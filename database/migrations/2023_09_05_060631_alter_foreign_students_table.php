<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterForeignStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foreign_students', function (Blueprint $table) {
            $table->enum('gender', ['Male', 'Female'])->after('age');
            $table->string('email')->after('grades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('foreign_students', function (Blueprint $table) {
            $table->dropColumn(['gender', 'email']);
        });
    }
}
