<?php
namespace App\Services;

use App\Enums\ApprovalStatus;
use App\Enums\InsuranceType;
use App\Repositories\Contracts\InsuranceProductInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;
use League\Csv\CharsetConverter;
use League\Csv\Statement;

class InsuranceProductService
{
    protected $insuranceProductRepo;

    public function __construct(InsuranceProductInterface $insuranceProductRepo)
    {
        $this->insuranceProductRepo = $insuranceProductRepo;
    }

    public function getAllInsuranceProduct()
    {
        return $this->insuranceProductRepo->getAll();
    }

    public function createInsuranceProduct(array $data)
    {
        DB::beginTransaction();
        try {
            $insuranceProduct = $this->insuranceProductRepo->create($data);
            DB::commit();
            return $insuranceProduct;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("保険製品作成失敗: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateInsuranceProduct(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            $insuranceProduct = $this->insuranceProductRepo->update($id, $data);
            DB::commit();
            return $insuranceProduct;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('保険製品更新失敗' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteInsuranceProduct(int $id)
    {
        DB::beginTransaction();
        try {
            $this->insuranceProductRepo->delete($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('保険製品削除失敗' . $e->getMessage());
            throw $e;
        }
    }

    public function importFromCsv($file)
    {
        //ファイルの読み込み(1000文字)
        $sample = file_get_contents($file->getRealPath(), false, null, 0, 1000);

        //区切り文字の判定
        $commaCount = substr_count($sample, ',');
        $tabCount = substr_count($sample, "\t");
        $delimiter = $tabCount > $commaCount ? "\t" : ",";

        //CSV Reader作成
        $csv = Reader::createFromPath($file->getRealPath(), 'r');
        $csv->setDelimiter($delimiter);

        //文字コード判定
        $encoding = mb_detect_encoding($sample, ['UTF-8', 'SJIS-win', 'SJIS', 'CP932'], true);

        if ($encoding !== 'UTF-8') {
            // SJIS 系なら UTF-8 に変換
            CharsetConverter::addTo($csv, 'SJIS-win', 'UTF-8');
        }

        // 1行目をヘッダーに設定
        $csv->setHeaderOffset(0);
        // ヘッダーをトリムして再設定
        $headers = array_map('trim', $csv->getHeader());

        //ヘッダーの定義
        $requiredHeaders = ['name', 'description', 'type', 'approval_status'];
        $invalidHeaders = array_diff($requiredHeaders, $headers);

        //不正なヘッダーがある場合はエラーで処理中断
        if (!empty($invalidHeaders)) {
            throw new Exception('CSVヘッダーが不正です: ' . implode(',', $invalidHeaders));
        }

        // Statement を使ってレコード取得
        $stmt = (new Statement());
        $records = $stmt->process($csv, $headers);

        $errors = [];
        $successCount = 0;

        DB::beginTransaction();

        try {
            foreach ($records as $index => $row) {
                $rowNumber = $index + 2;

                try {
                    $name = trim($row['name'] ?? '');
                    $description = trim($row['description'] ?? '');
                    $typeValue = strtolower(trim($row['type'] ?? ''));
                    $statusValue = strtolower(trim($row['approval_status'] ?? ''));

                    $invalidColumns = [];

                    if ($name === '') {
                        $invalidColumns[] = "name";
                    }

                    $type = InsuranceType::tryFrom($typeValue);
                    if (!$type) {
                        $invalidColumns[] = "type";
                    }

                    $approval_status = ApprovalStatus::tryFrom($statusValue);
                    if (!$approval_status) {
                        $invalidColumns[] = "approval_status";
                    }

                    if (!empty($invalidColumns)) {
                        throw new \Exception("不正な列: " . implode(', ', $invalidColumns));
                    }

                    //登録データ
                   $data = [
                        'name' => $name,
                        'description' => $description,
                        'type' => $type,
                        'approval_status' => $approval_status,
                    ];

                    //既存サービスメソッド経由で登録
                    $this->createInsuranceProduct($data);
                    $successCount++;

                } catch (\Exception $e) {
                    $errors[] = [
                        'row' => $rowNumber,
                        'data' => $row,
                        'error' => $e->getMessage(),
                    ];
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return [
            'success' => $successCount,
            'errors' => $errors,
        ];
    }
}