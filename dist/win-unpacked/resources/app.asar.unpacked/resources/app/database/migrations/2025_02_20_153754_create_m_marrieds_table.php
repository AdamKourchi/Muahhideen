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
        Schema::create('m_marrieds', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('full_name'); // الاسم الكامل (Full Name)
            $table->string('city'); // المدينة (City)
            $table->enum('marital_status', ['عازب','أرمل', 'متزوج', 'مطلق'])->nullable(); // الحالة الاجتماعية (Marital Status)
            $table->integer('age'); // العمر (Age)
            $table->string('phone'); // الهاتف (Phone)
            $table->string('job')->nullable(); // العمل (Job)
            $table->decimal('height', 5, 2)->nullable(); // الطول (Height)
            $table->decimal('weight', 5, 2)->nullable(); // الوزن (Weight)
            $table->string('skin_color')->nullable(); // لون البشرة (Skin Color)
            $table->string('education_level')->nullable(); // المستوى الدراسي (Education Level)
            $table->date('application_date'); // تاريخ التقديم (Application Date)
            $table->boolean('document_status')->default(true); // حالة الملف (Document Status)
            $table->boolean('has_beard')->default(false); // ملتحي (Has Beard)
            $table->string('creed')->nullable(); // عقيدتك (Religion)
            $table->boolean('prays_with_jamaah')->default(false); // تصلي الفريضة مع الجماعة (Prays with Jamaah)
            $table->string('scholars_you_follow')->nullable(); // شيوخك ممن تأخد الفتوى (Scholars You Follow for Fatwa)
            $table->string('medical_illness')->nullable(); // تعاني من مرض معين (Medical illness)
            $table->enum('housing_situation', ['independent', 'with_parents'])->nullable(); // ستوفر سكنا مستقلا أم مع الوالدين (Housing Situation)
            $table->boolean('accepts_working_wife')->default(false); // تقبل بها عاملة أو جامعية (Accepts Working or University Student Wife)
            $table->json('preferred_cities_for_marriage')->nullable(); // المدن التي تفضل الزواج منها (Preferred Cities for Marriage)
            $table->boolean('accepts_divorced_or_widowed')->default(false); // تقبل مطلقة أو أرملة (Accepts Divorced or Widowed)
            $table->integer('maximum_accepted_age')->nullable(); // أقصى العمر الذي تقبل به (Maximum Accepted Age)
            $table->text('partner_requirements')->nullable(); // شروطك في الطرف الآخر (Partner Requirements)
            $table->text('message_to_partner')->nullable(); // شيء ما تود إخباره للطرف الآخر (Message to Partner)
            $table->text('remarks')->nullable(); // ملاحظات (Remarks)
                        $table->string('shared_housing_details')->nullable(); // تفاصيل السكن المشترك (Shared Housing Details)

            $table->softDeletes();

                        $table->string('row_hash')->unique();

            $table->timestamps(); // Adds `created_at` and `updated_at` columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_marrieds');
    }
};
