<?php
namespace Tests\Unit\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Customer;
use App\Repositories\Eloquent\CustomerRepository;

class CustomerRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected CustomerRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CustomerRepository;
    }

    public function test_it_can_get_all_customers()
    {
        Customer::factory()->count(3)->create();

        $customers = $this->repository->getAll();

        $this->assertCount(3, $customers);
    }

    public function test_int_can_find_customer_by_id()
    {
        $customer = Customer::factory()->create();

        $found = $this->repository->findById($customer->id);

        $this->assertEquals($customer->id, $found->id);
        $this->assertEquals($customer->name, $found->name);
    }

    public function test_it_can_create_a_customer()
    {
        $data = [
            'name' => 'テスト太郎A',
            'email' => 'aaa@mail.com',
            'phone' => '0001112223',
            'cellphone' => '11122225555',
            'address' => '1112222 東京都テスト区テスト1-5-5',
            'birthday' => '2025-08-01',
        ];

        $customer = $this->repository->create($data);

        // DBに保存されていることを確認
        $this->assertDatabaseHas('customers', [
            'name' => 'テスト太郎A',
            'email' => 'aaa@mail.com',
            'phone' => '0001112223',
            'cellphone' => '11122225555',
            'address' => '1112222 東京都テスト区テスト1-5-5',
            'birthday' => '2025-08-01',
        ]);

        $this->assertEquals('aaa@mail.com', $customer->email);
    }

    public function test_it_can_update_a_customer()
    {
        $customer = Customer::factory()->create();

        $data = ['name' => 'テスト更新太郎'];

        $updated = $this->repository->update($customer->id, $data);

        $this->assertEquals('テスト更新太郎', $updated->name);
        $this->assertDatabaseHas('customers', ['id' => $customer->id, 'name' => 'テスト更新太郎']);
    }

    public function test_it_can_delete_a_customer()
    {
        $customer = Customer::factory()->create();

        $result = $this->repository->delete($customer->id);

        $this->assertEquals(1, $result); // delete returns the number of deleted rows
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }

    public function test_it_can_search_customers_with_filters()
    {
        $customer1 = Customer::factory()->create(['name' => 'Alice', 'email' => 'alice@example.com']);
        $customer2 = Customer::factory()->create(['name' => 'Bob', 'email' => 'bob@example.com']);

        $filters = ['name' => 'Alice'];
        $results = $this->repository->search($filters);

        $this->assertCount(1, $results);
        $this->assertEquals('Alice', $results->first()->name);
    }
}
