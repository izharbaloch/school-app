<div>
    <div class="card">
        <div class="card-header">
            <h4>Filter Results</h4>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label>Exam</label>
                    <select wire:model.live="exam_id" class="form-control">
                        <option value="">Select Exam</option>
                        @foreach ($exams as $exam)
                            <option value="{{ $exam->id }}">{{ $exam->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Class</label>
                    <select wire:model.live="student_class_id" class="form-control">
                        <option value="">Select Class</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label>Section</label>
                    <select wire:model.live="section_id" class="form-control" @disabled(!$student_class_id)>
                        <option value="">All Sections</option>
                        @foreach ($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    @if (count($results))
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 shadow-sm">
                    <div class="card-icon bg-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Pass Percentage</h4>
                        </div>
                        <div class="card-body">
                            {{ $stats['pass_percentage'] ?? 0 }}%
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1 shadow-sm">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Fail Percentage</h4>
                        </div>
                        <div class="card-body">
                            {{ $stats['fail_percentage'] ?? 0 }}%
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12 col-12">
                <div class="card card-statistic-1 shadow-sm">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>1st Position</h4>
                        </div>
                        <div class="card-body" style="font-size: 14px; padding-top: 10px; line-height: 1.2;">
                            {{ $stats['first_position'] ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12 col-12">
                <div class="card card-statistic-1 shadow-sm">
                    <div class="card-icon bg-info">
                        <i class="fas fa-medal"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>2nd Position</h4>
                        </div>
                        <div class="card-body" style="font-size: 14px; padding-top: 10px; line-height: 1.2;">
                            {{ $stats['second_position'] ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-12 col-12">
                <div class="card card-statistic-1 shadow-sm">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-award"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>3rd Position</h4>
                        </div>
                        <div class="card-body" style="font-size: 14px; padding-top: 10px; line-height: 1.2;">
                            {{ $stats['third_position'] ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4>Class Result Summary</h4>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-md">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Roll No</th>
                                <th>Student Name</th>
                                <th>Total Obtained</th>
                                <th>Total Marks</th>
                                <th>Percentage</th>
                                <th>Grade</th>
                                <th>Status</th>
                                <th width="160">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $row['student']->roll_no ?? '-' }}</td>
                                    <td>{{ $row['student']->full_name ?: $row['student']->name ?? '-' }}</td>
                                    <td>{{ $row['total_obtained'] }}</td>
                                    <td>{{ $row['total_marks'] }}</td>
                                    <td>{{ $row['percentage'] }}%</td>
                                    <td>{{ $row['grade'] }}</td>
                                    <td>{{ $row['status'] }}</td>
                                    <td>
                                        <a href="{{ route('results.show', [$exam_id, $row['student']->id]) }}"
                                            class="btn btn-sm btn-info">
                                            View
                                        </a>

                                        <a href="{{ route('results.print', [$exam_id, $row['student']->id]) }}"
                                            target="_blank" class="btn btn-sm btn-secondary">
                                            Print
                                        </a>
                                        <a href="{{ route('exam-marks.create', [
                                            'exam_id' => $exam_id,
                                            'student_class_id' => $student_class_id,
                                            'section_id' => $row['student']->section_id,
                                            'student_id' => $row['student']->id,
                                        ]) }}"
                                            class="btn btn-sm btn-warning">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
