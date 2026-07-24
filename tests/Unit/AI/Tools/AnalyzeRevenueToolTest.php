<?php

use App\AI\Tools\Implementations\AnalyzeRevenueTool;
use App\Repositories\Contracts\SalesRepositoryInterface;
use Mockery\MockInterface;

beforeEach(function () {
    $this->repo = Mockery::mock(SalesRepositoryInterface::class);
    $this->tool = new AnalyzeRevenueTool($this->repo);
});

afterEach(function () {
    Mockery::close();
});

test('it resolves name and description', function () {
    expect($this->tool->name())->toBe('analyze_revenue')
        ->and($this->tool->description())->toBeString();
});

test('it calls getStatsByPeriod by default', function () {
    $this->repo->shouldReceive('getStatsByPeriod')
        ->with('this_month')
        ->once()
        ->andReturn(['total_revenue' => 1000]);

    $result = $this->tool->execute([
        'metric' => 'total',
        'period' => 'this_month'
    ]);

    expect($result)->toBe(['total_revenue' => 1000]);
});

test('it calls getAverageOrderValue when metric is aov', function () {
    $this->repo->shouldReceive('getAverageOrderValue')
        ->once()
        ->andReturn(150.5);

    $result = $this->tool->execute([
        'metric' => 'aov'
    ]);

    expect($result)->toBe([
        'metric' => 'Average Order Value',
        'value' => 150.5
    ]);
});

test('it calls getRevenueComparison when metric is comparison', function () {
    $this->repo->shouldReceive('getRevenueComparison')
        ->with('today', 'yesterday')
        ->once()
        ->andReturn(['difference' => 500]);

    $result = $this->tool->execute([
        'metric' => 'comparison',
        'period' => 'today',
        'compare_with' => 'yesterday'
    ]);

    expect($result)->toBe(['difference' => 500]);
});
