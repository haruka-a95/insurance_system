<?php
namespace App\Services;

use App\Repositories\Contracts\CustomerRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerService
{
    protected $customerRepo;

    public function __construct(CustomerRepositoryInterface $customerRepo)
    {
        $this->customerRepo = $customerRepo;
    }

    public function getAllCustomers()
    {
        return $this->customerRepo->getAll();
    }

    public function createCustomer(array $data)
    {
        DB::beginTransaction();
        try {
            $customer = $this->customerRepo->create($data);
            DB::commit();
            return $customer;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("顧客作成失敗: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateCustomer(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            $customer = $this->customerRepo->update($id, $data);
            DB::commit();
            return $customer;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("顧客更新失敗: " . $e->getMessage());
            throw $e;
        }
    }

    public function deleteCustomer(int $id)
    {
        DB::beginTransaction();
        try {
            $this->customerRepo->delete($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("顧客削除失敗" . $e->getMessage());
            throw $e;
        }
    }
}