<div>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                    <input type="text" class="form-control" placeholder="Search by name, roll no..."
                        wire:model.live="search">
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <button type="button" wire:click="resetFilters" class="btn btn-secondary">
                Reset
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Photo</th>
                    <th>Roll No</th>
                    <th>Name</th>
                    <th>Father Name</th>
                    <th>Class</th>
                    <th>Section</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $student)
                    <tr>
                        <td>{{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}</td>
                        <td>
                            @if ($student->profilePhoto)
                                <img src="{{ asset('storage/' . $student->profilePhoto->file_path) }}"
                                    alt="Student Photo" width="45" height="45" class="rounded border">
                            @else
                                <span class="text-muted">No Photo</span>
                            @endif
                        </td>
                        <td>{{ $student->roll_no ?? '-' }}</td>
                        <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                        <td>{{ $student->father_name }}</td>
                        <td>{{ $student->studentClass->name ?? '-' }}</td>
                        <td>{{ $student->section->name ?? '-' }}</td>
                        <td>
                            @if ($student->status == 'active')
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-info"
                                    title="View">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-primary"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('students.destroy', $student->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Delete this student?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No students found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $students->links() }}
    </div>
</div>
