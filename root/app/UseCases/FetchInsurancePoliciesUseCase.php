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
     * 保険契約一覧を取得する
     * 空フィルターなら全件取得
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function handle(array $filters = [], int $perPage = 20)
    {
        // nullや空配列を除去
        $filters = array_map(function($v){
            if (is_array($v)) {
                return array_filter($v, fn($val) => !is_null($val) && $val !== '');
            }
            return $v;
        }, $filters);

        // フィルターが空なら全件取得
        $hasFilters = array_filter($filters, fn($v) => !empty($v));

        if ($hasFilters) {
            return $this->searchService->search($filters, $perPage);
        } else {
            return $this->searchService->search([], $perPage);
        }
    }
}