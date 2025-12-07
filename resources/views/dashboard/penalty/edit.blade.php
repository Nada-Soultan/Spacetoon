@extends('layouts.dashboard.app')

@section('adminContent')
    <div class="card mb-3" id="customersTable">
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">
                        {{ __('Edit Penalty') }}
                    </h5>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="row g-0 h-100">
                <div class="col-md-12 d-flex flex-center">
                    <div class="p-4 p-md-5 flex-grow-1">

                        <form method="POST" action="{{ route('penalty.update', $penalty->id) }}" enctype="multipart/form-data" novalidate>
                            @csrf
                            @method('PUT')

                            {{-- Type --}}
                            <div class="mb-3">
                                <label class="form-label" for="type">{{ __('Select Type') }}</label>
                                <select class="form-select @error('type') is-invalid @enderror" name="type">
                                    <option value="" disabled>Select Type</option>
                                    <option value="morning_delay" {{ $penalty->type == 'morning_delay' ? 'selected' : '' }}>
                                        Morning Delay
                                    </option>
                                    <option value="other" {{ $penalty->type == 'other' ? 'selected' : '' }}>
                                        Other
                                    </option>
                                </select>
                                @error('type')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Teacher --}}
                            <div class="mb-3">
                                <label class="form-label" for="user_id">{{ __('Select Teacher') }}</label>
                                <select class="form-select @error('user_id') is-invalid @enderror" name="user_id">
                                    <option value="" disabled>Select Teacher</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ $penalty->user_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Amount --}}
                            <div class="mb-3">
                                <label class="form-label" for="amount">{{ __('Amount') }}</label>
                                <input name="amount" type="number" id="amount"
                                       class="form-control @error('amount') is-invalid @enderror"
                                       value="{{ old('amount', $penalty->amount) }}" required>
                                @error('amount')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            {{-- Minutes / Hours if morning_delay --}}
                            <div class="mb-3" id="delay_time_box" style="{{ $penalty->type == 'morning_delay' ? '' : 'display:none' }}">
                                <label class="form-label" for="minutes">{{ __('Delay Time (Minutes)') }}</label>
                                <input name="minutes" type="text" id="delay_minutes"
                                       class="form-control @error('delay_time') is-invalid @enderror"
                                       value="{{ old('minutes', $penalty->minutes) }}">
                                @error('minutes')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            {{-- Notes --}}
                            <div class="mb-3">
                                <label class="form-label" for="notes">{{ __('Notes') }}</label>
                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror"
                                          rows="4" required>{{ old('notes', $penalty->notes) }}</textarea>
                                @error('notes')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-primary d-block w-100 mt-3" type="submit">
                                    {{ __('Update Penalty') }}
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Small Script for toggling delay time input --}}
    <script>
        document.querySelector('[name="type"]').addEventListener('change', function() {
            let box = document.getElementById('delay_time_box');
            if (this.value === 'morning_delay') {
                box.style.display = '';
            } else {
                box.style.display = 'none';
            }
        });
    </script>
@endsection
