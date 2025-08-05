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
        return InsurancePolicy::create($data);
    }

    public function update(int $id, array $data)
    {
        return InsurancePolicy::update($data);
    }

    public function delete(int $id)
    {
        return InsurancePolicy::destroy($id);
    }
}