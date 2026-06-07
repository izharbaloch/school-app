<div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Tab Navigation -->
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link {{ $activeTab === 'books' ? 'active' : '' }}" wire:click.prevent="$set('activeTab','books')" href="#">
                <i class="fas fa-book mr-1"></i> Books
                <span class="badge badge-primary">{{ $books->total() }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $activeTab === 'categories' ? 'active' : '' }}" wire:click.prevent="$set('activeTab','categories')" href="#">
                <i class="fas fa-tags mr-1"></i> Categories
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $activeTab === 'issues' ? 'active' : '' }}" wire:click.prevent="$set('activeTab','issues')" href="#">
                <i class="fas fa-hand-holding-heart mr-1"></i> Issues & Returns
                <span class="badge badge-warning">{{ $issues->total() }}</span>
            </a>
        </li>
    </ul>

    <!-- Books Tab -->
    @if($activeTab === 'books')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h4>Books Catalog</h4>
            <div class="d-flex" style="gap:8px">
                <input type="text" class="form-control form-control-sm" wire:model.live.debounce.400ms="search" placeholder="Search books...">
                @can('library.create')
                <button class="btn btn-primary btn-sm" wire:click="openBookForm"><i class="fas fa-plus"></i> Add Book</button>
                @endcan
            </div>
        </div>

        @if($showBookForm)
        <div class="card-body border-bottom bg-light">
            <h6>{{ $bookId ? 'Edit' : 'Add' }} Book</h6>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" wire:model="title">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Author</label>
                        <input type="text" class="form-control" wire:model="author">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>ISBN</label>
                        <input type="text" class="form-control" wire:model="isbn">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Category</label>
                        <select class="form-control" wire:model="category_id">
                            <option value="">No Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Total Copies</label>
                        <input type="number" class="form-control" wire:model="total_copies" min="1">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Price (Rs.)</label>
                        <input type="number" class="form-control" wire:model="price" step="0.01">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Shelf Location</label>
                        <input type="text" class="form-control" wire:model="shelf_location">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Publisher</label>
                        <input type="text" class="form-control" wire:model="publisher">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Year</label>
                        <input type="number" class="form-control" wire:model="publish_year" min="1900">
                    </div>
                </div>
            </div>
            <button class="btn btn-success" wire:click="saveBook">{{ $bookId ? 'Update' : 'Save' }}</button>
            <button class="btn btn-secondary ml-2" wire:click="$set('showBookForm', false)">Cancel</button>
        </div>
        @endif

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>ISBN</th>
                            <th>Total</th>
                            <th>Available</th>
                            <th>Shelf</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($books as $book)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $book->title }}</strong></td>
                            <td>{{ $book->author ?? '-' }}</td>
                            <td>{{ $book->category->name ?? '-' }}</td>
                            <td>{{ $book->isbn ?? '-' }}</td>
                            <td>{{ $book->total_copies }}</td>
                            <td>
                                <span class="badge badge-{{ $book->available_copies > 0 ? 'success' : 'danger' }}">
                                    {{ $book->available_copies }}
                                </span>
                            </td>
                            <td>{{ $book->shelf_location ?? '-' }}</td>
                            <td>
                                @can('library.edit')
                                <button class="btn btn-xs btn-info" wire:click="editBook({{ $book->id }})"><i class="fas fa-edit"></i></button>
                                @endcan
                                @can('library.delete')
                                <button class="btn btn-xs btn-danger ml-1" wire:click="deleteBook({{ $book->id }})" onclick="return confirm('Delete book?')"><i class="fas fa-trash"></i></button>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">No books found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($books->hasPages()) <div class="card-footer">{{ $books->links() }}</div> @endif
    </div>
    @endif

    <!-- Categories Tab -->
    @if($activeTab === 'categories')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Book Categories</h4>
            @can('library.create')
            <button class="btn btn-primary btn-sm" wire:click="openCategoryForm"><i class="fas fa-plus"></i> Add Category</button>
            @endcan
        </div>
        @if($showCategoryForm)
        <div class="card-body border-bottom bg-light">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control" wire:model="cat_name">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" class="form-control" wire:model="cat_description">
                    </div>
                </div>
            </div>
            <button class="btn btn-success" wire:click="saveCategory">{{ $categoryId ? 'Update' : 'Save' }}</button>
            <button class="btn btn-secondary ml-2" wire:click="$set('showCategoryForm', false)">Cancel</button>
        </div>
        @endif
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead><tr><th>#</th><th>Name</th><th>Description</th><th>Books</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $cat->name }}</td>
                        <td>{{ $cat->description ?? '-' }}</td>
                        <td>{{ $cat->books_count ?? $cat->books->count() }}</td>
                        <td>
                            @can('library.edit')
                            <button class="btn btn-xs btn-info" wire:click="editCategory({{ $cat->id }})"><i class="fas fa-edit"></i></button>
                            @endcan
                            @can('library.delete')
                            <button class="btn btn-xs btn-danger ml-1" wire:click="deleteCategory({{ $cat->id }})" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></button>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No categories found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Issues Tab -->
    @if($activeTab === 'issues')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h4>Book Issues & Returns</h4>
            <div class="d-flex" style="gap:8px">
                <input type="text" class="form-control form-control-sm" wire:model.live.debounce.400ms="search" placeholder="Search...">
                @can('library.issue_books')
                <button class="btn btn-primary btn-sm" wire:click="openIssueForm"><i class="fas fa-plus"></i> Issue Book</button>
                @endcan
            </div>
        </div>

        @if($showIssueForm)
        <div class="card-body border-bottom bg-light">
            <h6>Issue Book</h6>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Book <span class="text-danger">*</span></label>
                        <select class="form-control @error('issue_book_id') is-invalid @enderror" wire:model="issue_book_id">
                            <option value="">Select Book</option>
                            @foreach($books as $book)
                                <option value="{{ $book->id }}" {{ !$book->isAvailable() ? 'disabled' : '' }}>
                                    {{ $book->title }} ({{ $book->available_copies }} available)
                                </option>
                            @endforeach
                        </select>
                        @error('issue_book_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Issue To</label>
                        <select class="form-control" wire:model="issue_type">
                            <option value="student">Student</option>
                            <option value="teacher">Teacher</option>
                        </select>
                    </div>
                </div>
                @if($issue_type === 'student')
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Student</label>
                        <select class="form-control" wire:model="issue_student_id">
                            <option value="">Select Student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }} ({{ $student->admission_no }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @else
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Teacher</label>
                        <select class="form-control" wire:model="issue_teacher_id">
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Issue Date</label>
                        <input type="date" class="form-control" wire:model="issue_date">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Due Date</label>
                        <input type="date" class="form-control" wire:model="due_date">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Remarks</label>
                        <input type="text" class="form-control" wire:model="issue_remarks">
                    </div>
                </div>
            </div>
            <button class="btn btn-success" wire:click="saveIssue">Issue Book</button>
            <button class="btn btn-secondary ml-2" wire:click="$set('showIssueForm', false)">Cancel</button>
        </div>
        @endif

        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr><th>#</th><th>Book</th><th>Issued To</th><th>Issue Date</th><th>Due Date</th><th>Return Date</th><th>Fine</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($issues as $issue)
                    <tr class="{{ $issue->isOverdue() ? 'table-danger' : '' }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $issue->book->title ?? '-' }}</td>
                        <td>
                            @if($issue->student)
                                <i class="fas fa-user-graduate text-primary mr-1"></i>{{ $issue->student->first_name }} {{ $issue->student->last_name }}
                            @elseif($issue->teacher)
                                <i class="fas fa-chalkboard-teacher text-success mr-1"></i>{{ $issue->teacher->name }}
                            @endif
                        </td>
                        <td>{{ $issue->issue_date->format('d M Y') }}</td>
                        <td>{{ $issue->due_date->format('d M Y') }}</td>
                        <td>{{ $issue->return_date ? $issue->return_date->format('d M Y') : '-' }}</td>
                        <td>{{ $issue->fine_amount > 0 ? 'Rs. ' . $issue->fine_amount : '-' }}</td>
                        <td>
                            <span class="badge badge-{{ $issue->status === 'returned' ? 'success' : ($issue->isOverdue() ? 'danger' : 'warning') }}">
                                {{ ucfirst($issue->status) }}{{ $issue->isOverdue() ? ' (Overdue)' : '' }}
                            </span>
                        </td>
                        <td>
                            @if($issue->status === 'issued')
                            @can('library.issue_books')
                            <button class="btn btn-xs btn-success" wire:click="returnBook({{ $issue->id }})" onclick="return confirm('Mark as returned?')">
                                <i class="fas fa-undo"></i> Return
                            </button>
                            @endcan
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">No book issues found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($issues->hasPages()) <div class="card-footer">{{ $issues->links() }}</div> @endif
    </div>
    @endif
</div>
