<?php

use App\AI\Orchestrator\ContextFormatter;

beforeEach(function () {
    $this->formatter = new ContextFormatter();
});

test('it formats analyze_revenue output correctly for totals', function () {
    $data = [
        'total_revenue' => 1500000,
        'total_orders' => 10,
        'period' => 'this_month'
    ];
    
    $result = $this->formatter->format(['analyze_revenue' => $data]);
    
    expect($result)->toContain('Revenue Statistics:')
        ->and($result)->toContain('Period: this_month')
        ->and($result)->toContain('Total Revenue: IDR 1.500.000')
        ->and($result)->toContain('Total Orders: 10');
});

test('it formats analyze_revenue comparison output correctly', function () {
    $data = [
        'period_1' => 'today',
        'period_1_revenue' => 200000,
        'period_2' => 'yesterday',
        'period_2_revenue' => 100000,
        'difference' => 100000,
        'percentage_change' => 100,
        'trend' => 'up'
    ];
    
    $result = $this->formatter->format(['analyze_revenue' => $data]);
    
    expect($result)->toContain('Revenue Comparison:')
        ->and($result)->toContain('Period 1 (today): IDR 200.000')
        ->and($result)->toContain('Period 2 (yesterday): IDR 100.000')
        ->and($result)->toContain('Difference: IDR 100.000')
        ->and($result)->toContain('Trend: up (100%)');
});

test('it formats analyze_menu_performance correctly', function () {
    $data = [
        ['name' => 'Nasi Goreng', 'total_quantity' => 50, 'total_revenue' => 1000000],
        ['name' => 'Es Teh', 'total_quantity' => 100, 'total_revenue' => 500000],
    ];

    $result = $this->formatter->format(['analyze_menu_performance' => $data]);
    
    expect($result)->toContain('Menu Performance Analysis:')
        ->and($result)->toContain('#1 Nasi Goreng — 50 orders (IDR 1.000.000)')
        ->and($result)->toContain('#2 Es Teh — 100 orders (IDR 500.000)');
});

test('it formats analyze_payments correctly', function () {
    $data = [
        'period' => 'today',
        'total_orders' => 20,
        'distribution' => [
            'qris' => ['count' => 15, 'revenue' => 1500000, 'percentage' => 75],
            'cash' => ['count' => 5, 'revenue' => 500000, 'percentage' => 25],
        ]
    ];

    $result = $this->formatter->format(['analyze_payments' => $data]);
    
    expect($result)->toContain('Payment Analytics (Period: today):')
        ->and($result)->toContain('Total Orders: 20')
        ->and($result)->toContain('qris: 15 orders (75%) — IDR 1.500.000')
        ->and($result)->toContain('cash: 5 orders (25%) — IDR 500.000');
});
