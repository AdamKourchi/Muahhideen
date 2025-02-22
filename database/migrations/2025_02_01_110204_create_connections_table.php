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
        Schema::create('connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('m_applicant_id')->constrained('m_applicants')->onDelete('cascade');
            $table->foreignId('f_applicant_id')->constrained('f_applicants')->onDelete('cascade');
            $table->date('connection_date')->nullable();
            $table->enum('status', ['توافق', 'عدم توافق', 'انتظار الإجابة','انتظار الإرسال'])->default('انتظار الإرسال');
            $table->text('remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connections');
    }
};
