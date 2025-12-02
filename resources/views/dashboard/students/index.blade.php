@extends('layouts.dashboard.app')

@section('adminContent')
    <div class="card mb-3" id="customersTable"
        data-list='{"valueNames":["name","email","phone","address","joined"],"page":10,"pagination":true}'>
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">
                        @if ($students->count() > 0 && $students[0]->trashed())
                            {{ __('Students Trash') }}
                        @else
                            {{ __('Students') }}
                        @endif
                    </h5>
                </div>
                <div class="col-8 col-sm-auto text-end ps-2">
                    <div class="d-none" id="table-customers-actions">
                        <div class="d-flex">
                            <select class="form-select form-select-sm" aria-label="Bulk actions">
                                <option selected="">{{ __('Bulk actions') }}</option>
                                <option value="Refund">{{ __('Refund') }}</option>
                                <option value="Delete">{{ __('Delete') }}</option>
                                <option value="Archive">{{ __('Archive') }}</option>
                            </select>
                            <button class="btn btn-falcon-default btn-sm ms-2" type="button">{{ __('Apply') }}</button>
                        </div>
                    </div>
                    <div id="table-customers-replace-element">

                        @if (auth()->user()->hasPermission('students-create'))
                            <a href="{{ route('students.create') }}" class="btn btn-falcon-default btn-sm"
                                type="button"><span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span><span
                                    class="d-none d-sm-inline-block ms-1">{{ __('New') }}</span></a>
                        @endif
                        {{-- <a href="{{ route('students.trashed') }}" class="btn btn-falcon-default btn-sm" type="button"><span
                                class="fas fa-trash" data-fa-transform="shrink-3 down-2"></span><span
                                class="d-none d-sm-inline-block ms-1">{{ __('Trash') }}</span></a> --}}
                        {{-- <button class="btn btn-falcon-default btn-sm" type="button"><span class="fas fa-external-link-alt"
                                data-fa-transform="shrink-3 down-2"></span><span
                                class="d-none d-sm-inline-block ms-1">{{ __('Export') }}</span></button> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive scrollbar">
                @if ($students->count() > 0)
                    <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                        <thead class="bg-200 text-900">
                            <tr>
                                {{-- <th>
                                    <div class="form-check fs-0 mb-0 d-flex align-items-center">
                                        <input class="form-check-input" id="checkbox-bulk-customers-select" type="checkbox"
                                            data-bulk-select='{"body":"table-customers-body","actions":"table-customers-actions","replacedElement":"table-customers-replace-element"}' />
                                    </div>
                                </th> --}}

                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="name">{{ __('Name') }}
                                </th>
                                {{--
                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="name">{{ __('class name') }}
                                </th> --}}

                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="tech_update">
                                    {{ __('Student Details') }}
                                </th>


                                <th class="sort pe-1 align-middle white-space-nowrap" style="min-width: 100px;"
                                    data-sort="joined">{{ __('Created at') }}</th>
                                @if ($students->count() > 0 && $students[0]->trashed())
                                    <th class="sort pe-1 align-middle white-space-nowrap" style="min-width: 100px;"
                                        data-sort="joined">{{ __('Deleted at') }}</th>
                                @endif
                                <th class="align-middle no-sort"></th>
                            </tr>
                        </thead>
                        <tbody class="list" id="table-customers-body">
                            @foreach ($students as $student)
                                {{-- @if ($student->user !== null && $student->user->phone_verified_at !== null) --}}
                                    <tr class="btn-reveal-trigger">
                                        {{-- <td class="align-middle py-2" style="width: 28px;">
                                            <div class="form-check fs-0 mb-0 d-flex align-items-center">
                                                <input class="form-check-input" type="checkbox" id="customer-0"
                                                    data-bulk-select-row="data-bulk-select-row" />
                                            </div>
                                        </td> --}}
                                        <td class="name align-middle white-space-nowrap py-2">
                                            <div class="d-flex d-flex align-items-center">

                                                <div class="flex-1">
                                                    <h5 class="mb-0 fs--1">
                                                        {{-- @if ($student->user->phone !== null)
                                                            {{ $student->user->phone }}
                                                        @else
                                                            N/A
                                                        @endif --}}
                                                        {{-- {{ app()->getLocale() == 'ar' ? $class->name_ar : $class->name_en }} --}}
{{$student->user?->name ?? 'N/A'}}
                                                    </h5>
                                                 </div>
                                            </div>
                                        </td>

                                        <td class="joined align-middle py-2">
                                            @if ($student->age != null)
                                                <button class="btn btn-sm btn-outline-success me-1 mb-1" type="button"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#show-task-{{ $student->id }}">{{ __('show') }}
                                                </button>
                                            @endif
                                        </td>




                                        <td class="joined align-middle py-2">{{ $student->created_at }} <br>
                                            {{ interval($student->created_at) }} </td>
                                        @if ($student->trashed())
                                            <td class="joined align-middle py-2">{{ $student->deleted_at }} <br>
                                                {{ interval($student->deleted_at) }} </td>
                                        @endif
                                        <td class="align-middle white-space-nowrap py-2 text-end">
                                            <div class="dropdown font-sans-serif position-static">
                                                <button class="btn btn-link text-600 btn-sm dropdown-toggle btn-reveal"
                                                    type="button" id="customer-dropdown-0" data-bs-toggle="dropdown"
                                                    data-boundary="window" aria-haspopup="true" aria-expanded="false"><span
                                                        class="fas fa-ellipsis-h fs--1"></span></button>
                                                <div class="dropdown-menu dropdown-menu-end border py-0"
                                                    aria-labelledby="customer-dropdown-0">
                                                    <div class="bg-white py-2">
                                                        @if ($student->trashed() && auth()->user()->hasPermission('students-restore'))
                                                            <a class="dropdown-item"
                                                                href="{{ route('students.restore', ['student' => $student->id]) }}">{{ __('Restore') }}</a>
                                                        @elseif(auth()->user()->hasPermission('students-update'))
                                                            <a class="dropdown-item"
                                                                href="{{ route('students.edit', ['student' => $student->id]) }}">{{ __('Edit') }}</a>
                                                        @endif
                                                        {{-- @if (auth()->user()->hasPermission('students-delete') || auth()->user()->hasPermission('students-trash'))
                                                            <form method="POST"
                                                                action="{{ route('students.destroy', ['student' => $student->id]) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="dropdown-item text-danger"
                                                                    type="submit">{{ $student->trashed() ? __('Delete') : __('Trash') }}</button>
                                                            </form>
                                                        @endif --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                {{-- @endif --}}
                            @endforeach
                        </tbody>

                    </table>

                @foreach ($students as $student)
    <div class="modal fade" id="show-task-{{ $student->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content position-relative">
                <div class="position-absolute top-0 end-0 mt-2 me-2 z-index-1">
                    <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="rounded-top-lg py-3 ps-4 pe-6 bg-light">
                        <h4 class="mb-1" id="modalExampleDemoLabel">
                            {{ __('Student Details') }}
                        </h4>
                    </div>
                    <div class="p-4 pb-0">

                        <!-- Student Information Table -->
                        <div class="table-responsive scrollbar mb-4">
                            <table class="table table-bordered overflow-hidden">
                                <colgroup>
                                    <col class="bg-soft-primary" style="width: 40%;" />
                                    <col />
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <td><strong>{{ __('Name') }}</strong></td>
                                        <td>{{ $student->user?->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Email') }}</strong></td>
                                        <td>{{ $student->user?->email ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Phone') }}</strong></td>
                                        <td>{{ $student->user?->phone ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Gender') }}</strong></td>
                                        <td>{{ $student->user?->gender ? __(ucfirst($student->user->gender)) : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Age') }}</strong></td>
                                        <td>{{ $student->age ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Date of Birth') }}</strong></td>
                                        <td>{{ $student->date_of_birth ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Date of Join') }}</strong></td>
                                        <td>{{ $student->date_of_join ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Class') }}</strong></td>
                                        <td>{{ $student->class?->class_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Fees of Uniform') }}</strong></td>
                                        <td>{{ $student->fees_of_uniform ?? '0' }} {{ __('EGP') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Fees of Book') }}</strong></td>
                                        <td>{{ $student->fees_of_book ?? '0' }} {{ __('EGP') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Student Images Section -->
                        @if($student->images && $student->images->count() > 0)
                            <div class="mb-4">
                                <h5 class="mb-3">{{ __('Attached Images') }}</h5>
                                <div class="row g-3">
                                    @foreach($student->images as $image)
                                        <div class="col-md-4 col-sm-6">
                                            <div class="card h-100 shadow-sm">
                                                <a href="{{ asset('storage/images/students/' . $image->image) }}" 
                                                   target="_blank" 
                                                   class="text-decoration-none">
                                                    <img src="{{ asset('storage/images/students/' . $image->image) }}" 
                                                         class="card-img-top" 
                                                         alt="Student Image"
                                                         style="height: 200px; object-fit: cover; cursor: pointer;"
                                                         onerror="this.src='{{ asset('storage/images/placeholder.png') }}'">
                                                </a>
                                                <div class="card-body p-2 text-center">
                                                    <small class="text-muted">
                                                        {{ __('Uploaded') }}: {{ $image->created_at->format('Y-m-d') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                {{ __('No images attached for this student') }}
                            </div>
                        @endif

                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">
                        {{ __('Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<style>
.card img:hover {
    opacity: 0.9;
    transition: opacity 0.3s ease;
}

.modal-lg {
    max-width: 900px;
}
</style>
                @else
                    <h3 class="p-4">{{ __('No students to Show') }}</h3>
                @endif
            </div>
        </div>


        <div class="card-footer d-flex align-items-center justify-content-center">
            {{ $students->appends(request()->query())->links() }}
        </div>

    </div>
@endsection
