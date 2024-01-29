<?php

namespace App\Traits;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use DB;

trait MigrationTrait {

    public function hasColumn()
    {
        return Schema::hasColumn($this->table, array_keys($this->columns)[0]);
    }

    public function generateAccessData($removeActionBy = false)
    {
        $tables = DB::select('SHOW TABLES'); // returns an array of stdObjects
        $tables = collect($tables)->map(function($item){
            foreach ($item as $key => $value) {
                return $value;
            }
        });

        foreach ($tables as $tableName) {
            $addDeletedBy = !Schema::hasColumn($tableName, 'deleted_at');
            $addCreatedBy = !Schema::hasColumn($tableName, 'created_by');

            if ($removeActionBy) {
                Schema::table($tableName, function(Blueprint $table) use ($addDeletedBy, $addCreatedBy){
                    $table->dropForeign(['deleted_by','updated_by','created_by']);
                });
                Schema::table($tableName, function(Blueprint $table) use ($addDeletedBy, $addCreatedBy){
                    $table->dropColumn(['deleted_by','updated_by','created_by']);
                });
            } elseif( $addDeletedBy || $addCreatedBy ) {
                Schema::table($tableName, function(Blueprint $table) use ($addDeletedBy, $addCreatedBy){
                    if ($addDeletedBy)
                        $table->softDeletes();

                    if ($addCreatedBy)
                        $this->addAccessDataColumns($table);
                });
            }
        }
    }

    public function addAccessDataArray($tableNames = [])
    {
        foreach ($tableNames as $tableName) {
            $this->addAccessData($tableName);
        }
    }

    public function addAccessData($tableName)
    {
        if(!Schema::hasColumn($tableName, 'created_by'))
            Schema::table($tableName, function (Blueprint $table) {
                $this->addAccessDataColumns($table);
            });
    }

    public function addAccessDataColumns(Blueprint $table, $create = false)
    {
        if ($create) {
            $table->unsignedInteger('deleted_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('created_by')->nullable();
        } else {
            $table->unsignedInteger('deleted_by')->after('deleted_at')->nullable();
            $table->unsignedInteger('updated_by')->after('deleted_at')->nullable();
            $table->unsignedInteger('created_by')->after('deleted_at')->nullable();
        }
    }

    public function generateCreateTable($tableName, $params = [])
    {
        Schema::dropIfExists($tableName);
        Schema::create($tableName, function (Blueprint $table) use ($params){
            $table->engine = 'InnoDB';

            $table->id();

                /*
                    [
                        'name' => [
                            'type'     => 'string',
                            'nullable' => false,
                        ],
                        'area_id' => [
                            'type'     => 'foreign',
                            'nullable' => false,
                            'foreign'  => areas,
                        ],
                    ]
                */

                foreach ($params as $column => $option) {
                    $type     = $option['type'];
                    $length   = @$option['length'] ?? NULL;
                    $nullable = @$option['nullable'] ?? false;
                    $default  = @$option['default'] ?? NULL;

                    if (in_array($type, ['rememberToken','timestamps','softDeletes'])) {
                        $table->$type();
                    } elseif ($type == 'foreign') {
                        $foreignTable = @$option['foreign'] ?? Str::of(substr($column, 0, -3))->plural();

                        $table->unsignedBigInteger($column)->nullable($nullable);
                        $table->foreign($column)->references('id')->on($foreignTable);
                    } else {
                        $table->$type($column, $length)->nullable($nullable)->default($default);
                    }


                }

            $this->addAccessDataColumns($table, true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

}