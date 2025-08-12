<?php
namespace App\UseCases;

use App\Services\InsurancePolicySearchService;
use App\Services\InsurancePolicyService;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;

class ExportInsurancePolicyCsvUseCase
{
    protected InsurancePolicySearchService $searchService;

    public function __construct(InsurancePolicySearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function handle(array $filters): StreamedResponse
    {
        // 1. 検索結果取得
        $policies = $this->searchService->searchAll($filters);

        // 2. StreamedResponseでCSVをストリーム出力
        return new StreamedResponse(function () use ($policies){
            $handle = fopen('php://output', 'w');

            // ヘッダー行をShift_JISに変換して出力
            $header = ['契約番号', '顧客名', '開始日', '満了日', '保険料', 'ステータス'];
            fputcsv($handle, array_map(function ($item) {
                        return mb_convert_encoding($item, 'SJIS-win', 'UTF-8');
                    }, $header));

            // データ行 Shift_JISに変換して出力
            foreach ($policies as $policy) {
            $row = [
                $policy->policy_number,
                $policy->customer->name,
                $policy->start_date ? Carbon::parse($policy->start_date)->format('Y-m-d') : '',
                $policy->end_date ? Carbon::parse($policy->end_date)->format('Y-m-d') : '',
                $policy->premium_amount,
                $policy->status instanceof \BackedEnum ? $policy->status->label() : (string)$policy->status,
            ];
            fputcsv($handle, array_map(fn($item) => mb_convert_encoding((string)$item, 'SJIS-win', 'UTF-8'), $row));
        }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="insurance_policies.csv',
        ]);
    }
}