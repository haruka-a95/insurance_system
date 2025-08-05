<?php
namespace App\Repositories\Eloquent;

//インターフェースを使用してEloquent ORM特有のロジックを実装

use App\Repositories\Contracts\InsuranceProductInterface;
use App\Models\InsuranceProduct;

class InsuranceProductRepository implements InsuranceProductInterface
{
    public function getAll()
    {
        return InsuranceProduct::all();
    }

    public function findById(int $id)
    {
        return InsuranceProduct::findOrFail($id);
    }

    public function create(array $data)
    {
        return InsuranceProduct::create($data);
    }

    public function update(int $id, array $data)
    {
        return InsuranceProduct::update($id, $data);
    }

    public function delete(int $id)
    {
        return InsuranceProduct::destroy($id);
    }
}