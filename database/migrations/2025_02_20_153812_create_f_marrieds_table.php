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
        Schema::create('f_marrieds', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            //Infos
            $table->string('full_name'); // الاسم الكامل (Full Name)
            $table->string('city'); // المدينة (City)
            $table->enum('marital_status', ['عازبة', 'أرملة', 'مطلقة'])->nullable(); // الحالة الاجتماعية (Marital Status)
            $table->integer('age'); // العمر (Age)
            $table->string('phone'); // الهاتف (Phone)
            $table->enum('activity_status', ['تدرس', 'قارة', 'تعمل'])->nullable(); // هل انت تدرسين أم قارة في البيت أم تعملين
            $table->date('application_date'); // تاريخ التقديم (Application Date)
            $table->string('document_status')->nullable(); // حالة الملف (Document Status)

            //Description
            $table->boolean('prays_on_time')->default(false); // هل تصلين الفريضة في وقتها
            $table->decimal('height', 5, 2)->nullable(); // الطول (Height)
            $table->boolean('wears_niqab')->default(false); // منتقبة او مختمرة
            $table->decimal('weight', 5, 2)->nullable(); // الوزن (Weight)
            $table->string('skin_color')->nullable(); // لون البشرة (Skin Color)
            $table->string('education_level')->nullable(); // المستوى الدراسي (Education Level)
            $table->string('creed')->nullable(); // عقيدتك (Religion)
            $table->string('scholars_you_follow')->nullable(); // شيوخك ممن تأخد الفتوى (Scholars You Follow for Fatwa)
            $table->string('medical_illness')->nullable(); // تعاني من مرض معين (Medical illness)
            $table->boolean('parents_opposition')->default(false); // هل والداك يعارضان تزويجك

            //Conditions
            $table->boolean('willing_to_stay_home')->default(false); // هل انت على استعداد للقرار في البيت
            $table->boolean('accepts_divorced_or_widowed')->default(false); // هل تقبلين مطلقا أو أرملا
            $table->boolean('accepts_living_with_inlaws')->default(false); // هل تقبلين بالسكن مع والدي الزوج
            $table->integer('maximum_accepted_age')->nullable(); // أقصى العمر الذي تقبل به (Maximum Accepted Age)
            $table->text('partner_requirements')->nullable(); // شروطك في الطرف الآخر (Partner Requirements)
            $table->text('message_to_partner')->nullable(); // شيء ما تود إخباره للطرف الآخر (Message to Partner)
            $table->text('remarks')->nullable(); // ملاحظات (Remarks)

            //Foreign Keys
            $table->foreignId('m_married_id')->constrained('m_marrieds')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps(); // Adds `created_at` and `updated_at` columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('f_marrieds');
    }
};
