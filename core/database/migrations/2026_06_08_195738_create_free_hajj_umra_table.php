<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. php artisan migrate --path=database/migrations/2026_06_08_195738_create_free_hajj_umra_table.php
     */
    public function up(): void
    {
        Schema::create('free_hajj_umra', function (Blueprint $table) {
            $table->id();
            // ১. ব্যক্তিগত তথ্য (Personal Information)
            $table->string('full_name', 150)->comment('পূর্ণ নাম');
            $table->string('mobile_number', 20)->comment('মোবাইল নম্বর');
            $table->string('whatsapp_number', 20)->nullable()->comment('WhatsApp নম্বর');
            $table->string('email', 255)->nullable()->comment('ইমেইল (ঐচ্ছিক)');
            $table->text('address')->comment('সম্পূর্ণ ঠিকানা');

            // ২. আবেদনকারীর তথ্য (Applicant Details)
            $table->unsignedSmallInteger('age')->comment('বয়স');
            $table->enum('gender', ['পুরুষ', 'মহিলা'])->comment('লিঙ্গ');
            $table->enum('marital_status', ['অবিবাহিত', 'বিবাহিত'])->comment('বৈবাহিক অবস্থা');
            $table->boolean('has_done_umrah_or_haj_before')->default(false)->comment('পূর্বে উমরাহ বা হজ করেছেন কি?');

            // ৩. পাসপোর্ট তথ্য (Passport Information)
            $table->boolean('has_valid_passport')->default(false)->comment('বৈধ পাসপোর্ট আছে কি?');
            $table->boolean('passport_validity_6_months')->nullable()->comment('পাসপোর্টের মেয়াদ কমপক্ষে ৬ মাস আছে কি?');
            $table->string('passport_number', 50)->nullable()->comment('পাসপোর্ট নম্বর');
            $table->date('passport_expiry_date')->nullable()->comment('পাসপোর্টের মেয়াদ শেষের তারিখ');

            // ৪. নির্বাচন সংক্রান্ত তথ্য (Selection / Preference Info)
            $table->enum('pilgrimage_type', ['উমরাহ', 'হজ'])->comment('উমরাহ / হজ');
            $table->text('preferred_package')->nullable()->comment('পছন্দের প্যাকেজ বিবরণ');
            $table->boolean('can_self_finance')->default(false)->comment('নিজ খরচে যেতে সক্ষম কি?');

            // ৫. সম্মতি (Consent)
            $table->boolean('terms_accepted')->default(false)->comment('শর্ত ও নীতি সম্মত');
            $table->boolean('selection_decision_accepted')->default(false)->comment('নির্বাচন প্রক্রিয়ার সিদ্ধান্ত মেনে নেওয়া');

            // Application Status & Meta
            $table->enum('status', ['pending', 'reviewing', 'approved', 'rejected', 'completed'])
                ->default('pending')
                ->comment('আবেদনের বর্তমান অবস্থা');
            $table->string('application_reference', 20)->unique()->nullable()->comment('রেফারেন্স কোড: UHJ-YYYY-NNNNNN');
            $table->text('notes')->nullable()->comment('অ্যাডমিন নোট');
            $table->timestamp('reviewed_at')->nullable()->comment('পর্যালোচনার তারিখ');
            $table->foreignUuid('reviewed_by')->nullable()->constrained('users')->nullOnDelete()->comment('পর্যালোচনাকারী অ্যাডমিন');
            $table->softDeletes(); // deleted_at

            // Indexes
            $table->index('mobile_number');
            $table->index('status');
            $table->index('pilgrimage_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('free_hajj_umra');
    }
};
