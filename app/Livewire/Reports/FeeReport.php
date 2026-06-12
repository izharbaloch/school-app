<?php

namespace App\Livewire\Reports;

use App\Models\FeePayment;
use App\Models\StudentClass;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FeeReport extends Component
{
    public string $filter_from  = '';
    public string $filter_to    = '';
    public string $filter_class = '';
    public string $filter_mode  = '';

    public function mount(): void
    {
        $this->filter_from = now()->startOfMonth()->format('Y-m-d');
        $this->filter_to   = now()->format('Y-m-d');
    }

    public function getClassesProperty()
    {
        return StudentClass::orderBy('name')->get(['id', 'name']);
    }

    public function render()
    {
        $user = Auth::user();

        $query = FeePayment::allowedForUser($user)
            ->with([
                'studentFee.student:id,first_name,last_name,student_class_id,admission_no',
                'studentFee.student.studentClass:id,name',
                'studentFee.feeType:id,name',
            ])
            ->when($this->filter_from, fn($q) => $q->where('payment_date', '>=', $this->filter_from))
            ->when($this->filter_to,   fn($q) => $q->where('payment_date', '<=', $this->filter_to))
            ->when($this->filter_mode, fn($q) => $q->where('payment_method', $this->filter_mode))
            ->when($this->filter_class, fn($q) =>
                $q->whereHas('studentFee.student', fn($sq) =>
                    $sq->where('student_class_id', $this->filter_class)
                )
            )
            ->orderBy('payment_date', 'desc');

        $payments  = $query->get();
        $total     = $payments->sum('amount');

        $byMethod  = $payments->groupBy('payment_method')->map->sum('amount');

        return view('livewire.reports.fee-report', [
            'payments' => $payments,
            'total'    => $total,
            'byMethod' => $byMethod,
            'classes'  => $this->classes,
        ]);
    }
}
