<?php

namespace Tests\Unit\Models;

use App\Models\table;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class TableTest extends TestCase
{
    use RefreshDatabase;

    public function test_table_automatically_generates_uuid_identifier_on_creation(): void
    {
        $table = table::create([
            'table_number' => 5,
            'qr_code_path' => 'qrcodes/table-5.png',
            'status' => 'available',
        ]);

        $this->assertNotEmpty($table->identifier);
        $this->assertTrue(Str::isUuid($table->identifier));
    }

    public function test_table_preserves_custom_identifier_if_provided(): void
    {
        $customUuid = (string) Str::uuid();

        $table = table::create([
            'table_number' => 10,
            'identifier' => $customUuid,
            'qr_code_path' => 'qrcodes/table-10.png',
            'status' => 'available',
        ]);

        $this->assertEquals($customUuid, $table->identifier);
    }
}
