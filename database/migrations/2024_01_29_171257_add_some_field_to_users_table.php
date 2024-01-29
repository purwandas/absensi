<?php

use App\Traits\MigrationTrait;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use MigrationTrait;
    
    protected 
        $table       = 'users',
        $columns     = ['nik' => 'string', 'jabatan_id' => 'integer'],
        $afterColumn = 'name';

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!$this->hasColumn())
        Schema::table($this->table, function (Blueprint $table) {
            foreach ($this->columns as $column => $type) {
                $table->$type($column)->nullable()->after($this->afterColumn);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if ($this->hasColumn())
        Schema::table($this->table, function (Blueprint $table) {
            $table->dropColumn(array_keys($this->columns));
        });
    }
};
