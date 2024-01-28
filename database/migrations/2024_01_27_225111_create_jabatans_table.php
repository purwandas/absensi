<?php

use App\Traits\MigrationTrait;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use MigrationTrait;

    protected 
        $table = 'jabatans';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->generateCreateTable($this->table, [
            'name' => [
                'type'     => 'string',
                'nullable' => false,
            ],
            'employment_status' => [
                'type'     => 'string',
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
