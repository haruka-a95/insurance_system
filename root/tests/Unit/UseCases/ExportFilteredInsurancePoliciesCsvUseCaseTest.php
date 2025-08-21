<?php
namespace Tests\Unit\UseCases;

use Tests\TestCase;
use Mockery;
use App\Services\InsurancePolicySearchService;
use App\UseCases\ExportFilteredInsurancePoliciesCsvUseCase;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportFilteredInsurancePoliciesCsvUseCaseTest extends TestCase
{
    protected $searchServiceMock;
    protected $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->searchServiceMock = Mockery::mock(InsurancePolicySearchService::class);
        $this->useCase = new ExportFilteredInsurancePoliciesCsvUseCase($this->searchServiceMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function CSVを出力できる()
    {
        //ダミーデータ
        $policies = collect([
            (object)[
                'id' => 1,
                'policy_number' => 'POL-001',
                'customer' => (object)['name' => '田中太郎'],
                'start_date' => new \DateTime('2025-01-01'),
                'end_date' => new \DateTime('2025-12-31'),
                'premium_amount' => 10000,
                'status' => 'active',
            ],
            (object)[
                'id' => 2,
                'policy_number' => 'POL-002',
                'customer' => (object)['name' => '佐藤花子'],
                'start_date' => new \DateTime('2025-02-01'),
                'end_date' => new \DateTime('2025-11-30'),
                'premium_amount' => 20000,
                'status' => 'expired',
            ],
        ]);

        $this->searchServiceMock
            ->shouldReceive('searchAll')
            ->once()
            ->with(['status' => ['active']])
            ->andReturn($policies);

        $response = $this->useCase->handle(['status' => ['active']], null);

        //レスポンスの型確認
        $this->assertInstanceOf(StreamedResponse::class, $response);

        //ヘッダー確認
        $headers = $response->headers->all();
        $this->assertStringContainsString('text/csv', $headers['content-type'][0]);
        $this->assertStringContainsString('保険契約抜粋.csv', $headers['content-disposition'][0]);

        //実際のCSV
        ob_start();
        $response->sendContent();
        $csvOutput = ob_get_clean();

        //BOM除去して比較
        $csvOutput = preg_replace('/^\xEF\xBB\xBF/', '', $csvOutput);

        //期待値
        $expected = <<<CSV
契約ID,契約番号,顧客名,開始日,終了日,保険料,ステータス
1,POL-001,田中太郎,2025-01-01,2025-12-31,10000,active
2,POL-002,佐藤花子,2025-02-01,2025-11-30,20000,expired

CSV;

        $this->assertEquals($expected, $csvOutput);
    }
}