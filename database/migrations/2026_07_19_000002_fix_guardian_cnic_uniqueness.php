<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // The unique constraint belongs on guardians.guardian_cnic_no (the source of truth),
        // not on students.guardian_cnic_no (a denormalized copy) — as written it blocked
        // enrolling a second sibling, since siblings share the same guardian CNIC.
        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique(['guardian_cnic_no']);
        });

        Schema::table('guardians', function (Blueprint $table) {
            $table->unique('guardian_cnic_no');
        });
    }

    public function down(): void
    {
        Schema::table('guardians', function (Blueprint $table) {
            $table->dropUnique(['guardian_cnic_no']);
        });

        Schema::table('students', function (Blueprint $table) {
            $table->unique('guardian_cnic_no');
        });
    }
};
