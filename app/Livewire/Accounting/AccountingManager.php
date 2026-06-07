<?php

namespace App\Livewire\Accounting;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Income;
use App\Models\Salary;
use App\Models\Teacher;
use Livewire\Component;
use Livewire\WithPagination;

class AccountingManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public string $activeTab = 'overview';

    // Expense form
    public bool $showExpenseForm = false;
    public ?int $expenseId       = null;
    public string $exp_title     = '';
    public string $exp_desc      = '';
    public string $exp_category  = '';
    public string $exp_amount    = '';
    public string $exp_date      = '';
    public string $exp_method    = 'cash';
    public string $exp_ref       = '';

    // Income form
    public bool $showIncomeForm = false;
    public ?int $incomeId       = null;
    public string $inc_title    = '';
    public string $inc_desc     = '';
    public string $inc_source   = 'other';
    public string $inc_amount   = '';
    public string $inc_date     = '';
    public string $inc_method   = 'cash';
    public string $inc_ref      = '';

    // Salary form
    public bool $showSalaryForm = false;
    public ?int $salaryId       = null;
    public string $sal_teacher  = '';
    public string $sal_basic    = '';
    public string $sal_allow    = '0';
    public string $sal_deduct   = '0';
    public string $sal_month    = '';
    public string $sal_year     = '';
    public string $sal_method   = 'cash';
    public string $sal_ref      = '';
    public string $sal_remarks  = '';

    // Filter
    public string $filterMonth  = '';
    public string $filterYear   = '';

    public function mount(): void
    {
        $this->exp_date    = now()->format('Y-m-d');
        $this->inc_date    = now()->format('Y-m-d');
        $this->sal_month   = (string) now()->month;
        $this->sal_year    = (string) now()->year;
        $this->filterYear  = (string) now()->year;
        $this->filterMonth = (string) now()->month;
    }

    // ─── Expenses ─────────────────────────────────────────────────
    public function openExpenseForm(): void { $this->resetExpenseForm(); $this->showExpenseForm = true; }

    public function editExpense(int $id): void
    {
        $e = Expense::findOrFail($id);
        $this->expenseId   = $e->id;
        $this->exp_title   = $e->title;
        $this->exp_desc    = $e->description ?? '';
        $this->exp_category= $e->category_id ? (string) $e->category_id : '';
        $this->exp_amount  = (string) $e->amount;
        $this->exp_date    = $e->expense_date->format('Y-m-d');
        $this->exp_method  = $e->payment_method;
        $this->exp_ref     = $e->reference_no ?? '';
        $this->showExpenseForm = true;
        $this->resetValidation();
    }

    public function saveExpense(): void
    {
        $this->validate([
            'exp_title'  => ['required', 'string', 'max:255'],
            'exp_amount' => ['required', 'numeric', 'min:0.01'],
            'exp_date'   => ['required', 'date'],
        ]);

        $data = [
            'title'          => $this->exp_title,
            'description'    => $this->exp_desc ?: null,
            'category_id'    => $this->exp_category ?: null,
            'amount'         => $this->exp_amount,
            'expense_date'   => $this->exp_date,
            'payment_method' => $this->exp_method,
            'reference_no'   => $this->exp_ref ?: null,
            'created_by'     => auth()->id(),
        ];

        if ($this->expenseId) {
            Expense::findOrFail($this->expenseId)->update($data);
            session()->flash('success', 'Expense updated.');
        } else {
            Expense::create($data);
            session()->flash('success', 'Expense recorded.');
        }

        $this->resetExpenseForm();
        $this->showExpenseForm = false;
    }

    public function deleteExpense(int $id): void
    {
        Expense::findOrFail($id)->delete();
        session()->flash('success', 'Expense deleted.');
    }

    public function resetExpenseForm(): void
    {
        $this->reset(['expenseId', 'exp_title', 'exp_desc', 'exp_category', 'exp_amount', 'exp_ref']);
        $this->exp_date   = now()->format('Y-m-d');
        $this->exp_method = 'cash';
        $this->resetValidation();
    }

    // ─── Income ───────────────────────────────────────────────────
    public function openIncomeForm(): void { $this->resetIncomeForm(); $this->showIncomeForm = true; }

    public function editIncome(int $id): void
    {
        $i = Income::findOrFail($id);
        $this->incomeId   = $i->id;
        $this->inc_title  = $i->title;
        $this->inc_desc   = $i->description ?? '';
        $this->inc_source = $i->source;
        $this->inc_amount = (string) $i->amount;
        $this->inc_date   = $i->income_date->format('Y-m-d');
        $this->inc_method = $i->payment_method;
        $this->inc_ref    = $i->reference_no ?? '';
        $this->showIncomeForm = true;
        $this->resetValidation();
    }

    public function saveIncome(): void
    {
        $this->validate([
            'inc_title'  => ['required', 'string', 'max:255'],
            'inc_amount' => ['required', 'numeric', 'min:0.01'],
            'inc_date'   => ['required', 'date'],
            'inc_source' => ['required'],
        ]);

        $data = [
            'title'          => $this->inc_title,
            'description'    => $this->inc_desc ?: null,
            'source'         => $this->inc_source,
            'amount'         => $this->inc_amount,
            'income_date'    => $this->inc_date,
            'payment_method' => $this->inc_method,
            'reference_no'   => $this->inc_ref ?: null,
            'created_by'     => auth()->id(),
        ];

        if ($this->incomeId) {
            Income::findOrFail($this->incomeId)->update($data);
            session()->flash('success', 'Income updated.');
        } else {
            Income::create($data);
            session()->flash('success', 'Income recorded.');
        }

        $this->resetIncomeForm();
        $this->showIncomeForm = false;
    }

    public function deleteIncome(int $id): void
    {
        Income::findOrFail($id)->delete();
        session()->flash('success', 'Income deleted.');
    }

    public function resetIncomeForm(): void
    {
        $this->reset(['incomeId', 'inc_title', 'inc_desc', 'inc_ref', 'inc_amount']);
        $this->inc_source = 'other';
        $this->inc_date   = now()->format('Y-m-d');
        $this->inc_method = 'cash';
        $this->resetValidation();
    }

    // ─── Salary ───────────────────────────────────────────────────
    public function openSalaryForm(): void { $this->resetSalaryForm(); $this->showSalaryForm = true; }

    public function editSalary(int $id): void
    {
        $s = Salary::findOrFail($id);
        $this->salaryId    = $s->id;
        $this->sal_teacher = (string) $s->teacher_id;
        $this->sal_basic   = (string) $s->basic_salary;
        $this->sal_allow   = (string) $s->allowances;
        $this->sal_deduct  = (string) $s->deductions;
        $this->sal_month   = (string) $s->month;
        $this->sal_year    = (string) $s->year;
        $this->sal_method  = $s->payment_method;
        $this->sal_ref     = $s->reference_no ?? '';
        $this->sal_remarks = $s->remarks ?? '';
        $this->showSalaryForm = true;
        $this->resetValidation();
    }

    public function saveSalary(): void
    {
        $this->validate([
            'sal_teacher' => ['required', 'exists:teachers,id'],
            'sal_basic'   => ['required', 'numeric', 'min:0'],
            'sal_month'   => ['required', 'integer', 'between:1,12'],
            'sal_year'    => ['required', 'integer', 'min:2000'],
        ]);

        $data = [
            'teacher_id'     => $this->sal_teacher,
            'basic_salary'   => $this->sal_basic,
            'allowances'     => $this->sal_allow ?: 0,
            'deductions'     => $this->sal_deduct ?: 0,
            'month'          => $this->sal_month,
            'year'           => $this->sal_year,
            'payment_method' => $this->sal_method,
            'reference_no'   => $this->sal_ref ?: null,
            'remarks'        => $this->sal_remarks ?: null,
        ];

        if ($this->salaryId) {
            Salary::findOrFail($this->salaryId)->update($data);
            session()->flash('success', 'Salary record updated.');
        } else {
            Salary::create($data);
            session()->flash('success', 'Salary record created.');
        }

        $this->resetSalaryForm();
        $this->showSalaryForm = false;
    }

    public function markSalaryPaid(int $id): void
    {
        Salary::findOrFail($id)->update([
            'status'       => 'paid',
            'payment_date' => now()->toDateString(),
            'paid_by'      => auth()->id(),
        ]);
        session()->flash('success', 'Salary marked as paid.');
    }

    public function deleteSalary(int $id): void
    {
        Salary::findOrFail($id)->delete();
        session()->flash('success', 'Salary record deleted.');
    }

    public function resetSalaryForm(): void
    {
        $this->reset(['salaryId', 'sal_teacher', 'sal_basic', 'sal_ref', 'sal_remarks']);
        $this->sal_allow  = '0';
        $this->sal_deduct = '0';
        $this->sal_month  = (string) now()->month;
        $this->sal_year   = (string) now()->year;
        $this->sal_method = 'cash';
        $this->resetValidation();
    }

    public function render()
    {
        $categories = ExpenseCategory::orderBy('name')->get();
        $teachers   = Teacher::where('status', true)->orderBy('name')->get();

        $expenses = Expense::with(['category', 'creator'])
            ->when($this->filterYear, fn($q) => $q->whereYear('expense_date', $this->filterYear))
            ->when($this->filterMonth, fn($q) => $q->whereMonth('expense_date', $this->filterMonth))
            ->latest('expense_date')->paginate(15, ['*'], 'expPage');

        $incomes = Income::with('creator')
            ->when($this->filterYear, fn($q) => $q->whereYear('income_date', $this->filterYear))
            ->when($this->filterMonth, fn($q) => $q->whereMonth('income_date', $this->filterMonth))
            ->latest('income_date')->paginate(15, ['*'], 'incPage');

        $salaries = Salary::with('teacher')
            ->when($this->filterYear, fn($q) => $q->where('year', $this->filterYear))
            ->when($this->filterMonth, fn($q) => $q->where('month', $this->filterMonth))
            ->latest('id')->paginate(15, ['*'], 'salPage');

        // Overview stats
        $totalExpenses = Expense::when($this->filterYear, fn($q) => $q->whereYear('expense_date', $this->filterYear))
            ->when($this->filterMonth, fn($q) => $q->whereMonth('expense_date', $this->filterMonth))
            ->sum('amount');

        $totalIncome = Income::when($this->filterYear, fn($q) => $q->whereYear('income_date', $this->filterYear))
            ->when($this->filterMonth, fn($q) => $q->whereMonth('income_date', $this->filterMonth))
            ->sum('amount');

        $totalSalaries = Salary::when($this->filterYear, fn($q) => $q->where('year', $this->filterYear))
            ->when($this->filterMonth, fn($q) => $q->where('month', $this->filterMonth))
            ->selectRaw('SUM(basic_salary + allowances - deductions) as total')->value('total') ?? 0;

        $months = Salary::$months;

        return view('livewire.accounting.accounting-manager', compact(
            'categories', 'teachers', 'expenses', 'incomes', 'salaries',
            'totalExpenses', 'totalIncome', 'totalSalaries', 'months'
        ));
    }
}
