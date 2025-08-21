<?php
namespace Tests\Unit\UseCases;

use Tests\TestCase;
use Mockery;
use App\Services\InsurancePolicySearchService;
use App\Services\InsurancePolicyService;
use App\UseCases\FetchInsurancePoliciesUseCase;

class FetchInsurancePoliciesUseCaseTest extends TestCase
{
    protected $searchServiceMock;
    protected $useCase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->searchServiceMock = Mockery::mock(InsurancePolicySearchService::class);
        $this->useCase = new FetchInsurancePoliciesUseCase($this->searchServiceMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function フィルタが空の場合は全件取得()
    {
        $this->searchServiceMock
            ->shouldReceive('search')
            ->once()
            ->with([], 20)
            ->andReturn('all_policies');

        $result = $this->useCase->handle([]);

        $this->assertEquals('all_policies', $result);
    }

    /** @test */
    public function フィルタがある場合は検索する()
    {
        $filters = ['policy_number' => 'POL-1'];

        $this->searchServiceMock
            ->shouldReceive('search')
            ->once()
            ->with($filters, 20)
            ->andReturn('filtered');

        $result = $this->useCase->handle($filters, 20);

        $this->assertEquals('filtered', $result);
    }

    /** @test */
    public function フィルタがnullや空文字の場合全件取得()
    {
        $filters = ['policy_number' => null, 'status' => ['']];

        $this->searchServiceMock
            ->shouldReceive('search')
            ->once()
            ->with([], 20)
            ->andReturn('all_policies');

        $result = $this->useCase->handle($filters);

        $this->assertEquals('all_policies', $result);
    }
}