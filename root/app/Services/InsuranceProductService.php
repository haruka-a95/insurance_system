<?php
namespace App\Services;

use App\Repositories\Contracts\InsuranceProductInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InsuranceProductService
{
    protected $insuranceProductRepo;

    public function __construct(InsuranceProductInterface $insuranceProductRepo)
    {
        $this->insuranceProductRepo = $insuranceProductRepo;
    }

    public function getAllInsuranceProduct()
    {
        return $this->insuranceProductRepo->getAll();
    }

    public function createInsuranceProduct(array $data)
    {
        DB::beginTransaction();
        try {
            $insuranceProduct = $this->insuranceProductRepo->create($data);
            DB::commit();
            return $insuranceProduct;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("保険製品作成失敗: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateInsuranceProduct(int $id, array $data)
    {
        DB::beginTransaction();
        try {
            $insuranceProduct = $this->insuranceProductRepo->update($id, $data);
            DB::commit();
            return $insuranceProduct;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('保険製品更新失敗' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteInsuranceProduct(int $id)
    {
        DB::beginTransaction();
        try {
            $this->insuranceProductRepo->delete($id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('保険製品削除失敗' . $e->getMessage());
            throw $e;
        }
    }
}