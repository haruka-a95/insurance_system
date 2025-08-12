<?php
namespace App\UseCases;

use App\Services\InsurancePolicySearchService;

class FetchInsurancePoliciesUseCase
{
    protected InsurancePolicySearchService $searchService;

    public function __construct(InsurancePolicySearchService $searchService)
    {
        $this->searchService = $searchService;
    }


    /**
     * 保険契約一覧を取得する（全件取得などのビジネスロジックもここに記述可能）
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function handle(array $filters, int $perPage = 20)
    {
        //フィルタ条件に基づく一覧取得ロジック
        return $this->searchService->search($filters, $perPage);
    }
}