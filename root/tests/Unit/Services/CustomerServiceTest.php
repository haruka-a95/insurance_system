<?php
namespace Tests\Unit\Services;

use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Services\CustomerService;
use Tests\TestCase;
use Mockery;
use Exception;

class CustomerServiceTest extends TestCase
{
    protected $customerRepoMock;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->customerRepoMock = Mockery::mock(CustomerRepositoryInterface::class);
        $this->service = new CustomerService($this->customerRepoMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_all_customers()
    {
        $expected = [
            ['id' => 1, 'name' => 'テスト'],
            ['id' => 2, 'name' => '花子'],
        ];

        $this->customerRepoMock
            ->shouldReceive('getAll')
            ->once()
            ->andReturn($expected);

            $result = $this->service->getAllCustomers();
            $this->assertSame($expected, $result);
    }

    public function test_create_a_customer_commits_transaction()
    {
        $data = ['name' => 'テスト', 'email' => 'aaa@example.com'];

        $this->customerRepoMock
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn((object)$data);

            $result = $this->service->createCustomer($data);

            $this->assertEquals('テスト', $result->name);
    }

    public function test_create_and_throw_customer_exception_rollback()
    {
        $this->expectException(Exception::class);

        $data = ['name' => 'テスト'];

        $this->customerRepoMock
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andThrow(new Exception('DBエラー'));

            $this->service->createCustomer($data);
    }

    public function test_update_customer_commits_transaction()
    {
        $id = 1;
        $data = ['name' => '更新後'];
        $updatedCustomer = (object) array_merge(['id' => $id], $data);

        $this->customerRepoMock
            ->shouldReceive('update')
            ->with($id, $data)
            ->andReturn($updatedCustomer);

        $result = $this->service->updateCustomer($id, $data);
        $this->assertEquals('更新後', $result->name);
    }

    public function test_delete_customer_commits_transaction()
    {
        $id = 1;

        $this->customerRepoMock
            ->shouldReceive('delete')
            ->with($id)
            ->andReturnTrue();

        $result = $this->service->deleteCustomer($id);
        $this->assertTrue($result);
    }

    public function test_delete_customer_throws_exception_and_rolls_back()
    {
        $this->expectException(Exception::class);

        $id = 1;

        $this->customerRepoMock
            ->shouldReceive('delete')
            ->with($id)
            ->andThrow(new Exception('DBエラー'));

        $this->service->deleteCustomer($id);
    }

    public function test_search_returns_filtered_and_sorted_results()
    {
        $filters = ['name' => 'テスト'];
        $sort = 'name';
        $direction = 'desc';
        $expected = [
            ['id' => 1, 'name' => 'テスト'],
        ];

        $this->customerRepoMock
            ->shouldReceive('search')
            ->once()
            ->with($filters, $sort, $direction)
            ->andReturn($expected);

        $result = $this->service->search($filters, $sort, $direction);
        $this->assertSame($expected, $result);
    }

}