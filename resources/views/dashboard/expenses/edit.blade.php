@extends('layouts.dashboard.app')

@section('adminContent')
    <div class="card mb-3" id="customersTable"
        data-list='{"valueNames":["name","email","phone","address","joined"],"page":10,"pagination":true}'>
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">{{ __('Edit Expense') }}</h5>
                </div>
            </div>
        </div>
        <div class="card-body p-0">

            <div class="row g-0 h-100">
                <div class="col-md-12 d-flex flex-center">
                    <div class="p-4 p-md-5 flex-grow-1">
                        <form method="POST" action="{{ route('expenses.update', ['expense' => $expense->id]) }}"
                            enctype="multipart/form-data" novalidate>
                            @csrf
                            @method('PUT')
                           <div class="mb-3">
    <label class="form-label" for="expense_type">{{ __('Select Type') }}</label>

    <select class="form-select @error('expense_type') is-invalid @enderror" name="expense_type" id="expense_type">
        <option value="" disabled {{ old('expense_type', $expense->expense_type) ? '' : 'selected' }}>-- Select Type --</option>
        <option value="office_tools" {{ old('expense_type', $expense->expense_type) == 'office_tools' ? 'selected' : '' }}>Office Tools</option>
        <option value="cleaning_tools" {{ old('expense_type', $expense->expense_type) == 'cleaning_tools' ? 'selected' : '' }}>Cleaning Tools</option>
        <option value="bills" {{ old('expense_type', $expense->expense_type) == 'bills' ? 'selected' : '' }}>Bills</option>
        <option value="maintenance" {{ old('expense_type', $expense->expense_type) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
        <option value="teacher_salary" {{ old('expense_type', $expense->expense_type) == 'teacher_salary' ? 'selected' : '' }}>Teacher Salary</option>
    </select>

    @error('expense_type')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
</div>



                            <div class="mb-3">
                                <label class="form-label" for="user_id">{{ __('Select Teacher') }}</label>

                                <select class="form-select @error('user_id') is-invalid @enderror" name="user_id">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="expense_amount">{{ __('Expense Amount') }}</label>
                                <input name="expense_amount"
                                    class="form-control @error('expense_amount') is-invalid @enderror"
                                    value="{{ $expense->expense_amount }}" type="number" autocomplete="on"
                                    id="expense_amount" autofocus required />
                                @error('expense_amount')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                         <div class="mb-3">
    <label class="form-label" for="notes">{{ __('Notes') }}</label>
    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" 
        id="notes" rows="4" autocomplete="on" autofocus required>{{ $expense->notes }}</textarea>
    @error('notes')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
</div>


                            <div class="mb-3">
                                <button class="btn btn-primary d-block w-100 mt-3" type="submit"
                                    name="submit">{{ __('Edit Expense') }}</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
