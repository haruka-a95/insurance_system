<?php
namespace Tests\Unit\Services;

use App\Repositories\Contracts\InsuranceProductInterface;
use App\Services\InsuranceProductService;
use Tests\TestCase;
use Mockery;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Enums\InsuranceType;
use App\Enums\ApprovalStatus;
use Illuminate\Http\UploadedFile;

class InsuranceProductServiceTest extends TestCase
{
    protected $productRepoMock;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productRepoMock = Mockery::mock(InsuranceProductInterface::class);
        $this->service = new InsuranceProductService($this->productRepoMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function 全保険製品を取得できる()
    {
        $expected = [
            ['id' => '1', 'name' => 'テスト1', 'description' => '説明', 'type' => InsuranceType::ANNUITIES, 'approval_status' => ApprovalStatus::APPROVED],
            ['id' => '2', 'name' => 'テスト2', 'description' => '説明', 'type' => InsuranceType::HEALTH_RIDER, 'approval_status' => ApprovalStatus::PENDING],
        ];

        $this->productRepoMock
            ->shouldReceive('getAll')
            ->once()
            ->andReturn($expected);

        $result = $this->service->getAllInsuranceProduct();
        $this->assertSame($expected, $result);
    }

    /** @test */
    public function 正しいデータで保険製品を登録できる()
    {
        $data =  [ 'name' => 'テスト1', 'description' => '説明', 'type' => InsuranceType::ANNUITIES, 'approval_status' => ApprovalStatus::APPROVED];

        $this->productRepoMock
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn((object)$data);

        $result = $this->service->createInsuranceProduct($data);

        $this->assertEquals('テスト1', $result->name);
    }

    /** @test */
    public function 保険登録失敗時にロールバックして例外を投げる()
    {
        $this->expectException(Exception::class);

        $data = ['name' => 'テスト'];

        $this->productRepoMock
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andThrow(new Exception('DBエラー'));

        Log::shouldReceive('error')->once();

        $this->service->createInsuranceProduct($data);
    }

    /** @test */
    public function 保険製品を更新できる()
    {
        $id = 1;
        $data =  [ 'name' => '更新後', 'description' => '説明', 'type' => InsuranceType::ANNUITIES, 'approval_status' => ApprovalStatus::PENDING];

        $updated = (object) array_merge(['id' => $id], $data);

        $this->productRepoMock
            ->shouldReceive('update')
            ->once()
            ->with($id, $data)
            ->andReturn($updated);

        $result = $this->service->updateInsuranceProduct($id, $data);

        $this->assertEquals('更新後', $result->name);
    }

    /** @test */
    public function 保険製品を削除できる()
    {
        $id = 1;

        $this->productRepoMock->
            shouldReceive('delete')
            ->once()
            ->with($id)
            ->andReturnTrue();

        $result = $this->service->deleteInsuranceProduct($id);

        $this->assertTrue($result);
    }

    /** @test */
    public function 保険削除失敗時にロールバックして例外を投げる()
    {
        $this->expectException(Exception::class);

        $id = 1;

        $this->productRepoMock
            ->shouldReceive('delete')
            ->once()
            ->with($id)
            ->andThrow(new Exception('DBエラー'));

        Log::shouldReceive('error')->once();

        $this->service->deleteInsuranceProduct($id);
    }

    /** @test */
    public function CSVから保険を登録できる()
    {
        //CSV中身を作成
        $csvContent = <<<CSV
name,description,type,approval_status
CSV保険1,説明1,annuities,approved
CSV保険2,説明2,health_rider,pending
CSV;

        //一時ファイル作成
        $tmpFilePath = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($tmpFilePath, $csvContent);

        $file = new UploadedFile(
            $tmpFilePath,
            'text.csv',
            'text/csv',
            null,
            true
        );

        $this->productRepoMock
            ->shouldReceive('create')
            ->twice()
            ->andReturnUsing(function ($data) {
                return (object) $data;
            });

        $result = $this->service->importFromCsv($file);

        $this->assertEquals(2, $result['success']);
        $this->assertEmpty($result['errors']);
    }

    /** @test */
    public function CSVデータに不正な値があればエラーが返る()
    {
        //CSV中身を作成
        $csvContent = <<<CSV
name,description,type,approval_status
CSV保険1,説明1,annuities,キャンセル
CSV保険2,説明2,health_rider,pending
CSV;

        //一時ファイル作成
        $tmpFilePath = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($tmpFilePath, $csvContent);

        $file = new UploadedFile(
            $tmpFilePath,
            'text.csv',
            'text/csv',
            null,
            true
        );

        $this->productRepoMock
            ->shouldReceive('create')
            ->once()//成功時の1回
            ->andReturnUsing(function ($data) {
                return (object) $data;
            });

        $result = $this->service->importFromCsv($file);

        //成功件数
        $this->assertEquals(1, $result['success']);
        //エラー件数
        $this->assertCount(1, $result['errors']);
        //エラーメッセージ
        $this->assertEquals(
            '不正な列: approval_status',
            $result['errors'][0]['error']
        );
        //エラーの行番号
        $this->assertEquals(3, $result['errors'][0]['row']);
    }

    /** @test */
    public function CSV内ヘッダーに不正な値があればエラーが返る()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('CSVヘッダーが不正です');

        $csvContent = "名前,説明,タイプ,承認状況\nCSV保険1,説明1,ANNUITIES,APPROVED";
        $tmpFilePath = tempnam(sys_get_temp_dir(), 'csv');
        file_put_contents($tmpFilePath, $csvContent);

        $file = new UploadedFile($tmpFilePath, 'text.csv', 'text/csv', null, true);
        $this->service->importFromCsv($file);
    }

}