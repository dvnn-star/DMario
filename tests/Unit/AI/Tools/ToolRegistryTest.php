<?php

use App\AI\Contracts\AITool;
use App\AI\Exceptions\SecurityException;
use App\AI\Tools\ToolRegistry;
use Illuminate\Container\Container;

class DummyTool implements AITool
{
    public function name(): string
    {
        return 'dummy_tool';
    }

    public function description(): string
    {
        return 'A dummy tool for testing.';
    }

    public function inputSchema(): array
    {
        return [
            'type' => 'object',
            'properties' => [
                'test' => ['type' => 'string']
            ]
        ];
    }

    public function execute(array $parameters): mixed
    {
        return ['status' => 'ok'];
    }
}

beforeEach(function () {
    $this->container = new Container();
    $this->registry = new ToolRegistry($this->container);
});

test('it can register a valid tool class', function () {
    $this->registry->register(DummyTool::class);
    
    $tool = $this->registry->resolve('dummy_tool');
    
    expect($tool)->toBeInstanceOf(DummyTool::class);
});

test('it throws exception when registering invalid tool class', function () {
    $this->registry->register(stdClass::class);
})->throws(InvalidArgumentException::class);

test('it generates correct tool definitions schema', function () {
    $this->registry->register(DummyTool::class);
    
    $definitions = $this->registry->getDefinitions();
    
    expect($definitions)->toBeArray()
        ->and($definitions)->toHaveCount(1)
        ->and($definitions[0]['type'])->toBe('function')
        ->and($definitions[0]['function']['name'])->toBe('dummy_tool')
        ->and($definitions[0]['function']['description'])->toBe('A dummy tool for testing.')
        ->and($definitions[0]['function']['parameters']['properties']['test']['type'])->toBe('string');
});

test('it enforces whitelist security', function () {
    $this->registry->register(DummyTool::class);
    
    // Whitelist blocks dummy_tool
    $this->registry->setWhitelist(['some_other_tool']);
    
    expect(fn() => $this->registry->resolve('dummy_tool'))
        ->toThrow(SecurityException::class, "Tool execution blocked: dummy_tool is not in the whitelist.");
});

test('it ignores non-whitelisted tools in definitions', function () {
    $this->registry->register(DummyTool::class);
    $this->registry->setWhitelist(['some_other_tool']);
    
    $definitions = $this->registry->getDefinitions();
    
    expect($definitions)->toBeEmpty();
});
