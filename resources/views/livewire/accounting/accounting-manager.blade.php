<div>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    {{-- Filter Row --}}
    <div class="card mb-3">
        <div class="card-body py-2">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <select wire:model.live="filterMonth" class="form-control form-control-sm">
                        <option value="">All Months</option>
                        @foreach($months as $num => $name)
                            <option value="{{ $num }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input wire:model.live="filterYear" type="number" class="form-control form-control-sm" placeholder="Year" min="2000" max="2099">
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link {{ $activeTab==='overview' ? 'active' : '' }}" wire:click="$set('activeTab','overview')" href="#">
                <i class="fas fa-chart-pie"></i> Overview
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $activeTab==='expenses' ? 'active' : '' }}" wire:click="$set('activeTab','expenses')" href="#">
                <i class="fas fa-minus-circle text-danger"></i> Expenses
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $activeTab==='income' ? 'active' : '' }}" wire:click="$set('activeTab','income')" href="#">
                <i class="fas fa-plus-circle text-success"></i> Income
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $activeTab==='salaries' ? 'active' : '' }}" wire:click="$set('activeTab','salaries')" href="#">
                <i class="fas fa-user-tie"></i> Salaries
            </a>
        </li>
    </ul>

    {{-- OVERVIEW TAB --}}
    @if($activeTab === 'overview')
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h6>Total Income</h6>
                    <h3>Rs. {{ number_format($totalIncome, 0) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h6>Total Expenses</h6>
                    <h3>Rs. {{ number_format($totalExpenses, 0) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card {{ ($totalIncome - $totalExpenses - $totalSalaries) >= 0 ? 'bg-primary' : 'bg-warning' }} text-white">
                <div class="card-body text-center">
                    <h6>Net Balance</h6>
                    <h3>Rs. {{ number_format($totalIncome - $totalExpenses - $totalSalaries, 0) }}</h3>
                    <small>After salaries: Rs. {{ number_format($totalSalaries, 0) }}</small>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- EXPENSES TAB --}}
    @if($activeTab === 'expenses')
    @can('accounting.create')
        @unless($showExpenseForm)
        <div class="mb-3">
            <button wire:click="openExpenseForm" class="btn btn-danger btn-sm"><i class="fas fa-plus"></i> Add Expense</button>
        </div>
        @endunless
    @endcan

    @if($showExpenseForm)
    <div class="card border-danger mb-4">
        <div class="card-header bg-danger text-white">
            <h6 class="mb-0">{{ $expenseId ? 'Edit Expense' : 'Record Expense' }}</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Title <span class="text-danger">*</span></label>
                        <input wire:model="exp_title" type="text" class="form-control @error('exp_title') is-invalid @enderror" placeholder="Expense title">
                        @error('exp_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Amount (Rs.) <span class="text-danger">*</span></label>
                        <input wire:model="exp_amount" type="number" step="0.01" class="form-control @error('exp_amount') is-invalid @enderror">
                        @error('exp_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Date <span class="text-danger">*</span></label>
                        <input wire:model="exp_date" type="date" class="form-control @error('exp_date') is-invalid @enderror">
                        @error('exp_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Category</label>
                        <select wire:model="exp_category" class="form-control">
                            <option value="">Uncategorized</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Payment Method</label>
                        <select wire:model="exp_method" class="form-control">
                            <option value="cash">Cash</option>
                            <option value="bank">Bank Transfer</option>
                            <option value="cheque">Cheque</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Reference No.</label>
                        <input wire:model="exp_ref" type="text" class="form-control" placeholder="Optional">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea wire:model="exp_desc" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <button wire:click="$set('showExpenseForm',false)" class="btn btn-secondary btn-sm mr-2">Cancel</button>
                <button wire:click="saveExpense" class="btn btn-danger btn-sm">
                    <span wire:loading.remove>{{ $expenseId ? 'Update' : 'Save' }}</span>
                    <span wire:loading><i class="fas fa-spinner fa-spin"></i></span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-sm mb-0">
                    <thead class="thead-light">
                        <tr><th>#</th><th>Title</th><th>Category</th><th>Date</th><th>Method</th><th>Amount</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $exp)
                        <tr>
                            <td>{{ $expenses->firstItem() + $loop->index }}</td>
                            <td>{{ $exp->title }}</td>
                            <td>{{ $exp->category->name ?? 'Uncategorized' }}</td>
                            <td>{{ $exp->expense_date->format('d M Y') }}</td>
                            <td>{{ ucfirst($exp->payment_method) }}</td>
                            <td class="text-danger font-weight-bold">Rs. {{ number_format($exp->amount, 0) }}</td>
                            <td>
                                @can('accounting.edit')
                                    <button wire:click="editExpense({{ $exp->id }})" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></button>
                                @endcan
                                @can('accounting.delete')
                                    <button wire:click="deleteExpense({{ $exp->id }})" class="btn btn-sm btn-outline-danger" wire:confirm="Delete?" title="Delete"><i class="fas fa-trash"></i></button>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No expenses found.</td></tr>
                        @endforelse
                    </tbody>
                    @if($expenses->count())
                    <tfoot class="bg-light">
                        <tr>
                            <td colspan="5" class="text-right font-weight-bold">Total:</td>
                            <td class="text-danger font-weight-bold">Rs. {{ number_format($totalExpenses, 0) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
            @if($expenses->hasPages()) <div class="p-3">{{ $expenses->links() }}</div> @endif
        </div>
    </div>
    @endif

    {{-- INCOME TAB --}}
    @if($activeTab === 'income')
    @can('accounting.create')
        @unless($showIncomeForm)
        <div class="mb-3">
            <button wire:click="openIncomeForm" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Add Income</button>
        </div>
        @endunless
    @endcan

    @if($showIncomeForm)
    <div class="card border-success mb-4">
        <div class="card-header bg-success text-white">
            <h6 class="mb-0">{{ $incomeId ? 'Edit Income' : 'Record Income' }}</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Title <span class="text-danger">*</span></label>
                        <input wire:model="inc_title" type="text" class="form-control @error('inc_title') is-invalid @enderror">
                        @error('inc_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Amount (Rs.) <span class="text-danger">*</span></label>
                        <input wire:model="inc_amount" type="number" step="0.01" class="form-control @error('inc_amount') is-invalid @enderror">
                        @error('inc_amount') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Date <span class="text-danger">*</span></label>
                        <input wire:model="inc_date" type="date" class="form-control @error('inc_date') is-invalid @enderror">
                        @error('inc_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Source <span class="text-danger">*</span></label>
                        <select wire:model="inc_source" class="form-control @error('inc_source') is-invalid @enderror">
                            <option value="fee">Fee Collection</option>
                            <option value="donation">Donation</option>
                            <option value="grant">Government Grant</option>
                            <option value="other">Other</option>
                        </select>
                        @error('inc_source') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Payment Method</label>
                        <select wire:model="inc_method" class="form-control">
                            <option value="cash">Cash</option>
                            <option value="bank">Bank Transfer</option>
                            <option value="cheque">Cheque</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Reference No.</label>
                        <input wire:model="inc_ref" type="text" class="form-control" placeholder="Optional">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Description</label>
                        <textarea wire:model="inc_desc" class="form-control" rows="2"></textarea>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <button wire:click="$set('showIncomeForm',false)" class="btn btn-secondary btn-sm mr-2">Cancel</button>
                <button wire:click="saveIncome" class="btn btn-success btn-sm">
                    <span wire:loading.remove>{{ $incomeId ? 'Update' : 'Save' }}</span>
                    <span wire:loading><i class="fas fa-spinner fa-spin"></i></span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-sm mb-0">
                    <thead class="thead-light">
                        <tr><th>#</th><th>Title</th><th>Source</th><th>Date</th><th>Method</th><th>Amount</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        @forelse($incomes as $inc)
                        <tr>
                            <td>{{ $incomes->firstItem() + $loop->index }}</td>
                            <td>{{ $inc->title }}</td>
                            <td>{{ \App\Models\Income::$sources[$inc->source] ?? $inc->source }}</td>
                            <td>{{ $inc->income_date->format('d M Y') }}</td>
                            <td>{{ ucfirst($inc->payment_method) }}</td>
                            <td class="text-success font-weight-bold">Rs. {{ number_format($inc->amount, 0) }}</td>
                            <td>
                                @can('accounting.edit')
                                    <button wire:click="editIncome({{ $inc->id }})" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></button>
                                @endcan
                                @can('accounting.delete')
                                    <button wire:click="deleteIncome({{ $inc->id }})" class="btn btn-sm btn-outline-danger" wire:confirm="Delete?" title="Delete"><i class="fas fa-trash"></i></button>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No income records found.</td></tr>
                        @endforelse
                    </tbody>
                    @if($incomes->count())
                    <tfoot class="bg-light">
                        <tr>
                            <td colspan="5" class="text-right font-weight-bold">Total:</td>
                            <td class="text-success font-weight-bold">Rs. {{ number_format($totalIncome, 0) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
            @if($incomes->hasPages()) <div class="p-3">{{ $incomes->links() }}</div> @endif
        </div>
    </div>
    @endif

    {{-- SALARIES TAB --}}
    @if($activeTab === 'salaries')
    @can('accounting.create')
        @unless($showSalaryForm)
        <div class="mb-3">
            <button wire:click="openSalaryForm" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Salary Record</button>
        </div>
        @endunless
    @endcan

    @if($showSalaryForm)
    <div class="card border-primary mb-4">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0">{{ $salaryId ? 'Edit Salary' : 'Record Salary' }}</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Teacher <span class="text-danger">*</span></label>
                        <select wire:model="sal_teacher" class="form-control @error('sal_teacher') is-invalid @enderror">
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}">{{ $t->name }}</option>
                            @endforeach
                        </select>
                        @error('sal_teacher') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Month <span class="text-danger">*</span></label>
                        <select wire:model="sal_month" class="form-control @error('sal_month') is-invalid @enderror">
                            @foreach($months as $num => $name)
                                <option value="{{ $num }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('sal_month') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Year <span class="text-danger">*</span></label>
                        <input wire:model="sal_year" type="number" class="form-control @error('sal_year') is-invalid @enderror" placeholder="2026">
                        @error('sal_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Basic Salary <span class="text-danger">*</span></label>
                        <input wire:model="sal_basic" type="number" step="0.01" class="form-control @error('sal_basic') is-invalid @enderror">
                        @error('sal_basic') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Allowances</label>
                        <input wire:model="sal_allow" type="number" step="0.01" class="form-control">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Deductions</label>
                        <input wire:model="sal_deduct" type="number" step="0.01" class="form-control">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Payment Method</label>
                        <select wire:model="sal_method" class="form-control">
                            <option value="cash">Cash</option>
                            <option value="bank">Bank Transfer</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Reference No.</label>
                        <input wire:model="sal_ref" type="text" class="form-control" placeholder="Optional">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Remarks</label>
                        <input wire:model="sal_remarks" type="text" class="form-control">
                    </div>
                </div>
            </div>
            <div class="text-right">
                <button wire:click="$set('showSalaryForm',false)" class="btn btn-secondary btn-sm mr-2">Cancel</button>
                <button wire:click="saveSalary" class="btn btn-primary btn-sm">
                    <span wire:loading.remove>{{ $salaryId ? 'Update' : 'Save' }}</span>
                    <span wire:loading><i class="fas fa-spinner fa-spin"></i></span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-sm mb-0">
                    <thead class="thead-light">
                        <tr><th>#</th><th>Teacher</th><th>Month / Year</th><th>Basic</th><th>Allowances</th><th>Deductions</th><th>Net</th><th>Status</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        @forelse($salaries as $sal)
                        <tr>
                            <td>{{ $salaries->firstItem() + $loop->index }}</td>
                            <td>{{ $sal->teacher->name ?? '—' }}</td>
                            <td>{{ \App\Models\Salary::$months[$sal->month] ?? $sal->month }} {{ $sal->year }}</td>
                            <td>Rs. {{ number_format($sal->basic_salary, 0) }}</td>
                            <td class="text-success">+ Rs. {{ number_format($sal->allowances, 0) }}</td>
                            <td class="text-danger">- Rs. {{ number_format($sal->deductions, 0) }}</td>
                            <td class="font-weight-bold">Rs. {{ number_format($sal->net_salary, 0) }}</td>
                            <td>
                                @if($sal->status === 'paid')
                                    <span class="badge badge-success">Paid</span>
                                @else
                                    <span class="badge badge-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                @can('accounting.edit')
                                    @if($sal->status !== 'paid')
                                        <button wire:click="markSalaryPaid({{ $sal->id }})" class="btn btn-xs btn-success" title="Mark Paid"><i class="fas fa-check"></i></button>
                                    @endif
                                    <button wire:click="editSalary({{ $sal->id }})" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></button>
                                @endcan
                                @can('accounting.delete')
                                    <button wire:click="deleteSalary({{ $sal->id }})" class="btn btn-sm btn-outline-danger" wire:confirm="Delete?" title="Delete"><i class="fas fa-trash"></i></button>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">No salary records found.</td></tr>
                        @endforelse
                    </tbody>
                    @if($salaries->count())
                    <tfoot class="bg-light">
                        <tr>
                            <td colspan="6" class="text-right font-weight-bold">Total Net Salaries:</td>
                            <td class="font-weight-bold">Rs. {{ number_format($totalSalaries, 0) }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
            @if($salaries->hasPages()) <div class="p-3">{{ $salaries->links() }}</div> @endif
        </div>
    </div>
    @endif
</div>
