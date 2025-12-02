@extends('layouts.dashboard.app')

@section('adminContent')
    <div class="card mb-3" id="customersTable"
        data-list='{"valueNames":["name","email","phone","address","joined"],"page":10,"pagination":true}'>
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">{{ __('Add New class') }}</h5>
                </div>
            </div>
        </div>
        <div class="card-body p-0">

            <div class="row g-0 h-100">
                <div class="col-md-12 d-flex flex-center">
                    <div class="p-4 p-md-5 flex-grow-1">
                        <form method="POST" action="{{ route('classes.store') }}" enctype="multipart/form-data" novalidate>
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="class_name">{{ __('Class Name ') }}</label>
                                <input name="class_name" class="form-control @error('class_name') is-invalid @enderror"
                                    value="{{ old('class_name') }}" type="text" autocomplete="on" id="class_name" autofocus
                                    required />
                                @error('class_name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="user_id">{{ __('Select Teacher') }}</label>

                                <select class="form-select @error('user_id') is-invalid @enderror" name="user_id">
                                            <option value="" disabled selected>Select </option>
         <option value=""selected>Select
                                          
                                        </option>
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
                                <label class="form-label" for="students_no">{{ __(' No Of Student') }}</label>
                                <input name="students_no" class="form-control @error('students_no') is-invalid @enderror"
                                    value="{{ old('students_no') }}" type="number" autocomplete="on" id="students_no" autofocus
                                    required />
                                @error('students_no')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="class_stage">{{ __('Class Stage') }}</label>
                                <input name="class_stage" class="form-control @error('students_no') is-invalid @enderror"
                                    value="{{ old('class_stage') }}" type="number" autocomplete="on" id="class_stage" autofocus
                                    required />
                                @error('class_stage')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-primary d-block w-100 mt-3" type="submit"
                                    name="submit">{{ __('Add New Class') }}</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
