<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Services\CustomerService;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    protected $customerRepo;
    protected $customerService;

    public function __construct(CustomerRepositoryInterface $customerRepo, CustomerService $customerService)
    {
        $this->customerRepo = $customerRepo;
        $this->customerService = $customerService;
    }

    public function index(Request $request)
    {
        //検索条件取得
        $filters = $request->only(['name', 'birthday', 'phone', 'cellphone', 'email', 'updated_from', 'updated_to']);
        //サービスで検索
        $customers = $this->customerService->search($request->all(), $request->get('sort', 'id'), $request->get('direction', 'asc'));

        return view('customers.index', compact('customers', 'filters'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function show(int $id)
    {
        $customer = $this->customerRepo->findById($id);
        return view('customers.show', compact('customer'));
    }

    public function store(StoreCustomerRequest $request)
    {
        $this->customerService->createCustomer($request->validated());
        return redirect()->route('customers.index')->with('success', '顧客を登録しました');
    }

    public function edit(int $id)
    {
        $customer = $this->customerRepo->findById($id);
        return view('customers.edit', compact('customer'));
    }

    public function update(int $id, UpdateCustomerRequest $request)
    {
        $this->customerService->updateCustomer($id, $request->validated());
        return redirect()->route('customers.index')->with('success', '顧客情報を更新しました');
    }

    public function destroy(int $id)
    {
        $this->customerService->deleteCustomer($id);
        return redirect()->route('customers.index')->with('success', '顧客情報を削除しました');
    }
}