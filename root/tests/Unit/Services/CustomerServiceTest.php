<?php
namespace Tests\Unit\Services;

use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Services\CustomerService;
use Tests\TestCase;
use Mockery;
use Exception;
use Illuminate\Support\Facades\Log;

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

    /** @test */
    public function 全顧客を取得できる()
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

    /** @test */
    public function 検索とソートができる()
    {
        $filters = ['name' => 'テスト太郎'];
        $expected = collect([['id' => 1, 'name' => 'テスト太郎']]);

        $this->customerRepoMock
            ->shouldReceive('search')
            ->with($filters, 'name', 'asc')
            ->once()
            ->andReturn($expected);

        $result = $this->service->search($filters, 'name', 'asc');

        $this->assertEquals($expected, $result);
    }

    /** @test */
    public function 顧客を作成できる()
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

    /** @test */
    public function 顧客作成失敗時にロールバックして例外を投げる()
    {
        $this->expectException(Exception::class);

        $data = ['name' => 'テスト'];

        $this->customerRepoMock
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andThrow(new Exception('DBエラー'));

            Log::shouldReceive('error')->once();

            $this->service->createCustomer($data);
    }

    /** @test */
    public function 顧客情報を更新できる()
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

    /** @test */
    public function 顧客を削除できる()
    {
        $id = 1;

        $this->customerRepoMock
            ->shouldReceive('delete')
            ->with($id)
            ->andReturnTrue();

        $result = $this->service->deleteCustomer($id);
        $this->assertTrue($result);
    }

    /** @test */
    public function 顧客削除失敗時にロールバックして例外を投げる()
    {
        $this->expectException(Exception::class);

        $id = 1;

        $this->customerRepoMock
            ->shouldReceive('delete')
            ->with($id)
            ->andThrow(new Exception('DBエラー'));

        Log::shouldReceive('error')->once();

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