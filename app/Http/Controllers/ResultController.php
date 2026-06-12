<?php

namespace App\Http\Controllers;

use App\Models\AttendanceStudent;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\SchoolSetting;
use App\Models\Student;

class ResultController extends Controller
{
    public function index()
    {
        return view('results.index');
    }

    public function show(Exam $exam, Student $student)
    {
        return view('results.show', $this->buildResultData($exam, $student));
    }

    public function print(Exam $exam, Student $student)
    {
        return view('results.print', $this->buildResultData($exam, $student));
    }

    public function reportCard(Exam $exam, Student $student)
    {
        $data = $this->buildResultData($exam, $student);

        // Rank: count students in same exam+class who scored strictly higher and passed
        $classResults = ExamResult::where('exam_id', $exam->id)
            ->where('student_class_id', $student->student_class_id)
            ->selectRaw('student_id, SUM(obtained_marks) as total_obtained, SUM(total_marks) as total_marks_sum')
            ->groupBy('student_id')
            ->get();

        $studentPercentages = $classResults->map(function ($row) {
            return [
                'student_id' => $row->student_id,
                'percentage' => $row->total_marks_sum > 0
                    ? ($row->total_obtained / $row->total_marks_sum) * 100
                    : 0,
            ];
        })->sortByDesc('percentage')->values();

        $rank = $studentPercentages->search(fn($r) => $r['student_id'] === $student->id);
        $rank = $rank !== false ? $rank + 1 : null;
        $totalInClass = $studentPercentages->count();

        // Attendance summary for the exam's academic year
        $attendanceSummary = AttendanceStudent::where('student_id', $student->id)
            ->whereHas('attendance', fn($q) => $q->whereYear('date', $exam->academic_year))
            ->selectRaw("
                COUNT(*) as total_days,
                SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present,
                SUM(CASE WHEN status = 'absent'  THEN 1 ELSE 0 END) as absent,
                SUM(CASE WHEN status = 'leave'   THEN 1 ELSE 0 END) as leave,
                SUM(CASE WHEN status = 'late'    THEN 1 ELSE 0 END) as late
            ")
            ->first();

        $school = [
            'name'    => SchoolSetting::get('school_name', config('app.name')),
            'address' => SchoolSetting::get('school_address', ''),
            'phone'   => SchoolSetting::get('school_phone', ''),
            'logo'    => SchoolSetting::get('school_logo', ''),
        ];

        return view('results.report-card', $data + compact('rank', 'totalInClass', 'attendanceSummary', 'school'));
    }

    private function buildResultData(Exam $exam, Student $student): array
    {
        Student::allowedForUser(auth()->user())->where('id', $student->id)->firstOrFail(); // @phpstan-ignore-line

        $student->load('section:id,name');

        $results = ExamResult::with(['subject:id,name', 'studentClass:id,name'])
            ->where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->select('id', 'exam_id', 'student_id', 'subject_id', 'student_class_id', 'obtained_marks', 'total_marks', 'passing_marks')
            ->get();

        $studentClass = $results->first()?->studentClass;
        $totalObtained = $results->sum('obtained_marks');
        $totalMarks = $results->sum('total_marks');
        $percentage = $totalMarks > 0 ? ($totalObtained / $totalMarks) * 100 : 0;
        $failedSubjects = $results->filter(fn($item) => $item->obtained_marks < $item->passing_marks)->count();

        return compact(
            'exam',
            'student',
            'studentClass',
            'results',
            'totalObtained',
            'totalMarks',
            'percentage',
        ) + [
            'grade'  => $this->getGrade($percentage),
            'status' => $failedSubjects > 0 ? 'Fail' : 'Pass',
        ];
    }

    private function getGrade(float|int $percentage): string
    {
        if ($percentage >= 90) {
            return 'A+';
        } elseif ($percentage >= 80) {
            return 'A';
        } elseif ($percentage >= 70) {
            return 'B';
        } elseif ($percentage >= 60) {
            return 'C';
        } elseif ($percentage >= 50) {
            return 'D';
        }

        return 'F';
    }
}
