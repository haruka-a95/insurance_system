<?php
namespace App\Http\Controllers;

use App\Models\InsurancePolicy;
use App\Repositories\Contracts\InsurancePolicyInterface;
use App\Services\InsurancePolicyService;
use App\Http\Requests\StoreInsurancePolicyRequest;
use App\Http\Requests\UpdateInsurancePolicyRequest;

class InsurancePolicyController extends Controller
{
    protected $insurancePolicyRepo;
    protected $insurancePolicyService;

    public function __construct(InsurancePolicyInterface $insurancePolicyRepo,InsurancePolicyService $insurancePolicyService)
    {
        $this->insurancePolicyRepo = $insurancePolicyRepo;
        $this->insurancePolicyService = $insurancePolicyService;
    }

    public function index()
    {
        $insurancePolicies = $this->insurancePolicyRepo->getAll();
        return view('insurance_policies.index', compact('insurancePolicies'));
    }

    public function create()
    {
        return view('insurance_policies.create');
    }

    public function store(StoreInsurancePolicyRequest $request)
    {
        $this->insurancePolicyService->createInsurancePolicy($request->validated());
        return redirect()->route('insurance_policies.index')->with('success', '保険契約情報を登録しました');
    }

    public function edit(int $id)
    {
        $insurancePolicy = $this->insurancePolicyRepo->findById($id);
        return view('customers.edit', compact('insurancePolicy'));
    }

    public function update(UpdateInsurancePolicyRequest $request, int $id)
    {
        $this->insurancePolicyService->updateInsurancePolicy($id, $request->validated());
        return redirect()->route('insurance_polices.index')->with('success', '保険契約情報を更新しました');
    }

    public function destroy(int $id)
    {
        $this->insurancePolicyService->deleteInsurancePolicy($id);
        return redirect()->route('insurance_policies.index')->with('success', '保険契約情報を削除しました');
    }
}