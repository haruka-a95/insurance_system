<?php
namespace Tests\Unit\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\InsuranceProduct;
use App\Repositories\Eloquent\InsuranceProductRepository;
use Illuminate\Database\QueryException;

class InsuranceProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected InsuranceProductRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new InsuranceProductRepository;
    }

    /** @test */
    public function 全製品を取得できる()
    {
        InsuranceProduct::factory()->count(3)->create();

        $products = $this->repository->getAll();

        $this->assertCount(3, $products);
    }

    /** @test */
    public function IDで製品を取得できる()
    {
        $product = InsuranceProduct::factory()->create();

        $found = $this->repository->findById($product->id);

        $this->assertEquals($product->id, $found->id);
        $this->assertEquals($product->name, $found->name);
    }

    /** @test */
    public function 正しい情報で製品を登録できる()
    {
        $data = [
            'name' => 'テスト保険',
            'description' => '説明文',
            'type' => 'women',
            'approval_status' => 'pending'
        ];

        $product = $this->repository->create($data);

        // DBに保存されていることを確認
        $this->assertDatabaseHas('insurance_products', [
            'name' => 'テスト保険',
            'description' => '説明文',
            'type' => 'women',
            'approval_status' => 'pending'
        ]);

        $this->assertEquals('テスト保険', $product->name);
    }

    /** @test */
    public function 製品情報を更新したときDBも更新される()
    {
        $product = InsuranceProduct::factory()->create();

        $data = ['name' => 'テスト更新'];

        $updated = $this->repository->update($product->id, $data);

        $this->assertEquals('テスト更新', $updated->name);
        $this->assertDatabaseHas('insurance_products', ['id' => $product->id, 'name' => 'テスト更新']);
    }

    /** @test */
    public function 製品を削除できる()
    {
        $product = InsuranceProduct::factory()->create();

        $result = $this->repository->delete($product->id);

        $this->assertEquals(1, $result);
        $this->assertDatabaseMissing('insurance_products', ['id' => $product->id]);
    }
}
