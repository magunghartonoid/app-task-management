<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->text('client_address')->nullable();
            $table->string('client_phone');
            $table->string('client_email')->nullable();
            $table->string('client_poc');
            $table->string('project_name');
            $table->text('project_description')->nullable();
            $table->string('project_link')->nullable();
            $table->date('project_start_date');
            $table->date('project_end_date')->nullable();
            $table->string('project_repo')->nullable();
            $table->string('project_developer');
            $table->string('project_developer_phone');
            $table->string('project_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
