<?php
namespace Tests\Unit\Services;

use App\Services\InsurancePolicySearchService;
use Tests\TestCase;
use Mockery;
use App\Models\InsurancePolicy;

class InsurancePolicySearchServiceTest extends TestCase
{

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function searchメソッドはpaginateを呼び出して結果を返す()
    {
        $filters = ['policy_number' => 'POL-1'];

        $queryMock = Mockery::mock('alias:App\Models\InsurancePolicy');
        $builderMock = Mockery::mock('Illuminate\Database\Eloquent\Builder');

        // query() -> with() -> where() -> paginate()
        $queryMock->shouldReceive('query')->once()->andReturn($builderMock);
        $builderMock->shouldReceive('with')->once()->with(['customer:id,name','products:id,name'])->andReturnSelf();
        $builderMock->shouldReceive('where')->once()->with('policy_number','like','%POL-1%')->andReturnSelf();
        $builderMock->shouldReceive('paginate')->once()->with(20)->andReturn('paginated_result');

        $service = new InsurancePolicySearchService();
        $result = $service->search($filters);

        $this->assertEquals('paginated_result', $result);
    }

        /** @test */
    public function searchAllメソッドはgetを呼び出して結果を返す()
    {
        $filters = ['customer_id' => 123];

        $queryMock = Mockery::mock('alias:App\Models\InsurancePolicy');
        $builderMock = Mockery::mock('Illuminate\Database\Eloquent\Builder');

        // query() -> with() -> where() -> get()
        $queryMock->shouldReceive('query')->once()->andReturn($builderMock);
        $builderMock->shouldReceive('with')->once()->with(['customer:id,name','products:id,name'])->andReturnSelf();
        $builderMock->shouldReceive('where')->once()->with('customer_id','like','%123%')->andReturnSelf();
        $builderMock->shouldReceive('get')->once()->andReturn(['policy1','policy2']);

        $service = new InsurancePolicySearchService();
        $result = $service->searchAll($filters);

        $this->assertCount(2, $result);
    }

}