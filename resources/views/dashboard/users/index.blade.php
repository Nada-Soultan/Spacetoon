@extends('layouts.dashboard.app')

@section('adminContent')

    <div class="card mb-3" id="customersTable"
        data-list='{"valueNames":["name","email","phone","address","joined"],"page":10,"pagination":true}'>
        <div class="card-header">
            <div class="row flex-between-center">
                <div class="col-4 col-sm-auto d-flex align-items-center pe-0">
                    <h5 class="fs-0 mb-0 text-nowrap py-2 py-xl-0">
                        @if ($users->count() > 0 && $users[0]->trashed())
                            {{ __('Users trash') }}
                        @else
                            {{ __('Users') }}
                        @endif
                    </h5>
                </div>
                <div class="col-8 col-sm-auto text-end ps-2">
                    <div class="d-none" id="table-customers-actions">
                        <div class="d-flex">
                            <select name="action" class="form-select form-select-sm" required>
                                <option value="">{{ __('Bulk actions') }}</option>
                                <option value="trash">{{ __('Move to Trash') }}</option>
                                <option value="block">{{ __('Block Users') }}</option>
                                <option value="unblock">{{ __('Unblock Users') }}</option>
                            </select>
                            <button class="btn btn-falcon-default btn-sm ms-2" type="button">{{ __('Apply') }}</button>
                        </div>
                    </div>
                    <div id="table-customers-replace-element">
                        <form style="display: inline-block" action="">

                            <div class="d-inline-block">
                                <input type="date" id="from" name="from" class="form-control form-select-sm"
                                    value="{{ request()->from }}">
                            </div>

                            <div class="d-inline-block">
                                <input type="date" id="to" name="to"
                                    class="form-control form-select-sm sonoo-search" value="{{ request()->to }}">
                            </div>

                            <div class="d-inline-block">
                                <select name="role_id" class="form-select form-select-sm sonoo-search"
                                    id="autoSizingSelect">
                                    <option value="" selected>{{ __('All Roles') }}</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ request()->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}
                                            {{ app()->getLocale() == 'ar' ? $role->name_ar : $role->name_en }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-inline-block">
                                <select name="status" class="form-select form-select-sm sonoo-search"
                                    id="autoSizingSelect">
                                    <option value="" selected>{{ __('All Status') }}</option>
                                    <option value="active" {{ request()->status == 'active' ? 'selected' : '' }}>
                                        {{ __('active') }}</option>
                                    <option value="inactive" {{ request()->status == 'inactive' ? 'selected' : '' }}>
                                        {{ __('inactive') }}</option>
                                    <option value="1" {{ request()->status == '1' ? 'selected' : '' }}>
                                        {{ __('blocked') }}</option>
                                </select>
                            </div>

                        </form>
                        @if (auth()->user()->hasPermission('users-create'))
                            <a href="{{ route('users.create') }}" class="btn btn-falcon-default btn-sm"
                                type="button"><span class="fas fa-plus" data-fa-transform="shrink-3 down-2"></span><span
                                    class="d-none d-sm-inline-block ms-1">{{ __('New') }}</span></a>
                        @endif
                        <a href="{{ route('users.trashed') }}" class="btn btn-falcon-default btn-sm" type="button"><span
                                class="fas fa-trash" data-fa-transform="shrink-3 down-2"></span><span
                                class="d-none d-sm-inline-block ms-1">{{ __('Trash') }}</span></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive scrollbar">
                @if ($users->count() > 0)
                    <table class="table table-sm table-striped fs--1 mb-0 overflow-hidden">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="name">{{ __('Name') }}
                                </th>
                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="phone">{{ __('Phone') }}
                                </th>
                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="email">
                                    {{ __('User Type') }}</th>
                                <th class="sort pe-1 align-middle white-space-nowrap" data-sort="email">
                                    {{ __('Status') }}</th>
                                <th class="sort pe-1 align-middle white-space-nowrap" style="min-width: 100px;"
                                    data-sort="joined">{{ __('Joined') }}</th>
                                @if ($users->count() > 0 && $users[0]->trashed())
                                    <th class="sort pe-1 align-middle white-space-nowrap" style="min-width: 100px;"
                                        data-sort="joined">{{ __('Deleted at') }}</th>
                                @endif
                                <th class="align-middle no-sort"></th>
                            </tr>
                        </thead>
                        <tbody class="list" id="table-customers-body">
                            @foreach ($users as $user)
                                <tr class="btn-reveal-trigger">
                                    <td class="name align-middle white-space-nowrap py-2">
                                        <div class="d-flex d-flex align-items-center">
                                            <div class="avatar avatar-xl me-2" style="cursor: pointer;" 
                                                 data-bs-toggle="modal" 
                                                 data-bs-target="#photo-modal-{{ $user->id }}"
                                                 title="{{ __('Click to view larger photo') }}">
                                                <img class="rounded-circle"
                                                    src="{{ asset('storage/images/users/' . $user->profile) }}"
                                                    alt="{{ $user->name }}"
                                                    onerror="this.src='{{ asset('assets/img/avatar/avatarmale.png') }}'" />
                                            </div>
                                            <div class="flex-1">
                                                <h5 class="mb-0 fs--1">{{ $user->name }}</h5>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="phone align-middle white-space-nowrap py-2"><a
                                            href="tel:{{ $user->phone }}">{{ $user->phone }}</a></td>
                                    <td class="address align-middle white-space-nowrap py-2">
                                        @foreach ($user->roles as $role)
                                            <div style="display: inline-block">
                                                <span class="badge badge-soft-primary">{{ $role->name }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </td>
                                    <td class="phone align-middle white-space-nowrap py-2">
                                        @if (hasVerifiedPhone($user))
                                            <span class='badge badge-soft-success'>{{ __('Active') }}</span>
                                        @elseif (!hasVerifiedPhone($user))
                                            <span class='badge badge-soft-danger'>{{ __('Inactive') }}</span>
                                        @endif
                                        @if ($user->status == 1)
                                            <span class='badge badge-soft-danger'>{{ __('blocked') }}</span>
                                        @endif
                                    </td>
                                    <td class="joined align-middle py-2">{{ $user->created_at }} <br>
                                        {{ interval($user->created_at) }} </td>
                                    @if ($user->trashed())
                                        <td class="joined align-middle py-2">{{ $user->deleted_at }} <br>
                                            {{ interval($user->deleted_at) }} </td>
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
                                                    @if ($user->trashed() && auth()->user()->hasPermission('users-restore'))
                                                        <a class="dropdown-item"
                                                            href="{{ route('users.restore', ['user' => $user->id]) }}">{{ __('Restore') }}</a>
                                                    @elseif(auth()->user()->hasPermission('users-update'))
                                                        <a class="dropdown-item"
                                                            href="{{ route('users.edit', ['user' => $user->id]) }}">{{ __('Edit') }}</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('users.activate', ['user' => $user->id]) }}">{{ hasVerifiedPhone($user) ? __('Deactivate') : __('Activate') }}</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('users.block', ['user' => $user->id]) }}">{{ $user->status == 0 ? __('Block') : __('Unblock') }}</a>
                                                        <a href="" class="dropdown-item" data-bs-toggle="modal"
                                                            data-bs-target="#bonus-modal-{{ $user->id }}">{{ __('Add bonus') }}</a>
                                                    @endif
                                                    @if (auth()->user()->hasPermission('users-delete') || auth()->user()->hasPermission('users-trash'))
                                                        <form method="POST"
                                                            action="{{ route('users.destroy', ['user' => $user->id]) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="dropdown-item text-danger"
                                                                type="submit">{{ $user->trashed() ? __('Delete') : __('Trash') }}</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Photo Preview Modal -->
                                <div class="modal fade" id="photo-modal-{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">{{ $user->name }}</h5>
                                                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center p-4">
                                                <img src="{{ asset('storage/images/users/' . $user->profile) }}" 
                                                     class="img-fluid rounded" 
                                                     alt="{{ $user->name }}"
                                                     style="max-height: 500px; width: auto;"
                                                     onerror="this.src='{{ asset('assets/img/avatar/avatarmale.png') }}'">
                                                <div class="mt-3">
                                                    <p class="mb-1"><strong>{{ __('Name') }}:</strong> {{ $user->name }}</p>
                                                    <p class="mb-1"><strong>{{ __('Email') }}:</strong> {{ $user->email }}</p>
                                                    <p class="mb-1"><strong>{{ __('Phone') }}:</strong> {{ $user->phone }}</p>
                                                    <p class="mb-1">
                                                        <strong>{{ __('Roles') }}:</strong>
                                                        @foreach ($user->roles as $role)
                                                            <span class="badge badge-soft-primary">{{ $role->name }}</span>
                                                        @endforeach
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                @if(auth()->user()->hasPermission('users-update'))
                                                    <a href="{{ route('users.edit', ['user' => $user->id]) }}" 
                                                       class="btn btn-primary btn-sm">
                                                        <i class="fas fa-edit me-1"></i>{{ __('Edit User') }}
                                                    </a>
                                                @endif
                                                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                                    {{ __('Close') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <h3 class="p-4">{{ __('No Users To Show') }}</h3>
                @endif
            </div>
        </div>

        <div class="card-footer d-flex align-items-center justify-content-center">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>

    <style>
        .avatar img:hover {
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }
        
        .avatar[data-bs-toggle="modal"] {
            position: relative;
        }
        
        .avatar[data-bs-toggle="modal"]::after {
            content: '\f002'; /* Font Awesome search icon */
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            background: rgba(0, 0, 0, 0.6);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            font-size: 12px;
        }
        
        .avatar[data-bs-toggle="modal"]:hover::after {
            opacity: 1;
        }
    </style>
@endsection