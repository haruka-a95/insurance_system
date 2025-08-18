<?php
namespace App\UseCases;

use App\Services\InsurancePolicySearchService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportFilteredInsurancePoliciesCsvUseCase
{
    protected InsurancePolicySearchService $searchService;

    public function __construct(InsurancePolicySearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public function handle(array $filters, ?string $sortBy, ?string $sortDirection = 'asc')
    {
        //フィルター済み全件を取得
        $policies = $this->searchService->searchAll($filters);

        //並び替え
        if ($sortBy) {
            $policies = $policies->sortBy($sortBy, SORT_REGULAR, $sortDirection === 'desc');
        }

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="保険契約抜粋.csv"',
        ];

        return new StreamedResponse(function () use ($policies) {
            $handle = fopen('php://output', 'w');
            //文字化け防止
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, ['契約ID', '契約番号', '顧客名', '開始日', '終了日', '保険料', 'ステータス']);

            foreach ($policies->chunk(1000) as $chunk) {
                foreach ($chunk as $policy) {
                    fputcsv($handle, [
                        $policy->id,
                        $policy->policy_number,
                        $policy->customer->name ?? '',
                        $policy->start_date?->format('Y-m-d') ?? '',
                        $policy->end_date?->format('Y-m-d') ?? '',
                        $policy->premium_amount,
                        $policy->status instanceof \BackedEnum
                            ? $policy->status->label()
                            : (string) $policy->status,
                    ]);
                }
            }

            fclose($handle);
        }, 200, $headers);
    }
}