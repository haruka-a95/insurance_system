<?php
namespace App\Http\Controllers;

use App\Services\InsuranceProductService;
use App\Repositories\Contracts\InsuranceProductInterface;
use App\Http\Requests\StoreInsuranceProductRequest;
use App\Http\Requests\UpdateInsuranceProductRequest;

class InsuranceProductController extends Controller
{
    protected $insuranceProductRepo;
    protected $insuranceProductService;

    public function __construct(InsuranceProductInterface $insuranceProductRepo, InsuranceProductService $insuranceProductService)
    {
        $this->insuranceProductRepo = $insuranceProductRepo;
        $this->insuranceProductService = $insuranceProductService;
    }

    public function index()
    {
        $insuranceProducts = $this->insuranceProductRepo->getAll();
        return view('insurance_products.index', compact('insuranceProducts'));
    }

    public function create()
    {
        return view('insurance_products.create');
    }

    public function store(StoreInsuranceProductRequest $request)
    {
        $this->insuranceProductService->createInsuranceProduct($request->validated());
        return redirect()->route('insurance_products.index')->with('success', '保険製品を登録しました');
    }

    public function edit(int $id)
    {
        $insuranceProduct = $this->insuranceProductRepo->findById($id);
        return view('customers.edit', compact('insuranceProduct'));
    }

    public function update(int $id, UpdateInsuranceProductRequest $request)
    {
        $this->insuranceProductService->updateInsuranceProduct($id, $request->validated());
        return redirect()->route('insurance_products.index')->with('success', '保険製品を更新しました');
    }

    public function destroy(int $id)
    {
        $this->insuranceProductService->deleteInsuranceProduct($id);
        return redirect()->route('insurance_products.index')->with('success', '保険製品を削除しました');
    }
}