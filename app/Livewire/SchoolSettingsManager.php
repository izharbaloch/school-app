<?php

namespace App\Livewire;

use App\Models\SchoolSetting;
use Livewire\Component;

class SchoolSettingsManager extends Component
{
    public string $activeTab = 'general';

    // General
    public string $school_name    = '';
    public string $school_address = '';
    public string $school_phone   = '';
    public string $school_email   = '';
    public string $school_website = '';
    public string $school_motto   = '';
    public string $established    = '';

    // Academic
    public string $academic_year_start = '';
    public string $academic_year_end   = '';
    public string $working_days        = 'Mon,Tue,Wed,Thu,Fri';
    public string $school_timing_start = '08:00';
    public string $school_timing_end   = '14:00';

    // Fee
    public string $currency_symbol   = 'Rs.';
    public string $fee_due_day       = '10';
    public string $late_fee_per_day  = '50';
    public string $fee_receipt_prefix= 'RCT';

    // Attendance
    public string $min_attendance_pct = '75';
    public string $late_mark_after    = '15';

    // Library
    public string $max_books_per_student = '3';
    public string $fine_per_day          = '5';
    public string $loan_period_days      = '14';

    public function mount(): void
    {
        $this->loadSettings();
    }

    private function loadSettings(): void
    {
        $settings = SchoolSetting::pluck('value', 'key')->toArray();

        foreach ([
            'school_name', 'school_address', 'school_phone', 'school_email',
            'school_website', 'school_motto', 'established',
            'academic_year_start', 'academic_year_end', 'working_days',
            'school_timing_start', 'school_timing_end',
            'currency_symbol', 'fee_due_day', 'late_fee_per_day', 'fee_receipt_prefix',
            'min_attendance_pct', 'late_mark_after',
            'max_books_per_student', 'fine_per_day', 'loan_period_days',
        ] as $key) {
            if (isset($settings[$key]) && property_exists($this, $key)) {
                $this->$key = $settings[$key];
            }
        }
    }

    public function saveGeneral(): void
    {
        $this->validate([
            'school_name' => ['required', 'string', 'max:255'],
        ]);

        SchoolSetting::set('school_name',    $this->school_name,    'general');
        SchoolSetting::set('school_address', $this->school_address, 'general');
        SchoolSetting::set('school_phone',   $this->school_phone,   'general');
        SchoolSetting::set('school_email',   $this->school_email,   'general');
        SchoolSetting::set('school_website', $this->school_website, 'general');
        SchoolSetting::set('school_motto',   $this->school_motto,   'general');
        SchoolSetting::set('established',    $this->established,    'general');

        session()->flash('success', 'General settings saved.');
    }

    public function saveAcademic(): void
    {
        SchoolSetting::set('academic_year_start', $this->academic_year_start, 'academic');
        SchoolSetting::set('academic_year_end',   $this->academic_year_end,   'academic');
        SchoolSetting::set('working_days',        $this->working_days,        'academic');
        SchoolSetting::set('school_timing_start', $this->school_timing_start, 'academic');
        SchoolSetting::set('school_timing_end',   $this->school_timing_end,   'academic');

        session()->flash('success', 'Academic settings saved.');
    }

    public function saveFee(): void
    {
        $this->validate([
            'fee_due_day'      => ['required', 'integer', 'between:1,31'],
            'late_fee_per_day' => ['required', 'numeric', 'min:0'],
        ]);

        SchoolSetting::set('currency_symbol',    $this->currency_symbol,    'fee');
        SchoolSetting::set('fee_due_day',        $this->fee_due_day,        'fee');
        SchoolSetting::set('late_fee_per_day',   $this->late_fee_per_day,   'fee');
        SchoolSetting::set('fee_receipt_prefix', $this->fee_receipt_prefix, 'fee');

        session()->flash('success', 'Fee settings saved.');
    }

    public function saveAttendance(): void
    {
        SchoolSetting::set('min_attendance_pct', $this->min_attendance_pct, 'attendance');
        SchoolSetting::set('late_mark_after',    $this->late_mark_after,    'attendance');

        session()->flash('success', 'Attendance settings saved.');
    }

    public function saveLibrary(): void
    {
        SchoolSetting::set('max_books_per_student', $this->max_books_per_student, 'library');
        SchoolSetting::set('fine_per_day',          $this->fine_per_day,          'library');
        SchoolSetting::set('loan_period_days',      $this->loan_period_days,      'library');

        session()->flash('success', 'Library settings saved.');
    }

    public function render()
    {
        return view('livewire.school-settings-manager');
    }
}
