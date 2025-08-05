<?php
namespace App\Repositories\Eloquent;

use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;

//インターフェースを使用してEloquent ORM特有のロジックを実装
class CustomerRepository implements CustomerRepositoryInterface
{
    public function getAll()
    {
        return Customer::all();
    }

    public function findById(int $id)
    {
        return Customer::findOrFail($id);
    }

    public function create(array $data)
    {
        return Customer::create($data);
    }

    public function update(int $id, array $data)
    {
        $customer = Customer::findOrFail($id);
        $customer->update($data);
        return $customer;
    }

    public function delete(int $id)
    {
        return Customer::destroy($id);
    }
}