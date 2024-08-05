<?php

use App\Enums\TaskStagesEnums;
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id');
            $table->string('name');
            $table->string('description');
            $table->dateTime('due_date')->nullable();
            $table->string('priority')->default('normal');
            $table->enum('stage', [
                    TaskStagesEnums::NOT_STARTED->value,
                    TaskStagesEnums::IN_PROGRESS->value,
                    TaskStagesEnums::COMPLETED->value,
                ]
            )->default(TaskStagesEnums::NOT_STARTED->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
