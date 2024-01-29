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
            'date' => [
                'type'     => 'date',
                'nullable' => false,
            ],
            'time' => [
                'type'     => 'time',
                'nullable' => false,
            ],
            'ip_address' => [
                'type'     => 'string',
                'nullable' => true,
            ],
            'device_id' => [
                'type'     => 'foreign',
                'nullable' => false,
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
