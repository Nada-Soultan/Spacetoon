@extends('layouts.dashboard.app')

@section('adminContent')
    <div class="card mb-3" id="customersTable"
        data-list='{"valueNames":["name","email","phone","address","joined"],"page":10,"pagination":true}'>
      <div class="card-header">
    <div class="row flex-between-center">

        <!-- LEFT SIDE: Title -->
        <div class="col d-flex align-items-center">
            <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">
                @if ($attendances->count() > 0 && $attendances[0]->trashed())
                    {{ __('Attendance Trash') }}
                @else
                    {{ __('Attendance') }}
                @endif
            </h5>
        </div>

        <!-- RIGHT SIDE: Filter + Buttons -->
        <div class="col-auto d-flex align-items-center gap-2">

            <!-- Month / Year Filter -->
            <form method="GET" action="{{ route('attendance.index') }}" class="d-flex gap-2">

                <select name="month" class="form-select form-select-sm">
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                        </option>
                    @endforeach
                </select>

                <select name="year" class="form-select form-select-sm">
                    @foreach(range(2022, now()->year) as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-primary btn-sm">
                    {{ __('Filter') }}
                </button>
            </form>

            <!-- New Button -->
            @if (auth()->user()->hasPermission('attendance-create'))
                <a href="{{ route('attendance.create') }}" class="btn btn-falcon-default btn-sm">
                    <span class="fas fa-plus"></span>
                    <span class="d-none d-sm-inline-block ms-1">{{ __('New') }}</span>
                </a>
            @endif

            <!-- Trash Button -->
            <a href="{{ route('attendance.trashed') }}" class="btn btn-falcon-default btn-sm">
                <span class="fas fa-trash"></span>
                <span class="d-none d-sm-inline-block ms-1">{{ __('Trash') }}</span>
            </a>

        </div>

    </div>
</div>

        <div class="card-body p-0">
            <div class="table-responsive scrollbar">
                @if ($attendances->count() > 0)
                    <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                        <thead class="bg-200 text-900">
                            <tr>
                                {{-- <th>
                                    <div class="form-check fs-0 mb-0 d-flex align-items-center">
                                        <input class="form-check-input" id="checkbox-bulk-customers-select" type="checkbox"
                                            data-bulk-select='{"body":"table-customers-body","actions":"table-customers-actions","replacedElement":"table-customers-replace-element"}' />
                                    </div>
                                </th> --}}


 <th class="sort pe-1 align-middle white-space-nowrap" data-sort="name">{{ __('Teacher') }}
                                </th>


                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="name">{{ __('Status') }}
                                </th>
{{--
                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="name">{{ __('class name') }}
                                </th> --}}

                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="tech_update">
                                    {{ __('Absence Details') }}
                                </th>

                                <th class="sort pe-1 align-middle white-space-nowrap" style="min-width: 100px;"
                                    data-sort="joined">{{ __('Created at') }}</th>
                                @if ($attendances->count() > 0 && $attendances[0]->trashed())
                                    <th class="sort pe-1 align-middle white-space-nowrap" style="min-width: 100px;"
                                        data-sort="joined">{{ __('Deleted at') }}</th>
                                @endif
                                <th class="align-middle no-sort"></th>

                            </tr>
                        </thead>
                        <tbody class="list" id="table-customers-body">
                            @foreach ($attendances as $attendance)
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
                                                    {{$attendance->user->name}}
                                                    {{-- {{ app()->getLocale() == 'ar' ? $class->name_ar : $class->name_en }} --}}
                                                </h5>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="name align-middle white-space-nowrap py-2">
                                        <div class="d-flex d-flex align-items-center">

                                            <div class="flex-1">
                                                <h5 class="mb-0 fs--1">
                                                    {{$attendance->status}}
                                                    {{-- {{ app()->getLocale() == 'ar' ? $class->name_ar : $class->name_en }} --}}
                                                </h5>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="joined align-middle py-2">
                                        @if ($attendance->status != null)
                                            <button class="btn btn-sm btn-outline-success me-1 mb-1" type="button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#show-task-{{ $attendance->id }}">{{ __('Show') }}
                                            </button>
                                        @endif
                                    </td>


{{--
                                    <td class="name align-middle white-space-nowrap py-2">
                                        <div class="d-flex d-flex align-items-center">

                                            <div class="flex-1">
                                                <h5 class="mb-0 fs--1">
                                                    {{$class->class_name}}
                                                    {{ app()->getLocale() == 'ar' ? $class->name_ar : $class->name_en }}
                                                </h5>
                                            </div>
                                        </div>
                                    </td> --}}

                                    <td class="joined align-middle py-2">{{ $attendance->created_at }} <br>
                                        {{ interval($attendance->created_at) }} </td>
                                   @if ($attendance->trashed())
    <td class="joined align-middle py-2">
        {{ $attendance->deleted_at }} <br>
        {{ interval($attendance->deleted_at) }}
    </td>
@endif

                                    <td class="align-middle white-space-nowrap py-2 text-end">
                                       <div class="dropdown font-sans-serif position-static">
    <button class="btn btn-link text-600 btn-sm dropdown-toggle btn-reveal"
        type="button" id="customer-dropdown-{{ $attendance->id }}" data-bs-toggle="dropdown"
        data-boundary="window" aria-haspopup="true" aria-expanded="false">
        <span class="fas fa-ellipsis-h fs--1"></span>
    </button>
    <div class="dropdown-menu dropdown-menu-end border py-0"
        aria-labelledby="customer-dropdown-{{ $attendance->id }}">
        <div class="bg-white py-2">

            @if ($attendance->trashed())
                @if (auth()->user()->hasPermission('attendance-restore'))
                    <a class="dropdown-item"
                        href="{{ route('attendance.restore', ['attendance' => $attendance->id]) }}">
                        {{ __('Restore') }}
                    </a>
                @endif
            @else
                @if (auth()->user()->hasPermission('attendance-update'))
                    <a class="dropdown-item"
                        href="{{ route('attendance.edit', ['attendance' => $attendance->id]) }}">
                        {{ __('Edit') }}
                    </a>
                @endif

                @if (auth()->user()->hasPermission('attendance-delete') || auth()->user()->hasPermission('attendance-trash'))
                    <form method="POST" action="{{ route('attendance.destroy', ['attendance' => $attendance->id]) }}">
                        @csrf
                        @method('DELETE')
                        <button class="dropdown-item text-danger" type="submit">{{ __('Trash') }}</button>
                    </form>
                @endif
            @endif

        </div>
    </div>
</div>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                    @foreach ($attendances as $attendance)
                    <div class="modal fade" id="show-task-{{ $attendance->id }}" tabindex="-1" role="dialog"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 500px">
                            <div class="modal-content position-relative">
                                <div class="position-absolute top-0 end-0 mt-2 me-2 z-index-1">
                                    <button
                                        class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base"
                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-0">
                                    <div class="rounded-top-lg py-3 ps-4 pe-6 bg-light">
                                        <h4 class="mb-1" id="modalExampleDemoLabel">
                                            {{ __('Details') }}
                                        </h4>
                                    </div>
                                    <div class="p-4 pb-0">

                                        <div class="table-responsive scrollbar">
                                            <table class="table table-bordered overflow-hidden">
                                                <colgroup>
                                                    <col class="bg-soft-primary" />
                                                    <col />
                                                </colgroup>

                                                <tbody>

                                                    <tr class="btn-reveal-trigger">
                                                        <td>{{ __('Status') }}</td>
                                                        <td> {{ $attendance->status }}</td>
                                                    </tr>

                                                    <tr class="btn-reveal-trigger">
                                                        <td>{{ __('No Of Hours') }}</td>
                                                        <td>{{ $attendance->no_of_hours }}</td>
                                                    </tr>

                                                    <tr class="btn-reveal-trigger">
                                                        <td>{{ __('Absence Date') }}</td>
                                                        <td> {{ $attendance->absence_date }}</td>
                                                    </tr>

                                                    <tr class="btn-reveal-trigger">
                                                        <td>{{ __('Reasons') }}</td>
                                                        <td>{{ $attendance->reasons }}</td>
                                                    </tr>

                                                    <tr class="btn-reveal-trigger">
                                                        <td>{{ __('Comments') }}</td>
                                                        <td>{{ $attendance->comments }}</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>


                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="button"
                                        data-bs-dismiss="modal">{{ __('Close') }}</button>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                @else
                    <h3 class="p-4">{{ __('No Absences To Show') }}</h3>
                @endif
            </div>
        </div>


        <div class="card-footer d-flex align-items-center justify-content-center">
            {{ $attendances->appends(request()->query())->links() }}
        </div>

    </div>
@endsection
