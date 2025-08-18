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

    public function search(array $filters, string $sort = 'id', string $direction = 'asc')
    {
        $query = Customer::query();

        if (!empty($filters['id'])) {
            $query->where('id', 'like', "%{$filters['id']}%");
        }

        if (!empty($filters['name'])) {
            $query->where('name', 'like', "%{$filters['name']}%");
        }

        if (!empty($filters['email'])) {
            $query->where('email', 'like', "%{$filters['email']}%");
        }

        if (!empty($filters['updated_from'])) {
            $query->where('updated_at', '>=', $filters['updated_from']);
        }

        if (!empty($filters['updated_to'])) {
            $query->where('updated_at', '<=', $filters['updated_to']);
        }

        if (!empty($filters['phone'])) {
            $query->where('phone', 'like', "%{$filters['phone']}%");
        }

        if (!empty($filters['cellphone'])) {
            $query->where('cellphone', 'like', "%{$filters['cellphone']}%");
        }

        if (!empty($filters['address'])) {
        $query->where('address', 'like', "%{$filters['address']}%");
        }

        //ソート適用
        $query->orderBy($sort, $direction);

        return $query->paginate(10);
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