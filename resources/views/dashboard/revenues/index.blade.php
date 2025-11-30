@extends('layouts.dashboard.app')

@section('adminContent')
    <div class="card mb-3" id="customersTable"
        data-list='{"valueNames":["name","email","phone","address","joined"],"page":10,"pagination":true}'>
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">
                        @if ($revenues->count() > 0 && $revenues[0]->trashed())
                            {{ __('Revenues Trash') }}
                        @else
                            {{ __('Revenues') }}
                        @endif
                    </h5>
                </div>
                <div class="col-8 col-sm-auto text-end ps-2">
                    <div class="d-none" id="table-customers-actions">
                        <div class="d-flex">

                            <select class="form-select form-select-sm" aria-label="Bulk actions">
                                <option selected="">{{ __('Bulk actions') }}</option>
                                <option value="Delete">{{ __('Delete') }}</option>
                                <option value="Archive">{{ __('Archive') }}</option>
                            </select>
                            <button class="btn btn-falcon-default btn-sm ms-2" type="button">{{ __('Apply') }}</button>
                        </div>
                    </div>
                    <div id="table-customers-replace-element">
                        <form style="display: inline-block" action="">

                            <div class="d-inline-block">
                                {{-- <label class="form-label" for="from">{{ __('From') }}</label> --}}
                                <input type="date" id="from" name="from" class="form-control form-select-sm"
                                    value="{{ request()->from }}">
                            </div>

                            <div class="d-inline-block">
                                {{-- <label class="form-label" for="to">{{ __('To') }}</label> --}}
                                <input type="date" id="to" name="to"
                                    class="form-control form-select-sm sonoo-search" value="{{ request()->to }}">
                            </div>

                        </form>
                        @if (auth()->user()->hasPermission('revenues-create'))
                            <a href="{{ route('revenues.create') }}" class="btn btn-falcon-default btn-sm"
                                type="button"><span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span><span
                                    class="d-none d-sm-inline-block ms-1">{{ __('New') }}</span></a>
                        @endif
                        {{-- <a href="{{ route('revenues.trashed') }}" class="btn btn-falcon-default btn-sm" type="button"><span
                                class="fas fa-trash" data-fa-transform="shrink-3 down-2"></span><span
                                class="d-none d-sm-inline-block ms-1">{{ __('Trash') }}</span></a> --}}
                        {{-- <button class="btn btn-falcon-default btn-sm" type="button"><span class="fas fa-external-link-alt"
                                data-fa-transform="shrink-3 down-2"></span><span
                                class="d-none d-sm-inline-block ms-1">{{ __('Export') }}</span></button> --}}
                    </div>

                    <div id="table-customers-replace-element">


                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive scrollbar">
                @if ($revenues->count() > 0)
                    <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                        <thead class="bg-200 text-900">
                            <tr>
                                {{-- <th>
                                    <div class="form-check fs-0 mb-0 d-flex align-items-center">
                                        <input class="form-check-input" id="checkbox-bulk-customers-select" type="checkbox"
                                            data-bulk-select='{"body":"table-customers-body","actions":"table-customers-actions","replacedElement":"table-customers-replace-element"}' />
                                    </div>
                                </th> --}}
                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="name">{{ __('Type') }}
                                </th>

                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="tech_update">
                                    {{ __('Revenue Details') }}
                                </th>

                                <th class="sort pe-1 align-middle white-space-nowrap" style="min-width: 100px;"
                                    data-sort="joined">{{ __('Created at') }}</th>
                                @if ($revenues->count() > 0 && $revenues[0]->trashed())
                                    <th class="sort pe-1 align-middle white-space-nowrap" style="min-width: 100px;"
                                        data-sort="joined">{{ __('Deleted at') }}</th>
                                @endif
                                <th class="align-middle no-sort"></th>
                            </tr>
                        </thead>
                        <tbody class="list" id="table-customers-body">
                            @foreach ($revenues as $revenue)
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
                                                    {{ ucwords(str_replace('_', ' ', $revenue->revenue_type)) }}
                                                    {{-- {{ app()->getLocale() == 'ar' ? $class->name_ar : $class->name_en }} --}}
                                                </h5>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="joined align-middle py-2">
                                        @if ($revenue->id != null)
                                            <button class="btn btn-sm btn-outline-success me-1 mb-1" type="button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#show-task-{{ $revenue->id }}">{{ __('show') }}
                                            </button>
                                        @endif
                                    </td>


                                    <td class="joined align-middle py-2">{{ $revenue->created_at }} <br>
                                        {{ interval($revenue->created_at) }} </td>
                                    @if ($revenue->trashed())
                                        <td class="joined align-middle py-2">{{ $revenue->deleted_at }} <br>
                                            {{ interval($revenue->deleted_at) }} </td>
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
                                                    @if ($revenue->trashed() && auth()->user()->hasPermission('revenues-restore'))
                                                        <a class="dropdown-item"
                                                            href="{{ route('revenues.restore', ['revenue' => $revenue->id]) }}">{{ __('Restore') }}</a>
                                                    @elseif(auth()->user()->hasPermission('revenues-update'))
                                                        <a class="dropdown-item"
                                                            href="{{ route('revenues.edit', ['revenue' => $revenue->id]) }}">{{ __('Edit') }}</a>
                                                    @endif
                                                    @if (auth()->user()->hasPermission('revenues-delete') || auth()->user()->hasPermission('revenues-trash'))
                                                        {{-- <form method="POST"
                                                            action="{{ route('revenues.destroy', ['revenue' => $revenue->id]) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="dropdown-item text-danger"
                                                                type="submit">{{ $revenue->trashed() ? __('Delete') : __('Trash') }}</button>
                                                        </form> --}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>


                    @foreach ($revenues as $revenue)
                        <div class="modal fade" id="show-task-{{ $revenue->id }}" tabindex="-1" role="dialog"
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
                                                            <td>{{ __('Type') }}</td>
                                                            <td>{{ ucwords(str_replace('_', ' ', $revenue->revenue_type)) }}
                                                            </td>
                                                        </tr>
                                                            <tr class="btn-reveal-trigger">
                                                            <td>{{ __('Student Name') }}</td>
                                                            <td> {{ $revenue->user->name }}</td>
                                                        </tr>
                                                        <tr class="btn-reveal-trigger">
                                                            <td>{{ __('Expense amount') }}</td>
                                                            <td> {{ $revenue->revenue_amount }}</td>
                                                        </tr>

                                                        <tr class="btn-reveal-trigger">
                                                            <td>{{ __('Notes') }}</td>
                                                            <td>{{ $revenue->notes }}</td>
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
                    <h3 class="p-4">{{ __('No revenues to Show') }}</h3>
                @endif
            </div>
        </div>


        <div class="card-footer d-flex align-items-center justify-content-center">
            {{ $revenues->appends(request()->query())->links() }}
        </div>

    </div>
@endsection
