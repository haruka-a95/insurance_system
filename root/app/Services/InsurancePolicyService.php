<?php
namespace App\Services;

use App\Repositories\Contracts\InsurancePolicyInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

//トランザクション処理・ログ処理・業務ロジック
class InsurancePolicyService
{
    protected $insurancePolicyRepo;

    public function __construct(InsurancePolicyInterface $insurancePolicyRepo)
    {
        $this->insurancePolicyRepo = $insurancePolicyRepo;
    }

    public function getAllInsurancePolicies()
    {
        return $this->insurancePolicyRepo->getAll();
    }

    public function createInsurancePolicy(array $data)
    {
        DB::beginTransaction();
        try {
            $insurancePolicy = $this->insurancePolicyRepo->create($data);
            DB::commit();
            return $insurancePolicy;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("顧客作成失敗: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateInsurancePolicy(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            $insurancePolicy = $this->insurancePolicyRepo->update($id, $data);
            DB::commit();
            return $insurancePolicy;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("顧客更新失敗" . $e->getMessage());
            throw $e;
        }
    }

    public function deleteInsurancePolicy(int $id)
    {
        DB::beginTransaction();
        try {
            $this->insurancePolicyRepo->delete($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("顧客削除失敗" . $e->getMessage());
            throw $e;
        }
    }
}