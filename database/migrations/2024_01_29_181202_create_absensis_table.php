<?php

use App\Traits\MigrationTrait;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use MigrationTrait;

    protected 
        $table = 'absensis';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->generateCreateTable($this->table, [
            'user_id' => [
                'type'     => 'foreign',
                'nullable' => false,
            ],
            'type' => [
                'type'     => 'string',
                'nullable' => false,
            ],
            'date' => [
                'type'     => 'date',
                'nullable' => false,
            ],
            'time' => [
                'type'     => 'time',
                'nullable' => false,
            ],
            'latitude' => [
                'type'     => 'string',
                'nullable' => false,
            ],
            'longitude' => [
                'type'     => 'string',
                'nullable' => false,
            ],
            'ip_address' => [
                'type'     => 'string',
                'nullable' => true,
            ],
            'mac_address' => [
                'type'     => 'string',
                'nullable' => true,
            ],
            'device_info' => [
                'type'     => 'text',
                'nullable' => true,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->table);
    }
};
