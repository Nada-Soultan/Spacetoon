@extends('layouts.dashboard.app')

@section('adminContent')
    <div class="card mb-3" id="customersTable"
        data-list='{"valueNames":["name","email","phone","address","joined"],"page":10,"pagination":true}'>
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">
                        @if ($classes->count() > 0 && $classes[0]->trashed())
                            {{ __('Classes Trash') }}
                        @else
                            {{ __('Classes') }}
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
                        @if (auth()->user()->hasPermission('classes-create'))
                            <a href="{{ route('classes.create') }}" class="btn btn-falcon-default btn-sm"
                                type="button"><span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span><span
                                    class="d-none d-sm-inline-block ms-1">{{ __('New') }}</span></a>
                        @endif
                        <a href="{{ route('classes.trashed') }}" class="btn btn-falcon-default btn-sm" type="button"><span
                                class="fas fa-trash" data-fa-transform="shrink-3 down-2"></span><span
                                class="d-none d-sm-inline-block ms-1">{{ __('Trash') }}</span></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive scrollbar">
                @if ($classes->count() > 0)
                    <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="name">{{ __('Name') }}
                                </th>
                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="name">
                                    {{ __('Teacher Name') }}
                                </th>
                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="tech_update">
                                    {{ __('Class Details') }}
                                </th>
                                <th class="sort pe-1 align-middle white-space-nowrap" style="min-width: 100px;"
                                    data-sort="joined">{{ __('Created at') }}</th>
                                @if ($classes->count() > 0 && $classes[0]->trashed())
                                    <th class="sort pe-1 align-middle white-space-nowrap" style="min-width: 100px;"
                                        data-sort="joined">{{ __('Deleted at') }}</th>
                                @endif
                                <th class="align-middle no-sort"></th>
                            </tr>
                        </thead>
                        <tbody class="list" id="table-customers-body">
                            @foreach ($classes as $class)
                                <tr class="btn-reveal-trigger">
                                    <td class="name align-middle white-space-nowrap py-2">
                                        <div class="d-flex d-flex align-items-center">
                                            <div class="flex-1">
                                                <h5 class="mb-0 fs--1">
                                                    {{ $class->class_name ?? 'N/A' }}
                                                </h5>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="name align-middle white-space-nowrap py-2">
                                        <div class="d-flex d-flex align-items-center">
                                            <div class="flex-1">
                                                <h5 class="mb-0 fs--1">
                                                    {{ $class->user?->name ?? __('No Teacher Assigned') }}
                                                </h5>
                                                @if(!$class->user)
                                                    <small class="text-danger">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                        {{ __('Teacher deleted or not found') }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <td class="joined align-middle py-2">
                                        @if ($class->class_name != null)
                                            <button class="btn btn-sm btn-outline-success me-1 mb-1" type="button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#show-task-{{ $class->id }}">{{ __('show') }}
                                            </button>
                                        @endif
                                    </td>

                                    <td class="joined align-middle py-2">{{ $class->created_at }} <br>
                                        {{ interval($class->created_at) }} </td>
                                    @if ($class->trashed())
                                        <td class="joined align-middle py-2">{{ $class->deleted_at }} <br>
                                            {{ interval($class->deleted_at) }} </td>
                                    @endif
                                    <td class="align-middle white-space-nowrap py-2 text-end">
                                        <div class="dropdown font-sans-serif position-static">
                                            <button class="btn btn-link text-600 btn-sm dropdown-toggle btn-reveal"
                                                type="button" id="customer-dropdown-{{ $class->id }}" data-bs-toggle="dropdown"
                                                data-boundary="window" aria-haspopup="true" aria-expanded="false"><span
                                                    class="fas fa-ellipsis-h fs--1"></span></button>
                                            <div class="dropdown-menu dropdown-menu-end border py-0"
                                                aria-labelledby="customer-dropdown-{{ $class->id }}">
                                                <div class="bg-white py-2">
                                                    @if ($class->trashed() && auth()->user()->hasPermission('classes-restore'))
                                                        <a class="dropdown-item"
                                                            href="{{ route('classes.restore', ['class' => $class->id]) }}">{{ __('Restore') }}</a>
                                                    @elseif(auth()->user()->hasPermission('classes-update'))
                                                        <a class="dropdown-item"
                                                            href="{{ route('classes.edit', ['class' => $class->id]) }}">{{ __('Edit') }}</a>
                                                    @endif
                                                    @if (auth()->user()->hasPermission('classes-delete') || auth()->user()->hasPermission('classes-trash'))
                                                        <form method="POST"
                                                            action="{{ route('classes.destroy', ['class' => $class->id]) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="dropdown-item text-danger"
                                                                type="submit">{{ $class->trashed() ? __('Delete') : __('Trash') }}</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @foreach ($classes as $class)
                        <div class="modal fade" id="show-task-{{ $class->id }}" tabindex="-1" role="dialog"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 500px">
                                <div class="modal-content position-relative">
                                    <div class="position-absolute top-0 end-0 mt-2 me-2 z-index-1">
                                        <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base"
                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-0">
                                        <div class="rounded-top-lg py-3 ps-4 pe-6 bg-light">
                                            <h4 class="mb-1" id="modalExampleDemoLabel">
                                                {{ __('Class Details') }}
                                            </h4>
                                        </div>
                                        <div class="p-4 pb-0">
                                            <div class="table-responsive scrollbar">
                                                <table class="table table-bordered overflow-hidden">
                                                    <colgroup>
                                                        <col class="bg-soft-primary" style="width: 40%;" />
                                                        <col />
                                                    </colgroup>
                                                    <tbody>
                                                        <tr>
                                                            <td><strong>{{ __('Class Name') }}</strong></td>
                                                            <td>{{ $class->class_name ?? 'N/A' }}</td>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>{{ __('Class Stage') }}</strong></td>
                                                            <td>{{ $class->class_stage ?? 'N/A' }}</td>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>{{ __('Teacher Name') }}</strong></td>
                                                            <td>
                                                                @if($class->user)
                                                                    {{ $class->user->name }}
                                                                @else
                                                                    <span class="text-danger">
                                                                        <i class="fas fa-exclamation-triangle"></i>
                                                                        {{ __('No Teacher Assigned') }}
                                                                    </span>
                                                                @endif
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>{{ __('No Of Students') }}</strong></td>
                                                            <td>{{ $class->students_no ?? 0 }}</td>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>{{ __('Students') }}</strong></td>
                                                            <td>
                                                                @if($class->students && $class->students->count() > 0)
                                                                    @foreach ($class->students as $index => $student)
                                                                        <div class="mb-1">
                                                                            {{ $index + 1 }}.
                                                                            @if($student->user)
                                                                                {{ $student->user->name }}
                                                                            @else
                                                                                <span class="text-muted">
                                                                                    {{ __('Student user deleted') }}
                                                                                </span>
                                                                            @endif
                                                                        </div>
                                                                    @endforeach
                                                                @else
                                                                    <span class="text-muted">
                                                                        {{ __('No students enrolled') }}
                                                                    </span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        @if(auth()->user()->hasPermission('classes-update'))
                                            <a href="{{ route('classes.edit', ['class' => $class->id]) }}" 
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit me-1"></i>{{ __('Edit Class') }}
                                            </a>
                                        @endif
                                        <button class="btn btn-secondary btn-sm" type="button"
                                            data-bs-dismiss="modal">{{ __('Close') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <h3 class="p-4">{{ __('No classes to Show') }}</h3>
                @endif
            </div>
        </div>

        <div class="card-footer d-flex align-items-center justify-content-center">
            {{ $classes->appends(request()->query())->links() }}
        </div>
    </div>
@endsection