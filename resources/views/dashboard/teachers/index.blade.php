@extends('layouts.dashboard.app')

@section('adminContent')
    <div class="card mb-3" id="customersTable"
        data-list='{"valueNames":["name","email","phone","address","joined"],"page":10,"pagination":true}'>
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">
                        @if ($teachers->count() > 0 && $teachers[0]->trashed())
                            {{ __('Teachers Trash') }}
                        @else
                            {{ __('Teachers') }}
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

                        @if (auth()->user()->hasPermission('teachers-create'))
                            <a href="{{ route('teachers.create') }}" class="btn btn-falcon-default btn-sm"
                                type="button"><span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span><span
                                    class="d-none d-sm-inline-block ms-1">{{ __('New') }}</span></a>
                        @endif
                        {{-- <a href="{{ route('teachers.trashed') }}" class="btn btn-falcon-default btn-sm" type="button"><span
                                class="fas fa-trash" data-fa-transform="shrink-3 down-2"></span><span
                                class="d-none d-sm-inline-block ms-1">{{ __('Trash') }}</span></a>
                        <button class="btn btn-falcon-default btn-sm" type="button"><span class="fas fa-external-link-alt"
                                data-fa-transform="shrink-3 down-2"></span><span
                                class="d-none d-sm-inline-block ms-1">{{ __('Export') }}</span></button> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive scrollbar">
                @if ($teachers->count() > 0)
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
                                    {{ __('Teacher Details') }}
                                </th>


                                <th class="sort pe-1 align-middle white-space-nowrap" style="min-width: 100px;"
                                    data-sort="joined">{{ __('Created at') }}</th>
                                @if ($teachers->count() > 0 && $teachers[0]->trashed())
                                    <th class="sort pe-1 align-middle white-space-nowrap" style="min-width: 100px;"
                                        data-sort="joined">{{ __('Deleted at') }}</th>
                                @endif
                                <th class="align-middle no-sort"></th>
                            </tr>
                        </thead>
                        <tbody class="list" id="table-customers-body">
                            @foreach ($teachers as $teacher)
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
                                                    {{$teacher->user->name?? 'N/A'}}
                                                    {{-- {{ app()->getLocale() == 'ar' ? $class->name_ar : $class->name_en }} --}}
                                                </h5>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="joined align-middle py-2">
                                        @if ($teacher->salary != null)
                                            <button class="btn btn-sm btn-outline-success me-1 mb-1" type="button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#show-task-{{ $teacher->id }}">{{ __('show') }}
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

                                    <td class="joined align-middle py-2">{{ $teacher->created_at }} <br>
                                        {{ interval($teacher->created_at) }} </td>
                                    @if ($teacher->trashed())
                                        <td class="joined align-middle py-2">{{ $teacher->deleted_at }} <br>
                                            {{ interval($teacher->deleted_at) }} </td>
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
                                                    @if (
                                                        $teacher->trashed() &&
                                                            auth()->user()->hasPermission('teachers-restore'))
                                                        <a class="dropdown-item"
                                                            href="{{ route('teachers.restore', ['teacher' => $teacher->id]) }}">{{ __('Restore') }}</a>
                                                    @elseif(auth()->user()->hasPermission('teachers-update'))
                                                        <a class="dropdown-item"
                                                            href="{{ route('teachers.edit', ['teacher' => $teacher->id]) }}">{{ __('Edit') }}</a>
                                                    @endif
                                                    {{-- @if (auth()->user()->hasPermission('teachers-delete') ||
                                                            auth()->user()->hasPermission('teachers-trash'))
                                                        <form method="POST"
                                                            action="{{ route('teachers.destroy', ['teacher' => $teacher->id]) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="dropdown-item text-danger"
                                                                type="submit">{{ $teacher->trashed() ? __('Delete') : __('Trash') }}</button>
                                                        </form>
                                                    @endif --}}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                    @foreach ($teachers as $teacher)
                    <div class="modal fade" id="show-task-{{ $teacher->id }}" tabindex="-1" role="dialog"
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
                                                        <td>{{ __('Salary') }}</td>
                                                        <td> {{ $teacher->salary }}</td>
                                                    </tr>

                                                    <tr class="btn-reveal-trigger">
                                                        <td>{{ __('Extra Courses') }}</td>
                                                        <td> {{ $teacher->extra_courses }}</td>
                                                    </tr>

                                                    <tr class="btn-reveal-trigger">
                                                        <td>{{ __('Fees Of Courses') }}</td>
                                                        <td>{{ $teacher->fees_of_courses }}</td>
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
                    <h3 class="p-4">{{ __('No teachers to Show') }}</h3>
                @endif
            </div>
        </div>


        <div class="card-footer d-flex align-items-center justify-content-center">
            {{ $teachers->appends(request()->query())->links() }}
        </div>

    </div>
@endsection
