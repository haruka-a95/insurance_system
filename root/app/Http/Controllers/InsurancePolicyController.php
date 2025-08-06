<?php
namespace App\Http\Controllers;

use App\Models\InsurancePolicy;
use App\Repositories\Contracts\InsurancePolicyInterface;
use App\Services\InsurancePolicyService;
use App\Http\Requests\StoreInsurancePolicyRequest;
use App\Http\Requests\UpdateInsurancePolicyRequest;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Contracts\InsuranceProductInterface;

class InsurancePolicyController extends Controller
{
    protected $insurancePolicyRepo;
    protected $insurancePolicyService;
    protected $customerRepo;
    protected $productRepo;

    public function __construct(
        InsurancePolicyInterface $insurancePolicyRepo,
        InsurancePolicyService $insurancePolicyService,
        CustomerRepositoryInterface $customerRepo,
        InsuranceProductInterface $productRepo
        ) {
        $this->insurancePolicyRepo = $insurancePolicyRepo;
        $this->insurancePolicyService = $insurancePolicyService;
        $this->customerRepo = $customerRepo;
        $this->productRepo = $productRepo;
    }

    public function index()
    {
        $insurancePolicies = $this->insurancePolicyRepo->getAll();
        return view('insurance_policies.index', compact('insurancePolicies'));
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