<?php
namespace App\Http\Controllers;

use App\Enums\ApprovalStatus;
use App\Services\InsuranceProductService;
use App\Repositories\Contracts\InsuranceProductInterface;
use App\Http\Requests\StoreInsuranceProductRequest;
use App\Http\Requests\UpdateInsuranceProductRequest;
use Illuminate\Http\Request;

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

    public function show(int $id)
    {
        $insuranceProduct = $this->insuranceProductRepo->findById($id);
        return view('insurance_products.show', compact('insuranceProduct'));
    }

    public function edit(int $id)
    {
        $insuranceProduct = $this->insuranceProductRepo->findById($id);
        $statuses = ApprovalStatus::options();
        return view('insurance_products.edit', compact('insuranceProduct', 'statuses'));
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

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimetypes:text/csv,text/plain,application/vnd.ms-excel',
        ]);

        $file = $request->file('csv_file');

        //サービスクラスにCSVファイルを渡して処理
        $result = $this->insuranceProductService->importFromCsv($file);

        if (count($result['errors']) > 0) {
            return redirect()->back()
                ->withInput()
                ->with('import_errors', $result['errors'])
                ->with('success', "{$result['success']}件登録しました（エラーあり）");
        }

        return redirect()->route('insurance_products.index')
            ->with('success', "CSVから{$result['success']}件登録しました");
    }
}