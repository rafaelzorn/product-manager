<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processed_file_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('processed_file_id');
            $table->text('exception_message')->nullable();
            $table->longText('exception_trace')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('processed_file_logs');
    }
};
