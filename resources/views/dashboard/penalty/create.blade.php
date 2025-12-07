@extends('layouts.dashboard.app')

@section('adminContent')
    <div class="card mb-3" id="customersTable"
        data-list='{"valueNames":["name","email","phone","address","joined"],"page":10,"pagination":true}'>

        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">{{ __('Add New Penalty') }}</h5>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="row g-0 h-100">
                <div class="col-md-12 d-flex flex-center">
                    <div class="p-4 p-md-5 flex-grow-1">

                        <form method="POST" action="{{ route('penalty.store') }}" novalidate>
                            @csrf

                            {{-- Penalty Type --}}
                            <div class="mb-3">
                                <label class="form-label">{{ __('Select Type') }}</label>
                                <select class="form-select @error('type') is-invalid @enderror" name="type" id="type" required>
                                    <option value="" disabled selected>Select Type</option>
                                    <option value="morning_delay">Morning Delay</option>
                                    <option value="other">Other</option>
                                </select>
                                @error('type')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Select Teacher --}}
                            <div class="mb-3">
                                <label class="form-label">{{ __('Select Teacher') }}</label>
                                <select class="form-select @error('user_id') is-invalid @enderror" name="user_id" required>
                                    <option value="" disabled selected>Select Teacher</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Date --}}
                            <div class="mb-3">
                                <label class="form-label">{{ __('Date') }}</label>
                                <input name="date" class="form-control @error('date') is-invalid @enderror"
                                       type="date" value="{{ old('date', date('Y-m-d')) }}" required />
                                @error('date')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Minutes (only for morning_delay) --}}
                            <div class="mb-3 d-none" id="minutesWrapper">
                                <label class="form-label">{{ __('Minutes Late') }}</label>
                                <input name="minutes" class="form-control @error('minutes') is-invalid @enderror"
                                       type="number" min="1" value="{{ old('minutes') }}" />
                                @error('minutes')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Amount --}}
                            <div class="mb-3">
                                <label class="form-label">{{ __('Amount') }}</label>
                                <input name="amount" class="form-control @error('amount') is-invalid @enderror"
                                       value="{{ old('amount') }}" type="number" step="0.01" min="0" required />
                                @error('amount')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Notes --}}
                            <div class="mb-3">
                                <label class="form-label">{{ __('Notes') }}</label>
                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                                          rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Submit --}}
                            <div class="mb-3">
                                <button class="btn btn-primary d-block w-100 mt-3" type="submit">
                                    {{ __('Add New Penalty') }}
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- JS for showing minutes field --}}
    <script>
        document.getElementById('type').addEventListener('change', function () {
            const minutesWrapper = document.getElementById('minutesWrapper');

            if (this.value === 'morning_delay') {
                minutesWrapper.classList.remove('d-none');
            } else {
                minutesWrapper.classList.add('d-none');
            }
        });
    </script>
@endsection
