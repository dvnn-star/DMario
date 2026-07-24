<?php

use App\AI\Tools\Implementations\AnalyzeMenuPerformanceTool;
use App\Repositories\Contracts\MenuRepositoryInterface;
use Mockery\MockInterface;

beforeEach(function () {
    $this->repo = Mockery::mock(MenuRepositoryInterface::class);
    $this->tool = new AnalyzeMenuPerformanceTool($this->repo);
});

afterEach(function () {
    Mockery::close();
});

test('it delegates to getMenuPerformance with proper parameters', function () {
    $this->repo->shouldReceive('getMenuPerformance')
        ->with('quantity_asc', 5)
        ->once()
        ->andReturn([
            ['name' => 'Dead Stock Menu', 'total_quantity' => 0]
        ]);

    $result = $this->tool->execute([
        'sort_by' => 'quantity_asc',
        'limit' => 5
    ]);

    expect($result)->toBe([
        ['name' => 'Dead Stock Menu', 'total_quantity' => 0]
    ]);
});
