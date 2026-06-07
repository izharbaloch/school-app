<?php

namespace App\Livewire\Library;

use App\Models\Book;
use App\Models\BookCategory;
use App\Models\BookIssue;
use App\Models\Student;
use App\Models\Teacher;
use Livewire\Component;
use Livewire\WithPagination;

class LibraryManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $activeTab = 'books'; // books, categories, issues

    // Book form
    public bool $showBookForm = false;
    public ?int $bookId = null;
    public string $title = '';
    public string $author = '';
    public string $isbn = '';
    public string $category_id = '';
    public string $publisher = '';
    public string $publish_year = '';
    public string $total_copies = '1';
    public string $price = '';
    public string $shelf_location = '';
    public bool $book_status = true;

    // Category form
    public bool $showCategoryForm = false;
    public ?int $categoryId = null;
    public string $cat_name = '';
    public string $cat_description = '';
    public bool $cat_status = true;

    // Issue form
    public bool $showIssueForm = false;
    public ?int $issueId = null;
    public string $issue_book_id = '';
    public string $issue_student_id = '';
    public string $issue_teacher_id = '';
    public string $issue_type = 'student'; // student or teacher
    public string $issue_date = '';
    public string $due_date = '';
    public string $issue_remarks = '';

    public string $search = '';

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatedActiveTab(): void { $this->resetPage(); $this->search = ''; }

    // ─── Book CRUD ────────────────────────────────────────────────
    protected function bookRules(): array
    {
        return [
            'title'         => ['required', 'string', 'max:255'],
            'author'        => ['nullable', 'string', 'max:255'],
            'isbn'          => ['nullable', 'string', 'max:50'],
            'category_id'   => ['nullable', 'exists:book_categories,id'],
            'publisher'     => ['nullable', 'string', 'max:255'],
            'publish_year'  => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'total_copies'  => ['required', 'integer', 'min:1'],
            'price'         => ['nullable', 'numeric', 'min:0'],
            'shelf_location'=> ['nullable', 'string', 'max:100'],
            'book_status'   => ['boolean'],
        ];
    }

    public function openBookForm(): void
    {
        $this->resetBookForm();
        $this->showBookForm = true;
    }

    public function editBook(int $id): void
    {
        $book = Book::findOrFail($id);
        $this->bookId         = $book->id;
        $this->title          = $book->title;
        $this->author         = $book->author ?? '';
        $this->isbn           = $book->isbn ?? '';
        $this->category_id    = $book->category_id ? (string) $book->category_id : '';
        $this->publisher      = $book->publisher ?? '';
        $this->publish_year   = $book->publish_year ?? '';
        $this->total_copies   = (string) $book->total_copies;
        $this->price          = $book->price ?? '';
        $this->shelf_location = $book->shelf_location ?? '';
        $this->book_status    = (bool) $book->status;
        $this->showBookForm   = true;
        $this->resetValidation();
    }

    public function saveBook(): void
    {
        $data = $this->validateOnly([
            'title', 'author', 'isbn', 'category_id', 'publisher',
            'publish_year', 'total_copies', 'price', 'shelf_location', 'book_status'
        ], $this->bookRules());

        $data = array_filter([
            'title'          => $this->title,
            'author'         => $this->author ?: null,
            'isbn'           => $this->isbn ?: null,
            'category_id'    => $this->category_id ?: null,
            'publisher'      => $this->publisher ?: null,
            'publish_year'   => $this->publish_year ?: null,
            'total_copies'   => (int) $this->total_copies,
            'available_copies' => (int) $this->total_copies,
            'price'          => $this->price ?: null,
            'shelf_location' => $this->shelf_location ?: null,
            'status'         => $this->book_status,
        ], fn($v) => $v !== null);

        if ($this->bookId) {
            $book = Book::findOrFail($this->bookId);
            $diff = (int) $this->total_copies - $book->total_copies;
            $data['available_copies'] = max(0, $book->available_copies + $diff);
            $book->update($data);
            session()->flash('success', 'Book updated successfully.');
        } else {
            Book::create($data);
            session()->flash('success', 'Book added successfully.');
        }

        $this->resetBookForm();
        $this->showBookForm = false;
    }

    public function deleteBook(int $id): void
    {
        Book::findOrFail($id)->delete();
        session()->flash('success', 'Book deleted.');
    }

    public function resetBookForm(): void
    {
        $this->reset(['bookId', 'title', 'author', 'isbn', 'category_id', 'publisher', 'publish_year', 'price', 'shelf_location']);
        $this->total_copies = '1';
        $this->book_status  = true;
        $this->resetValidation();
    }

    // ─── Category CRUD ────────────────────────────────────────────
    public function openCategoryForm(): void
    {
        $this->resetCategoryForm();
        $this->showCategoryForm = true;
    }

    public function editCategory(int $id): void
    {
        $cat = BookCategory::findOrFail($id);
        $this->categoryId      = $cat->id;
        $this->cat_name        = $cat->name;
        $this->cat_description = $cat->description ?? '';
        $this->cat_status      = (bool) $cat->status;
        $this->showCategoryForm = true;
        $this->resetValidation();
    }

    public function saveCategory(): void
    {
        $this->validate([
            'cat_name'        => ['required', 'string', 'max:100'],
            'cat_description' => ['nullable', 'string'],
            'cat_status'      => ['boolean'],
        ]);

        $data = ['name' => $this->cat_name, 'description' => $this->cat_description ?: null, 'status' => $this->cat_status];

        if ($this->categoryId) {
            BookCategory::findOrFail($this->categoryId)->update($data);
            session()->flash('success', 'Category updated.');
        } else {
            BookCategory::create($data);
            session()->flash('success', 'Category added.');
        }

        $this->resetCategoryForm();
        $this->showCategoryForm = false;
    }

    public function deleteCategory(int $id): void
    {
        BookCategory::findOrFail($id)->delete();
        session()->flash('success', 'Category deleted.');
    }

    public function resetCategoryForm(): void
    {
        $this->reset(['categoryId', 'cat_name', 'cat_description']);
        $this->cat_status = true;
        $this->resetValidation();
    }

    // ─── Issue / Return ───────────────────────────────────────────
    public function openIssueForm(): void
    {
        $this->resetIssueForm();
        $this->issue_date = now()->format('Y-m-d');
        $this->due_date   = now()->addDays(14)->format('Y-m-d');
        $this->showIssueForm = true;
    }

    public function returnBook(int $id): void
    {
        $issue = BookIssue::findOrFail($id);
        $fine  = $issue->calculateFine();

        $issue->update([
            'return_date'  => now()->toDateString(),
            'status'       => BookIssue::STATUS_RETURNED,
            'fine_amount'  => $fine,
        ]);

        $issue->book->increment('available_copies');

        session()->flash('success', 'Book returned' . ($fine > 0 ? ". Fine: Rs. {$fine}" : '.'));
    }

    public function saveIssue(): void
    {
        $this->validate([
            'issue_book_id'    => ['required', 'exists:books,id'],
            'issue_student_id' => ['required_if:issue_type,student', 'nullable', 'exists:students,id'],
            'issue_teacher_id' => ['required_if:issue_type,teacher', 'nullable', 'exists:teachers,id'],
            'issue_date'       => ['required', 'date'],
            'due_date'         => ['required', 'date', 'after_or_equal:issue_date'],
        ]);

        $book = Book::findOrFail($this->issue_book_id);

        if (!$book->isAvailable()) {
            $this->addError('issue_book_id', 'No copies available.');
            return;
        }

        BookIssue::create([
            'book_id'    => $this->issue_book_id,
            'student_id' => $this->issue_type === 'student' ? $this->issue_student_id : null,
            'teacher_id' => $this->issue_type === 'teacher' ? $this->issue_teacher_id : null,
            'issued_by'  => auth()->id(),
            'issue_date' => $this->issue_date,
            'due_date'   => $this->due_date,
            'status'     => BookIssue::STATUS_ISSUED,
            'remarks'    => $this->issue_remarks ?: null,
        ]);

        $book->decrement('available_copies');

        session()->flash('success', 'Book issued successfully.');
        $this->resetIssueForm();
        $this->showIssueForm = false;
    }

    public function resetIssueForm(): void
    {
        $this->reset(['issueId', 'issue_book_id', 'issue_student_id', 'issue_teacher_id', 'issue_remarks']);
        $this->issue_type = 'student';
        $this->issue_date = now()->format('Y-m-d');
        $this->due_date   = now()->addDays(14)->format('Y-m-d');
        $this->resetValidation();
    }

    public function render()
    {
        $categories = BookCategory::orderBy('name')->get();
        $teachers   = Teacher::orderBy('name')->get();
        $students   = Student::select('id', 'first_name', 'last_name', 'admission_no')->orderBy('first_name')->get();
        $books      = Book::with('category')
            ->when($this->search, fn($q) => $q->where('title', 'like', "%{$this->search}%")->orWhere('author', 'like', "%{$this->search}%")->orWhere('isbn', 'like', "%{$this->search}%"))
            ->latest('id')->paginate(15);

        $issues = BookIssue::with(['book', 'student', 'teacher', 'issuedBy'])
            ->when($this->search, fn($q) => $q->whereHas('book', fn($bq) => $bq->where('title', 'like', "%{$this->search}%")))
            ->latest('id')->paginate(15);

        return view('livewire.library.library-manager', compact('categories', 'books', 'issues', 'students', 'teachers'));
    }
}
