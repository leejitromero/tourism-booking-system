<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tour_packages', function (Blueprint $table) {
            if (!Schema::hasColumn('tour_packages', 'distance')) $table->string('distance')->nullable()->after('location');
            if (!Schema::hasColumn('tour_packages', 'beach_info')) $table->string('beach_info')->nullable()->after('distance');
            if (!Schema::hasColumn('tour_packages', 'stars')) $table->unsignedTinyInteger('stars')->default(0)->after('category');
            if (!Schema::hasColumn('tour_packages', 'review_score')) $table->decimal('review_score', 3, 1)->nullable()->after('image_url');
            if (!Schema::hasColumn('tour_packages', 'review_count')) $table->unsignedInteger('review_count')->default(0)->after('review_score');
            if (!Schema::hasColumn('tour_packages', 'amenities')) $table->text('amenities')->nullable()->after('review_count');
        });

        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'check_in_date')) $table->date('check_in_date')->nullable()->after('booking_date');
            if (!Schema::hasColumn('bookings', 'check_out_date')) $table->date('check_out_date')->nullable()->after('check_in_date');
            if (!Schema::hasColumn('bookings', 'nights')) $table->unsignedInteger('nights')->default(1)->after('check_out_date');
        });
    }

    public function down(): void
    {
        Schema::table('tour_packages', function (Blueprint $table) {
            foreach (['distance','beach_info','stars','review_score','review_count','amenities'] as $column) {
                if (Schema::hasColumn('tour_packages', $column)) $table->dropColumn($column);
            }
        });
        Schema::table('bookings', function (Blueprint $table) {
            foreach (['check_in_date','check_out_date','nights'] as $column) {
                if (Schema::hasColumn('bookings', $column)) $table->dropColumn($column);
            }
        });
    }
};
