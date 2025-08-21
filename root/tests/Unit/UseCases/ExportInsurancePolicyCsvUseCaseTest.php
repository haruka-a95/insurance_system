<?php
namespace Tests\Unit\UseCases;

use App\UseCases\ExportInsurancePolicyCsvUseCase;
use App\Services\InsurancePolicySearchService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportInsurancePolicyCsvUseCaseTest extends TestCase
{
    /** @test */
    public function CSVを出力できる()
    {
        $policies = collect([
            (object)[
                'policy_number' => 'POL-001',
                'customer' => (object)['name' => '田中太郎'],
                'start_date' => '2025-01-01',
                'end_date' => '2025-12-31',
                'premium_amount' => 10000,
                'status' => 'active',
            ],
            (object)[
                'policy_number' => 'POL-002',
                'customer' => (object)['name' => '佐藤花子'],
                'start_date' => '2025-02-01',
                'end_date' => '2025-11-30',
                'premium_amount' => 20000,
                'status' => 'expired',
            ],
        ]);

        $searchServiceMock = $this->createMock(InsurancePolicySearchService::class);
        $searchServiceMock->expects($this->once())
            ->method('searchAll')
            ->with([])
            ->willReturn($policies);

        $useCase = new ExportInsurancePolicyCsvUseCase($searchServiceMock);

        $response = $useCase->handle([]);
        $this->assertInstanceOf(StreamedResponse::class, $response);

        ob_start();
        $response->sendContent();
        $output = ob_get_clean();

        $csvOutput = mb_convert_encoding($output, 'UTF-8', 'SJIS-win');

        $expected = <<<CSV
契約番号,顧客名,開始日,満了日,保険料,ステータス
POL-001,田中太郎,2025-01-01,2025-12-31,10000,active
POL-002,佐藤花子,2025-02-01,2025-11-30,20000,expired

CSV;

        $this->assertEquals($expected, $csvOutput);
    }
}