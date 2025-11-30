@extends('layouts.dashboard.app')

@section('adminContent')
    <div class="card mb-3" id="customersTable"
        data-list='{"valueNames":["name","email","phone","address","joined"],"page":10,"pagination":true}'>
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">{{ __('Add New Teacher') }}</h5>
                </div>
            </div>
        </div>
        <div class="card-body p-0">

            <div class="row g-0 h-100">
                <div class="col-md-12 d-flex flex-center">
                    <div class="p-4 p-md-5 flex-grow-1">
                        <form method="POST" action="{{ route('teachers.store') }}" enctype="multipart/form-data" novalidate>
                            @csrf

                            {{-- <div class="mb-3">
                                <label class="form-label" for="class_id">{{ __('select class') }}</label>

                                <select class="form-select @error('class_id') is-invalid @enderror" name="class_id">
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">
                                            {{ $class->class_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div> --}}

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
                                <label class="form-label" for="salary">{{ __('Salary') }}</label>
                                <input name="salary" class="form-control @error('salary') is-invalid @enderror"
                                    value="{{ old('salary') }}" type="number" autocomplete="on" id="salary" autofocus
                                    required />
                                @error('salary')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label class="form-label" for="extra_courses">{{ __('Extra Courses') }}</label>
                                <input name="extra_courses" class="form-control @error('extra_courses') is-invalid @enderror"
                                    value="{{ old('extra_courses') }}" type="text" autocomplete="on" id="extra_courses" autofocus
                                    required />
                                @error('extra_courses')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="fees_of_courses">{{ __(' Fees Of Courses') }}</label>
                                <input name="fees_of_courses" class="form-control @error('fees_of_courses') is-invalid @enderror"
                                    value="{{ old('fees_of_courses') }}" type="number" autocomplete="on" id="fees_of_courses" autofocus
                                    required />
                                @error('fees_of_courses')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- <div class="mb-3">
                                <label for="deductions">Deduction:</label>
                                <input type="text" name="deductions" class="form-control" id="deductions" value="{{ old('deductions', $deductions) }}" readonly>
                                @error('deductions')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="total">Total Amount:</label>
                                <input type="text" name="total" class="form-control" id="total" value="{{ old('total', $netSalary) }}" readonly>
                                @error('total')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div> --}}










                            <div class="mb-3">
                                <button class="btn btn-primary d-block w-100 mt-3" type="submit"
                                    name="submit">{{ __('Add') }}</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
