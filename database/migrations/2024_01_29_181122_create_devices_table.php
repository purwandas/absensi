<?php

use App\Traits\MigrationTrait;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use MigrationTrait;

    protected 
        $table = 'devices';

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
            'mac_address' => [
                'type'     => 'string',
                'nullable' => false,
            ],
            'user_id' => [
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
