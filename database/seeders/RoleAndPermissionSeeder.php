<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | All Permissions
        |--------------------------------------------------------------------------
        */
        $permissions = [
            // dashboard
            'dashboard.view',

            // role & permission management
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'permissions.view',
            'permissions.assign',
            'permissions.manage', // create/edit/delete permission definitions themselves

            // user management
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            // reports
            'reports.view',

            // hostel
            'hostel.view',
            'hostel.manage',

            // sports & activities
            'sports.view',
            'sports.manage',

            // medical records
            'medical.view',
            'medical.edit',

            // conduct / discipline
            'conduct.view',
            'conduct.create',
            'conduct.edit',
            'conduct.delete',

            // leaves
            'leaves.view',
            'leaves.apply',
            'leaves.approve',
            'leaves.manage',  // leave type CRUD

            // admissions
            'admissions.view',
            'admissions.create',
            'admissions.edit',
            'admissions.delete',
            'admissions.process',   // accept / reject / enroll

            // students
            'students.view',
            'students.create',
            'students.edit',
            'students.delete',

            // teachers
            'teachers.view',
            'teachers.create',
            'teachers.edit',
            'teachers.delete',

            // parents
            'parents.view',
            'parents.create',
            'parents.edit',
            'parents.delete',

            // classes
            'classes.view',
            'classes.create',
            'classes.edit',
            'classes.delete',

            // sections
            'sections.view',
            'sections.create',
            'sections.edit',
            'sections.delete',

            // subjects
            'subjects.view',
            'subjects.create',
            'subjects.edit',
            'subjects.delete',

            // timetable
            'timetable.view',
            'timetable.create',
            'timetable.edit',
            'timetable.delete',

            // attendance
            'attendance.view',
            'attendance.mark',
            'attendance.edit',

            // exams
            'exams.view',
            'exams.create',
            'exams.edit',
            'exams.delete',
            'marks.view',
            'marks.create',
            'marks.edit',

            // fees
            'fees.view',
            'fees.create',
            'fees.edit',
            'fees.delete',
            'fees.collect',


            // library
            'library.view',
            'library.create',
            'library.edit',
            'library.delete',
            'library.issue_books',

            // notices
            'notices.view',
            'notices.create',
            'notices.edit',
            'notices.delete',

            // settings
            'settings.view',
            'settings.update',

            // activity logs
            'activity-logs.view',

            // profile
            'profile.view',
            'profile.update',

            // transport
            'transport.view',
            'transport.create',
            'transport.edit',
            'transport.delete',

            // events
            'events.view',
            'events.create',
            'events.edit',
            'events.delete',

            // certificates
            'certificates.view',
            'certificates.print',

            // homework
            'homework.view',
            'homework.create',
            'homework.edit',
            'homework.delete',

            // accounting
            'accounting.view',
            'accounting.create',
            'accounting.edit',
            'accounting.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */
        $roles = [
            'super admin',
            'admin',
            'principal',
            'teacher',
            'student',
            'parent',
            'accountant',
            'librarian',
            'receptionist',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        /*
        |--------------------------------------------------------------------------
        | Assign Permissions To Roles
        |--------------------------------------------------------------------------
        */

        // super admin => all permissions
        Role::findByName('super admin')->syncPermissions(Permission::all());

        // admin
        Role::findByName('admin')->syncPermissions([
            'dashboard.view',

            'reports.view',

            'hostel.view',
            'hostel.manage',

            'sports.view',
            'sports.manage',

            'medical.view',
            'medical.edit',

            'conduct.view',
            'conduct.create',
            'conduct.edit',
            'conduct.delete',

            'leaves.view',
            'leaves.apply',
            'leaves.approve',
            'leaves.manage',

            'admissions.view',
            'admissions.create',
            'admissions.edit',
            'admissions.delete',
            'admissions.process',

            'students.view',
            'students.create',
            'students.edit',
            'students.delete',

            'teachers.view',
            'teachers.create',
            'teachers.edit',
            'teachers.delete',

            'parents.view',
            'parents.create',
            'parents.edit',
            'parents.delete',

            'classes.view',
            'classes.create',
            'classes.edit',
            'classes.delete',

            'sections.view',
            'sections.create',
            'sections.edit',
            'sections.delete',

            'subjects.view',
            'subjects.create',
            'subjects.edit',
            'subjects.delete',

            'timetable.view',
            'timetable.create',
            'timetable.edit',
            'timetable.delete',

            'attendance.view',
            'attendance.mark',
            'attendance.edit',

            'exams.view',
            'exams.create',
            'exams.edit',
            'exams.delete',

            'marks.view',
            'marks.create',
            'marks.edit',

            'fees.view',
            'fees.create',
            'fees.edit',
            'fees.delete',
            'fees.collect',

            'library.view',
            'library.create',
            'library.edit',
            'library.delete',
            'library.issue_books',

            'notices.view',
            'notices.create',
            'notices.edit',
            'notices.delete',

            'settings.view',
            'settings.update',

            'activity-logs.view',

            'roles.view',
            'roles.create',
            'roles.edit',
            'permissions.view',
            'permissions.assign',

            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            'profile.view',
            'profile.update',

            'homework.view',
            'homework.create',
            'homework.edit',
            'homework.delete',

            'accounting.view',
            'accounting.create',
            'accounting.edit',
            'accounting.delete',

            'transport.view',
            'transport.create',
            'transport.edit',
            'transport.delete',

            'events.view',
            'events.create',
            'events.edit',
            'events.delete',

            'certificates.view',
            'certificates.print',
        ]);

        // principal
        Role::findByName('principal')->syncPermissions([
            'dashboard.view',

            'reports.view',

            'hostel.view',
            'hostel.manage',

            'sports.view',
            'sports.manage',

            'medical.view',
            'medical.edit',

            'conduct.view',
            'conduct.create',
            'conduct.edit',
            'conduct.delete',

            'leaves.view',
            'leaves.approve',

            'admissions.view',
            'admissions.process',

            'students.view',
            'teachers.view',
            'parents.view',

            'classes.view',
            'sections.view',
            'subjects.view',
            'timetable.view',

            'attendance.view',

            'exams.view',
            'marks.view',

            'fees.view',

            'library.view',

            'notices.view',
            'notices.create',
            'notices.edit',

            'settings.view',

            'activity-logs.view',

            'homework.view',
            'homework.create',
            'homework.edit',

            'accounting.view',

            'transport.view',
            'events.view',
            'certificates.view',
            'certificates.print',

            'profile.view',
            'profile.update',
        ]);

        // teacher
        Role::findByName('teacher')->syncPermissions([
            'dashboard.view',

            'reports.view',

            'hostel.view',

            'sports.view',

            'medical.view',
            'medical.edit',

            'conduct.view',
            'conduct.create',

            'leaves.view',
            'leaves.apply',

            'students.view',
            'classes.view',
            'sections.view',
            'subjects.view',
            'timetable.view',

            'attendance.view',
            'attendance.mark',
            'attendance.edit',

            'exams.view',
            'marks.view',
            'marks.create',
            'marks.edit',

            'notices.view',

            'homework.view',
            'homework.create',
            'homework.edit',

            'events.view',

            'profile.view',
            'profile.update',
        ]);

        // student
        Role::findByName('student')->syncPermissions([
            'dashboard.view',

            'hostel.view',

            'sports.view',

            'medical.view',

            'conduct.view',

            'leaves.view',
            'leaves.apply',

            'students.view',
            'subjects.view',
            'timetable.view',
            'attendance.view',
            'exams.view',
            'marks.view',
            'fees.view',
            'library.view',
            'notices.view',

            'profile.view',
            'profile.update',
        ]);

        // parent
        Role::findByName('parent')->syncPermissions([
            'dashboard.view',

            'medical.view',

            'conduct.view',

            'students.view',
            'attendance.view',
            'marks.view',
            'fees.view',
            'notices.view',

            'profile.view',
            'profile.update',
        ]);

        // accountant
        Role::findByName('accountant')->syncPermissions([
            'dashboard.view',

            'reports.view',

            'students.view',
            'parents.view',

            'fees.view',
            'fees.create',
            'fees.edit',
            'fees.delete',
            'fees.collect',

            'accounting.view',
            'accounting.create',
            'accounting.edit',
            'accounting.delete',

            'notices.view',

            'profile.view',
            'profile.update',
        ]);

        // librarian
        Role::findByName('librarian')->syncPermissions([
            'dashboard.view',

            'students.view',
            'teachers.view',

            'library.view',
            'library.create',
            'library.edit',
            'library.delete',
            'library.issue_books',

            'profile.view',
            'profile.update',
        ]);

        // receptionist
        Role::findByName('receptionist')->syncPermissions([
            'dashboard.view',

            'admissions.view',
            'admissions.create',
            'admissions.edit',

            'students.view',
            'students.create',
            'students.edit',

            'parents.view',
            'parents.create',
            'parents.edit',

            'teachers.view',

            'classes.view',
            'sections.view',

            'fees.view',

            'notices.view',

            'profile.view',
            'profile.update',
        ]);

        // create super admin user — credentials are read from .env (SUPER_ADMIN_EMAIL / PASSWORD)
        $superAdmin = \App\Models\User::firstOrCreate(
            ['email' => env('SUPER_ADMIN_EMAIL', 'admin@school.com')],
            [
                'name' => env('SUPER_ADMIN_NAME', 'Super Admin'),
                'password' => bcrypt(env('SUPER_ADMIN_PASSWORD', 'changeme123!')),
            ]
        );

        $superAdmin->assignRole('super admin');
    }
}
