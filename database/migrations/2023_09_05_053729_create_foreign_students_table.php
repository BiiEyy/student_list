<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForeignStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foreign_students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('student_type');
            $table->integer('id_number');
            $table->string('name');
            $table->integer('age');
            $table->string('city');
            $table->string('mobile_number', 100);
            $table->decimal('grades', 30, 2)->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->unique(array('id_number', 'name', 'mobile_number'), 'local_indexs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('foreign_students');
    }
}
