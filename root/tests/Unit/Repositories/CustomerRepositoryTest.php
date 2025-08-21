<?php
namespace Tests\Unit\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Customer;
use App\Repositories\Eloquent\CustomerRepository;
use Illuminate\Database\QueryException;

class CustomerRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected CustomerRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new CustomerRepository;
    }

    /** @test */
    public function 全顧客を取得できる()
    {
        Customer::factory()->count(3)->create();

        $customers = $this->repository->getAll();

        $this->assertCount(3, $customers);
    }

    /** @test */
    public function IDで顧客を取得できる()
    {
        $customer = Customer::factory()->create();

        $found = $this->repository->findById($customer->id);

        $this->assertEquals($customer->id, $found->id);
        $this->assertEquals($customer->name, $found->name);
    }

    /** @test */
    public function 正しい情報で顧客を作成できる()
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

    /** @test */
    public function メールアドレス未入力でエラーになる()
    {
        $this->expectException(QueryException::class);

        $this->repository->create(['email' => null]);
    }

    /** @test */
    public function 顧客名を更新したときDBも更新される()
    {
        $customer = Customer::factory()->create();

        $data = ['name' => 'テスト更新太郎'];

        $updated = $this->repository->update($customer->id, $data);

        $this->assertEquals('テスト更新太郎', $updated->name);
        $this->assertDatabaseHas('customers', ['id' => $customer->id, 'name' => 'テスト更新太郎']);
    }

    /** @test */
    public function 顧客情報を削除できる()
    {
        $customer = Customer::factory()->create();

        $result = $this->repository->delete($customer->id);

        $this->assertEquals(1, $result); // delete returns the number of deleted rows
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }

    /** @test */
    public function 顧客を検索できる()
    {
        $customer1 = Customer::factory()->create(['name' => 'Alice', 'email' => 'alice@example.com']);
        $customer2 = Customer::factory()->create(['name' => 'Bob', 'email' => 'bob@example.com']);

        $filters = ['name' => 'Alice'];
        $results = $this->repository->search($filters);

        $this->assertCount(1, $results);
        $this->assertEquals('Alice', $results->first()->name);
    }
}
