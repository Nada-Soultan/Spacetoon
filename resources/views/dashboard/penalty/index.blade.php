@extends('layouts.dashboard.app')

@section('adminContent')
    <div class="card mb-3" id="customersTable"
        data-list='{"valueNames":["name","email","phone","address","joined"],"page":10,"pagination":true}'>
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">
                        @if ($penalties->count() > 0 && $penalties[0]->trashed())
                            {{ __('Penalties Trash') }}
                        @else
                            {{ __('Penalties') }}
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

                        @if (auth()->user()->hasPermission('penalty-create'))
                            <a href="{{ route('penalty.create') }}" class="btn btn-falcon-default btn-sm">
                                <span class="fas fa-plus"></span>
                                <span class="d-none d-sm-inline-block ms-1">{{ __('New') }}</span>
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive scrollbar">
                @if ($penalties->count() > 0)
                    <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th class="joined align-middle py-2">{{ __('Type') }}</th>
                                <th class="joined align-middle py-2">{{ __('Teacher Name') }}</th>
                                <th class="joined align-middle py-2">{{ __('Penalty Details') }}</th>
                                <th class="joined align-middle py-2">{{ __('Created at') }}</th>
                                @if ($penalties->count() > 0 && $penalties[0]->trashed())
                                    <th class="sort pe-1 align-middle white-space-nowrap">{{ __('Deleted at') }}</th>
                                @endif
                                <th class="align-middle no-sort"></th>
                            </tr>
                        </thead>

                        <tbody class="list" id="table-customers-body">
                            @foreach ($penalties as $penalty)
                                <tr class="btn-reveal-trigger">

                                    <td class="name align-middle">
                                        <h5 class="mb-0 fs--1">
                                            {{ ucwords(str_replace('_', ' ', $penalty->type)) }}
                                        </h5>
                                    </td>
<td class="name align-middle">
                                        <h5 class="mb-0 fs--1">
                                            {{ $penalty->user->name ?? '' }}
                                        </h5>
                                    </td>
                                    <td class="joined align-middle">
                                        <button class="btn btn-sm btn-outline-success me-1 mb-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#show-penalty-{{ $penalty->id }}">
                                            {{ __('Show') }}
                                        </button>
                                    </td>

                                    <td class="joined align-middle">
                                        {{ $penalty->created_at }} <br>
                                        {{ interval($penalty->created_at) }}
                                    </td>

                                    @if ($penalty->trashed())
                                        <td class="joined align-middle">
                                            {{ $penalty->deleted_at }} <br>
                                            {{ interval($penalty->deleted_at) }}
                                        </td>
                                    @endif

                                    <td class="align-middle text-end">
                                        <div class="dropdown position-static">
                                            <button class="btn btn-link text-600 btn-sm dropdown-toggle"
                                                data-bs-toggle="dropdown">
                                                <span class="fas fa-ellipsis-h fs--1"></span>
                                            </button>

                                            <div class="dropdown-menu dropdown-menu-end">
                                                <div class="bg-white ">
                                                    @if ($penalty->trashed() && auth()->user()->hasPermission('penalty-restore'))
                                                        <a class="dropdown-item"
                                                            href="{{ route('penalty.restore', $penalty->id) }}">
                                                            {{ __('Restore') }}
                                                        </a>
                                                    @elseif(auth()->user()->hasPermission('penalty-update'))
                                                        <a class="dropdown-item"
                                                            href="{{ route('penalty.edit', $penalty->id) }}">
                                                            {{ __('Edit') }}
                                                        </a>
                                                    @endif

                                                    @if (auth()->user()->hasPermission('penalty-delete'))
                                                        {{-- Delete/Trash button can be added here --}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- MODALS --}}
                    @foreach ($penalties as $penalty)
                        <div class="modal fade" id="show-penalty-{{ $penalty->id }}">
                            <div class="modal-dialog modal-dialog-centered" style="max-width: 500px">
                                <div class="modal-content">

                                    <div class="modal-body p-0">
                                        <div class="rounded-top-lg py-3 ps-4 pe-6 bg-light">
                                            <h4 class="mb-1">{{ __('Details') }}</h4>
                                        </div>

                                        <div class="p-4 pb-0">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <td>{{ __('Type') }}</td>
                                                        <td>{{ ucwords(str_replace('_', ' ', $penalty->type)) }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td>{{ __('Teacher Name') }}</td>
                                                        <td>{{ $penalty->user->name ?? 'N/A' }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td>{{ __('Amount') }}</td>
                                                        <td>{{ $penalty->amount }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td>{{ __('Notes') }}</td>
                                                        <td>{{ $penalty->notes }}</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">
                                            {{ __('Close') }}
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <h3 class="p-4">{{ __('No penalties to show') }}</h3>
                @endif
            </div>
        </div>

        <div class="card-footer d-flex align-items-center justify-content-center">
            {{ $penalties->appends(request()->query())->links() }}
        </div>

    </div>
@endsection
