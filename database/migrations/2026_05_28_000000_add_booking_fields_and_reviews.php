<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tour_packages', function (Blueprint $table) {
            if (!Schema::hasColumn('tour_packages', 'image_url')) $table->string('image_url')->nullable()->after('slots');
            if (!Schema::hasColumn('tour_packages', 'duration')) $table->string('duration')->nullable()->after('location');
            if (!Schema::hasColumn('tour_packages', 'category')) $table->string('category')->default('Tour')->after('title');
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'total_amount')) $table->decimal('total_amount', 10, 2)->default(0)->after('people_count');
            if (!Schema::hasColumn('bookings', 'notes')) $table->text('notes')->nullable()->after('status');
        });

        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'payment_method')) $table->string('payment_method')->default('Cash')->after('amount');
            if (!Schema::hasColumn('payments', 'reference_number')) $table->string('reference_number')->nullable()->after('payment_method');
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('tour_package_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
