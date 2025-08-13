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
        $query = $this->searchService->searchQuery($filters)
            ->orderBy($sortBy ?? 'id', $sortDirection ?? 'asc');

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="保険契約抜粋.csv"',
        ];

        return new StreamedResponse(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));//文字化け防止

            fputcsv($handle, ['契約ID', '契約番号', '顧客名', '開始日', '終了日', '保険料', 'ステータス']);

            $query->chunk(1000, function ($policies) use ($handle){
                foreach ($policies as $policy) {
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
            });

            fclose($handle);
        }, 200, $headers);
    }
}