<?php

namespace App\Livewire\Fees;

use App\Mail\FeePaymentReceiptMail;
use App\Models\FeePayment;
use App\Models\SchoolSetting;
use App\Models\StudentFee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class FeePaymentCreate extends Component
{
    public StudentFee $studentFee;

    public $payment_date = '';
    public $amount = '';
    public $payment_method = '';
    public $reference_no = '';
    public $remarks = '';

    public function mount(StudentFee $studentFee)
    {
        $this->authorize('create', FeePayment::class);
        abort_unless(StudentFee::allowedForUser(auth()->user())->whereKey($studentFee->id)->exists(), 403);

        $this->studentFee = $studentFee->load([
            'student.studentClass:id,name',
            'student.section:id,name',
            'feeType:id,name',
        ]);

        $this->payment_date = now()->toDateString();
        $this->amount = $this->studentFee->remaining_amount;
    }

    public function rules()
    {
        return [
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_method' => ['nullable', 'string', 'max:255'],
            'reference_no' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string'],
        ];
    }

    public function save()
    {
        $rateLimitKey = 'fee-payment:' . auth()->id();

        if (RateLimiter::tooManyAttempts($rateLimitKey, 15)) {
            $this->addError('amount', 'Too many payment submissions — please wait a moment and try again.');
            return;
        }
        RateLimiter::hit($rateLimitKey, 60);

        $this->validate();

        $remaining = $this->studentFee->remaining_amount;

        if ($this->amount > $remaining) {
            $this->addError('amount', 'Amount cannot be greater than remaining amount.');
            return;
        }

        $payment = null;

        DB::transaction(function () use (&$payment) {
            $payment = FeePayment::create([
                'student_fee_id' => $this->studentFee->id,
                'payment_date' => $this->payment_date,
                'amount' => $this->amount,
                'payment_method' => $this->payment_method,
                'reference_no' => $this->reference_no,
                'remarks' => $this->remarks,
                'received_by' => auth()->id(),
            ]);

            $newPaidAmount = ((float) $this->studentFee->paid_amount + (float) $this->amount);
            $payableAmount = $this->studentFee->payable_amount;

            $status = StudentFee::UNPAID;

            if ($newPaidAmount <= 0) {
                $status = StudentFee::UNPAID;
            } elseif ($newPaidAmount < $payableAmount) {
                $status = StudentFee::PARTIAL;
            } else {
                $status = StudentFee::PAID;
            }

            $this->studentFee->update([
                'paid_amount' => $newPaidAmount,
                'status' => $status,
            ]);
        });

        if ($payment && SchoolSetting::get('notifications_enabled', '1') === '1') {
            $email = $this->studentFee->student->guardian_email
                ?? $this->studentFee->student->guardian?->email;
            if ($email) {
                try {
                    $this->studentFee->refresh();
                    Mail::to($email)->queue(new FeePaymentReceiptMail($this->studentFee, $payment));
                } catch (\Exception) {
                    // mail failure must not break payment recording
                }
            }
        }

        session()->flash('success', 'Payment saved successfully.');

        return redirect()->route('student-fees.show', $this->studentFee->id);
    }

    public function render()
    {
        $this->studentFee->refresh();

        return view('livewire.fees.fee-payment-create');
    }
}
