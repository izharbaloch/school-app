<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Bus 1, Van A
            $table->string('registration_no')->unique();
            $table->string('type')->default('bus'); // bus, van, car
            $table->integer('capacity')->default(40);
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->string('driver_cnic')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('transport_routes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->nullOnDelete();
            $table->string('start_point')->nullable();
            $table->string('end_point')->nullable();
            $table->decimal('monthly_fee', 10, 2)->default(0);
            $table->text('stops')->nullable(); // JSON list of stop names
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('student_transports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('route_id')->constrained('transport_routes')->cascadeOnDelete();
            $table->string('pickup_point')->nullable();
            $table->string('drop_point')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->unique(['student_id', 'route_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_transports');
        Schema::dropIfExists('transport_routes');
        Schema::dropIfExists('vehicles');
    }
};
