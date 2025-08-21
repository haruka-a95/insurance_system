<?php
namespace Tests\Unit\Services;

use App\Repositories\Contracts\InsurancePolicyInterface;
use App\Services\InsurancePolicyService;
use Tests\TestCase;
use Mockery;
use Exception;
use App\Enums\PolicyStatus;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class InsurancePolicyServiceTest extends TestCase
{
    protected $policyRepoMock;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policyRepoMock = Mockery::mock(InsurancePolicyInterface::class);
        $this->service = new InsurancePolicyService($this->policyRepoMock);

        //DB facadeをMock
        DB::shouldReceive('beginTransaction')->byDefault();
        DB::shouldReceive('commit')->byDefault();
        DB::shouldReceive('rollBack')->byDefault();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function 全ポリシーを取得できる()
    {
        $this->policyRepoMock
            ->shouldReceive('getAll')
            ->once()
            ->andReturn([
                ['id' => 1, 'name' => 'テスト1'],
                ['id' => 2, 'name' => 'テスト2']
            ]);

        $result = $this->service->getAllInsurancePolicies();

        $this->assertCount(2, $result);
        $this->assertEquals('テスト1', $result[0]['name']);
    }

    /** @test */
    public function 正しいデータでポリシーを作成できる()
    {
        $customer = Customer::factory()->create();

        $data = ['policy_number' => 'POL-1', 'customer_id' => $customer->id, 'start_date' => '2025-01-01', 'end_date' => '2026-01-01', 'premium_amount' => 12000, 'status' => PolicyStatus::ACTIVE];

        //例外なし
        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();
        DB::shouldReceive('rollBack')->never();

        $this->policyRepoMock
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn((object) $data);

        $result = $this->service->createInsurancePolicy($data);

        $this->assertEquals($data['policy_number'], $result->policy_number);
    }

    /** @test */
    public function 登録失敗時にロールバックして例外を投げる()
    {
        $data = ['policy_number' => 'POL-1', 'customer_id' => '', 'start_date' => '2025-01-01', 'end_date' => '2026-01-01', 'premium_amount' => 12000, 'status' => PolicyStatus::ACTIVE];

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->never();
        DB::shouldReceive('rollBack')->once();

        $this->policyRepoMock
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andThrow(new Exception('DB エラー'));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('DB エラー');

        $this->service->createInsurancePolicy($data);
    }

    /** @test */
    public function ポリシーを更新できる()
    {
        $id = 1;
        $data = ['policy_number' => 'POL-updated_1'];

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();
        DB::shouldReceive('rollBack')->never();

        $updated = (object) array_merge(['id' => $id], $data);

        $this->policyRepoMock
            ->shouldReceive('update')
            ->once()
            ->with($id, $data)
            ->andReturn($updated);

        $result = $this->service->updateInsurancePolicy($id, $data);

        $this->assertEquals($updated, $result);
    }

    /** @test */
    public function 更新失敗時にロールバックして例外を投げる()
    {
        $id = 1;
        $data = ['policy_number' => 'POL-1', 'customer_id' => '', 'start_date' => '2025-01-01', 'end_date' => '2026-01-01', 'premium_amount' => 12000, 'status' => PolicyStatus::ACTIVE];

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->never();
        DB::shouldReceive('rollBack')->once();

        $this->policyRepoMock
            ->shouldReceive('update')
            ->once()
            ->with($id, $data)
            ->andThrow(new Exception('DB エラー'));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('DB エラー');

        $this->service->updateInsurancePolicy($id, $data);
    }

    /** @test */
    public function ポリシーを削除できる()
    {
        $id = 1;

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->once();
        DB::shouldReceive('rollBack')->never();

        $this->policyRepoMock
            ->shouldReceive('delete')
            ->once()
            ->with($id);

        $result = $this->service->deleteInsurancePolicy($id);

        $this->assertTrue($result);
    }

    /** @test */
    public function 削除失敗時にロールバックする()
    {
        $id = 1;

        DB::shouldReceive('beginTransaction')->once();
        DB::shouldReceive('commit')->never();
        DB::shouldReceive('rollBack')->once();

        $this->policyRepoMock
            ->shouldReceive('delete')
            ->once()
            ->with($id)
            ->andThrow(new Exception('DB 削除エラー'));

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('DB 削除エラー');

        $this->service->deleteInsurancePolicy($id);
    }
}