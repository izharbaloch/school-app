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

            // accounts
            'accounts.view',
            'accounts.create',
            'accounts.edit',
            'accounts.delete',

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

            // profile
            'profile.view',
            'profile.update',
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

            'accounts.view',
            'accounts.create',
            'accounts.edit',
            'accounts.delete',

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

            'roles.view',
            'roles.create',
            'roles.edit',
            'permissions.view',
            'permissions.assign',

            'profile.view',
            'profile.update',
        ]);

        // principal
        Role::findByName('principal')->syncPermissions([
            'dashboard.view',

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
            'accounts.view',

            'library.view',

            'notices.view',
            'notices.create',
            'notices.edit',

            'settings.view',

            'profile.view',
            'profile.update',
        ]);

        // teacher
        Role::findByName('teacher')->syncPermissions([
            'dashboard.view',

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

            'profile.view',
            'profile.update',
        ]);

        // student
        Role::findByName('student')->syncPermissions([
            'dashboard.view',

            'subjects.view',
            'timetable.view',
            'attendance.view',
            'exams.view',
            'marks.view',
            'library.view',
            'notices.view',

            'profile.view',
            'profile.update',
        ]);

        // parent
        Role::findByName('parent')->syncPermissions([
            'dashboard.view',

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

            'students.view',
            'parents.view',

            'fees.view',
            'fees.create',
            'fees.edit',
            'fees.delete',
            'fees.collect',

            'accounts.view',
            'accounts.create',
            'accounts.edit',
            'accounts.delete',

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
    }
}
