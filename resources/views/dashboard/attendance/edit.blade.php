@extends('layouts.dashboard.app')

@section('adminContent')
    <div class="card mb-3" id="customersTable"
        data-list='{"valueNames":["name","email","phone","address","joined"],"page":10,"pagination":true}'>
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">{{ __('Edit attendance') }}</h5>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="row g-0 h-100">
                <div class="col-md-12 d-flex flex-center">
                    <div class="p-4 p-md-5 flex-grow-1">
                        <form method="POST" action="{{ route('attendance.update', ['attendance' => $attendance->id]) }}"
                            enctype="multipart/form-data" novalidate>
                            @csrf
                            @method('PUT')

                            {{-- Teacher Selection --}}
                            <div class="mb-3">
                                <label class="form-label" for="user_id">{{ __('Select Teacher') }}</label>
                                <select class="form-select @error('user_id') is-invalid @enderror" name="user_id" required>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ $attendance->user_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Absence Date --}}
                            <div class="mb-3">
                                <label class="form-label" for="absence_date">{{ __('Absence Date') }}</label>
                                <input name="absence_date" class="form-control @error('absence_date') is-invalid @enderror"
                                    value="{{ $attendance->absence_date }}" type="date" id="absence_date" required autofocus />
                                @error('absence_date')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Status --}}
                            <div class="mb-3">
                                <div class="form-check form-check-inline">
                                    <input {{ $attendance->status == 'dayOff' ? 'checked' : '' }}
                                        class="form-check-input @error('status') is-invalid @enderror" id="status1"
                                        type="radio" name="status" value="dayOff" required />
                                    <label class="form-check-label" for="status1">{{ __('Day Off') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input {{ $attendance->status == 'permission' ? 'checked' : '' }}
                                        class="form-check-input @error('status') is-invalid @enderror" id="status2"
                                        type="radio" name="status" value="permission" required />
                                    <label class="form-check-label" for="status2">{{ __('Permission') }}</label>
                                </div>
                                @error('status')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Number of Hours --}}
                            <div class="mb-3">
                                <label class="form-label" for="no_of_hours">{{ __('Number of Hours') }}</label>
                                <input name="no_of_hours" class="form-control @error('no_of_hours') is-invalid @enderror"
                                    value="{{ $attendance->no_of_hours }}" type="number" id="no_of_hours" required autofocus />
                                @error('no_of_hours')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Reasons --}}
                            <div class="mb-3">
                                <label class="form-label" for="reasons">{{ __('Reasons') }}</label>
                                <textarea name="reasons" class="form-control @error('reasons') is-invalid @enderror"
                                    id="reasons" rows="4" >{{ $attendance->reasons }}</textarea>
                                @error('reasons')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Comments --}}
                            <div class="mb-3">
                                <label class="form-label" for="comments">{{ __('Comments') }}</label>
                                <textarea name="comments" class="form-control @error('comments') is-invalid @enderror"
                                    id="comments" rows="4" >{{ $attendance->comments }}</textarea>
                                @error('comments')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Submit Button --}}
                            <div class="mb-3">
                                <button class="btn btn-primary d-block w-100 mt-3" type="submit">
                                    {{ __('Edit Attendance') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
