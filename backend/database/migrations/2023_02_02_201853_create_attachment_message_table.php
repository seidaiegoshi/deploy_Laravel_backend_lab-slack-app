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
        Schema::create('attachment_message', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('message_id')
                ->unique()
                ->constrained('messages')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table
                ->foreignId('attachment_id')
                ->constrained('attachments')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attachment_message');
    }
};
