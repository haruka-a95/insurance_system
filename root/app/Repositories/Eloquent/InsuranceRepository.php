<?php
namespace App\Repositories\Eloquent;

use App\Models\InsurancePolicy;
use App\Repositories\Contracts\InsurancePolicyInterface;

//インターフェースを使用してEloquent ORM特有のロジックを実装
class InsuranceRepository implements InsurancePolicyInterface
{
    public function getAll()
    {
        return InsurancePolicy::all();
    }

    public function findById(int $id)
    {
        return InsurancePolicy::findOrFail($id);
    }

    public function create(array $data)
    {
        //多対多の関連付け用製品ID配列を分離
        $productIds = $data['product_ids'] ?? [];
        unset($data['product_ids']);

        $insurancePolicy = InsurancePolicy::create($data);
        if (!empty('$productIds')) {
            //製品を中間テーブルで紐づけ
            $insurancePolicy->products()->sync($productIds);
        }
        return $insurancePolicy;
    }

    public function update(int $id, array $data)
    {
        $productIds = $data['product_ids'] ?? [];
        unset($data['product_ids']);

        $insurancePolicy = InsurancePolicy::findOrFail($id);
        $insurancePolicy->update($data);

        if (!empty($productIds)) {
            $insurancePolicy->products()->sync($productIds);
        } else {
            // 製品が空なら関連解除も可能
            $insurancePolicy->products()->detach();
        }

        return $insurancePolicy;
    }

    public function delete(int $id)
    {
        return InsurancePolicy::destroy($id);
    }
}