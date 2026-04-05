<div>
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <p><strong>Student:</strong> {{ $studentFee->student->full_name ?? $studentFee->student->name }}</p>
            <p><strong>Fee Type:</strong> {{ $studentFee->feeType->name ?? '-' }}</p>
            <p><strong>Payable Amount:</strong> {{ number_format($studentFee->payable_amount, 2) }}</p>
            <p><strong>Already Paid:</strong> {{ number_format($studentFee->paid_amount, 2) }}</p>
            <p><strong>Remaining Amount:</strong> {{ number_format($studentFee->remaining_amount, 2) }}</p>

            <form wire:submit.prevent="save">
                <div class="row">
                    <div class="col-md-4">
                        <label>Payment Date</label>
                        <input type="date" wire:model.defer="payment_date"
                            class="form-control @error('payment_date') is-invalid @enderror">
                        @error('payment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label>Amount</label>
                        <input type="number" step="0.01" wire:model.defer="amount"
                            class="form-control @error('amount') is-invalid @enderror">
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label>Payment Method</label>
                        <input type="text" wire:model.defer="payment_method"
                            class="form-control @error('payment_method') is-invalid @enderror"
                            placeholder="Cash / Bank / Easypaisa">
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label>Reference No</label>
                        <input type="text" wire:model.defer="reference_no"
                            class="form-control @error('reference_no') is-invalid @enderror">
                        @error('reference_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label>Remarks</label>
                        <input type="text" wire:model.defer="remarks"
                            class="form-control @error('remarks') is-invalid @enderror">
                        @error('remarks')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button class="btn btn-primary mt-3">Save Payment</button>
                <a href="{{ route('student-fees.show', $studentFee->id) }}" class="btn btn-secondary mt-3">Back</a>
            </form>
        </div>
    </div>
</div>
