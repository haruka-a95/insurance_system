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
use App\UseCases\ExportInsurancePolicyCsvUseCase;
use App\UseCases\FetchInsurancePoliciesUseCase;
use Illuminate\Http\Request;

class InsurancePolicyController extends Controller
{
    protected $insurancePolicyRepo;
    protected $insurancePolicyService;
    protected $customerRepo;
    protected $productRepo;
    protected $searchService;
    private FetchInsurancePoliciesUseCase $fetchInsurancePoliciesUseCase;
    private ExportInsurancePolicyCsvUseCase $exportCsvUseCase;

    public function __construct(
        InsurancePolicyInterface $insurancePolicyRepo,
        InsurancePolicyService $insurancePolicyService,
        CustomerRepositoryInterface $customerRepo,
        InsuranceProductInterface $productRepo,
        InsurancePolicySearchService $searchService,
        FetchInsurancePoliciesUseCase $fetchInsurancePoliciesUseCase,
        ExportInsurancePolicyCsvUseCase $exportCsvUseCase
        ) {
        $this->insurancePolicyRepo = $insurancePolicyRepo;
        $this->insurancePolicyService = $insurancePolicyService;
        $this->customerRepo = $customerRepo;
        $this->productRepo = $productRepo;
        $this->searchService = $searchService;
        $this->fetchInsurancePoliciesUseCase = $fetchInsurancePoliciesUseCase;
        $this->exportCsvUseCase = $exportCsvUseCase;
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
            'status'
        ]);

        // クリアフラグがある場合はセッションクリア
        if ($request->has('clear')) {
            session()->forget('insurance_policy_filters');
            $filters = []; // フィルターを空に
        } else {
            // 検索条件があればセッションに保存
            if (!empty(array_filter($filters))) {
                session(['insurance_policy_filters' => $filters]);
            } elseif (session()->has('insurance_policy_filters') && !$request->has('page')) {
                // ページング時はセッションの検索条件を使う
                $filters = session('insurance_policy_filters');
            }
        }

        // ユースケースの handle メソッドにフィルターを渡して処理を依頼
        $insurancePolicies = $this->fetchInsurancePoliciesUseCase->handle($filters);
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