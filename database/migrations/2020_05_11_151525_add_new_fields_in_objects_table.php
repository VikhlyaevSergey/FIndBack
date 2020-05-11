<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsInObjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'objects', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('type')->nullable()->index();
            $table->string('pet_type')->nullable()->index();
            $table->string('regime')->nullable()->index();
            $table->date('date_of_losing')->nullable();
            $table->string('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'objects', function (Blueprint $table) {
            $table->dropColumn(
                [
                    'name',
                    'description',
                    'type',
                    'pet_type',
                    'regime',
                    'date_of_losing',
                    'image',
                ]);
        });
    }
}
