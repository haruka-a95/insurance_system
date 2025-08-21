<?php
namespace Tests\Unit\Repositories;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\InsurancePolicy;
use App\Repositories\Eloquent\InsuranceRepository;
use Illuminate\Database\QueryException;
use App\Enums\PolicyStatus;
use Illuminate\Validation\ValidationException;

class InsuranceRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected InsuranceRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new InsuranceRepository;
    }

    /** @test */
    public function 全ポリシーを取得できる()
    {
        InsurancePolicy::factory()->count(3)->create();

        $products = $this->repository->getAll();

        $this->assertCount(3, $products);
    }

    /** @test */
    public function IDでポリシーを取得できる()
    {
        $product = InsurancePolicy::factory()->create();

        $found = $this->repository->findById($product->id);

        $this->assertEquals($product->id, $found->id);
        $this->assertEquals($product->name, $found->name);
    }

    /** @test */
    public function 正しい情報でポリシーを登録できる()
    {
        $customer = Customer::factory()->create();

        $data = [
            'policy_number' => 'POL-1',
            'customer_id' => $customer->id,
            'start_date' => '2025-01-01',
            'end_date' => '2026-01-01',
            'premium_amount' => 15000,
            'status' => PolicyStatus::ACTIVE,
        ];

        $policy = $this->repository->create($data);

        // DBに保存されていることを確認
        $this->assertDatabaseHas('insurance_policies', [
            'policy_number' => 'POL-1',
            'customer_id' => $customer->id,
            'start_date' => '2025-01-01',
            'end_date' => '2026-01-01',
            'premium_amount' => 15000,
            'status' => PolicyStatus::ACTIVE,
        ]);

        $this->assertEquals('POL-1', $policy->policy_number);
    }

    /** @test */
    public function ポリシー情報を更新したときDBも更新される()
    {
        $customer = Customer::factory()->create();

        $data = [
            'policy_number' => 'POL-1',
            'customer_id' => $customer->id,
            'start_date' => '2025-01-01',
            'end_date' => '2026-01-01',
            'premium_amount' => 15000,
            'status' => PolicyStatus::ACTIVE,
        ];

        $policy = $this->repository->create($data);

        $updatedData = ['premium_amount' => 50000];

        $updated = $this->repository->update($policy->id, $updatedData);

        $this->assertEquals(50000, $updated->premium_amount);
        $this->assertDatabaseHas('insurance_policies', ['id' => $policy->id, 'premium_amount' => 50000]);
    }

    /** @test */
    public function ポリシーを削除できる()
    {
        $product = InsurancePolicy::factory()->create();

        $result = $this->repository->delete($product->id);

        $this->assertEquals(1, $result);
        $this->assertDatabaseMissing('insurance_policies', ['id' => $product->id]);
    }
}