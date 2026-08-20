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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();

        $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
        $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
        $table->foreignId('assigned_to')->constrained('users')->cascadeOnDelete();
            $table->text('request');
            $table->date('request_start_date');

        $table->date('request_deadline_date')->nullable();
            $table->enum('priority', [
                'low',
                'medium',
                'high',
                'urgent'
            ]);

            $table->enum('status', [
                'pending',
                'in_progress',
                'testing',
                'completed',
                'canceled',
            ]);
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
