<?php
namespace App\Http\Controllers;

use App\Models\InsurancePolicy;
use App\Repositories\Contracts\InsurancePolicyInterface;
use App\Services\InsurancePolicyService;
use App\Http\Requests\StoreInsurancePolicyRequest;
use App\Http\Requests\UpdateInsurancePolicyRequest;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Contracts\InsuranceProductInterface;
use App\Services\InsurancePolicySearchService;
use App\UseCases\ExportFilteredInsurancePoliciesCsvUseCase;
use App\UseCases\ExportInsurancePolicyCsvUseCase;
use App\UseCases\FetchInsurancePoliciesUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class InsurancePolicyController extends Controller
{
    protected $insurancePolicyRepo;
    protected $insurancePolicyService;
    protected $customerRepo;
    protected $productRepo;
    protected $searchService;
    private FetchInsurancePoliciesUseCase $fetchInsurancePoliciesUseCase;
    private ExportInsurancePolicyCsvUseCase $exportCsvUseCase;
    private ExportFilteredInsurancePoliciesCsvUseCase $filteredCsvUseCase;

    public function __construct(
        InsurancePolicyInterface $insurancePolicyRepo,
        InsurancePolicyService $insurancePolicyService,
        CustomerRepositoryInterface $customerRepo,
        InsuranceProductInterface $productRepo,
        InsurancePolicySearchService $searchService,
        FetchInsurancePoliciesUseCase $fetchInsurancePoliciesUseCase,
        ExportInsurancePolicyCsvUseCase $exportCsvUseCase,
        ExportFilteredInsurancePoliciesCsvUseCase $filteredCsvUseCase
        ) {
        $this->insurancePolicyRepo = $insurancePolicyRepo;
        $this->insurancePolicyService = $insurancePolicyService;
        $this->customerRepo = $customerRepo;
        $this->productRepo = $productRepo;
        $this->searchService = $searchService;
        $this->fetchInsurancePoliciesUseCase = $fetchInsurancePoliciesUseCase;
        $this->exportCsvUseCase = $exportCsvUseCase;
        $this->filteredCsvUseCase = $filteredCsvUseCase;
    }

    public function index(Request $request)
    {
        $filters = $request->only([
            'policy_number',
            'customer_id',
            'product_name',
            'start_date_from',
            'start_date_to',
            'end_date_from',
            'end_date_to',
            'amount_min',
            'amount_max',
            'status',
            'sort_by',
        ]);

        // ユースケースの handle メソッドにフィルターを渡して処理を依頼
        $insurancePolicies = $this->fetchInsurancePoliciesUseCase->handle($filters, 20);

        return view('insurance_policies.index', compact('insurancePolicies','filters'));
    }

    public function exportCsv(Request $request)
    {
        $filters = $request->only([
            'policy_number',
            'customer_id',
            'product_name',
            'start_date_from',
            'start_date_to',
            'end_date_from',
            'end_date_to',
            'amount_min',
            'amount_max',
            'status'
        ]);

        return $this->exportCsvUseCase->handle($filters);
    }

    public function exportFiltered(Request $request)
    {
        $filters = $request->except(['page']); // ページ番号以外取得
        $sortBy = $request->input('sort_by');
        $sortDirection = $request->input('sort_direction', 'asc');

        return $this->filteredCsvUseCase->handle($filters, $sortBy, $sortDirection);
    }

    public function create(CustomerRepositoryInterface $customerRepo, InsuranceProductInterface $productRepo)
    {
        $customers = $customerRepo->getAll();
        $products = $productRepo->getAll();
        return view('insurance_policies.create', compact('customers', 'products'));
    }

    public function store(StoreInsurancePolicyRequest $request)
    {
        $this->insurancePolicyService->createInsurancePolicy($request->validated());
        return redirect()->route('insurance_policies.index')->with('success', '保険契約情報を登録しました');
    }

    public function edit(int $id)
    {
        $insurancePolicy = $this->insurancePolicyRepo->findById($id);
        return view('insurance_policies.edit', compact('insurancePolicy'));
    }

    public function update(UpdateInsurancePolicyRequest $request, int $id)
    {
        $this->insurancePolicyService->updateInsurancePolicy($id, $request->validated());
        return redirect()->route('insurance_policies.index')->with('success', '保険契約情報を更新しました');
    }

    public function destroy(int $id)
    {
        $this->insurancePolicyService->deleteInsurancePolicy($id);
        return redirect()->route('insurance_policies.index')->with('success', '保険契約情報を削除しました');
    }
}