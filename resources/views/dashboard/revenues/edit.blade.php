@extends('layouts.dashboard.app')

@section('adminContent')
    <div class="card mb-3" id="customersTable"
        data-list='{"valueNames":["name","email","phone","address","joined"],"page":10,"pagination":true}'>
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">{{ __('Edit Revenue') }}</h5>
                </div>
            </div>
        </div>
        <div class="card-body p-0">

            <div class="row g-0 h-100">
                <div class="col-md-12 d-flex flex-center">
                    <div class="p-4 p-md-5 flex-grow-1">
                        <form method="POST" action="{{ route('revenues.update', ['revenue' => $revenue->id]) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label" for="revenue_type">{{ __('Select Type') }}</label>

                                <select class="form-select @error('revenue_type') is-invalid @enderror" name="revenue_type">
                                    <option value="subscribtion_fees" {{ $revenue->revenue_type == 'subscribtion_fees' ? 'selected' : '' }}>
                                        Subscription Fees
                                    </option>
                                    <option value="Book Fees" {{ $revenue->revenue_type == 'Book Fees' ? 'selected' : '' }}>
                                        Book Fees
                                    </option>
                                    <option value="Uniform Fees" {{ $revenue->revenue_type == 'Uniform Fees' ? 'selected' : '' }}>
                                        Uniform Fees
                                    </option>
                                </select>
                                @error('revenue_type')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="user_id">{{ __('Select Student') }}</label>

                                        <select class="form-select @error('user_id') is-invalid @enderror" name="user_id">
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" {{$user->id == $revenue->user_id ? 'selected' : ''}}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                            <div class="mb-3">
                                <label class="form-label" for="revenue_amount">{{ __('Revenue Amount') }}</label>
                                <input name="revenue_amount" class="form-control @error('revenue_amount') is-invalid @enderror"
                                    value="{{ $revenue->revenue_amount }}" type="number" autocomplete="on" id="revenue_amount"
                                    autofocus required />
                                @error('revenue_amount')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                           <div class="mb-3">
    <label class="form-label" for="notes">{{ __('Notes') }}</label>
    <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror"
        autocomplete="on" autofocus required>{{ old('notes', $revenue->notes) }}</textarea>
    @error('notes')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
</div>

                            <div class="mb-3">
                                <button class="btn btn-primary d-block w-100 mt-3" type="submit"
                                    name="submit">{{ __('Edit revenue') }}</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
