@extends('layouts.dashboard.app')

@section('adminContent')

<div class="card mb-3">

    <div class="card-header">
        <form method="GET" class="row g-2">

            <div class="col-md-3">
                <label>Year</label>
                <select name="year" class="form-select form-select-sm">
                    @for ($y = now()->year - 5; $y <= now()->year + 1; $y++)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-3">
                <label>Month</label>
                <select name="month" class="form-select form-select-sm">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-sm btn-primary w-100">Filter</button>
            </div>

        </form>
    </div>

    <div class="card-body p-0">

        <div class="table-responsive scrollbar">

            <table class="table table-sm table-striped fs--1 mb-0">
                <thead class="bg-200 text-900">
                    <tr>
                        <th>Name</th>
                        <th>Salary</th>
                        <th>Daily Salary</th>
                        <th>Days Off</th>
                        <th>Hours Off</th>
                        <th>Net Salary</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($salaryCards as $row)
                        <tr>
                            <td>{{ $row['teacher']->user->name }}</td>
                            <td>{{ number_format($row['base_salary'], 2) }}</td>
                            <td>{{ number_format($row['daily_salary'], 2) }}</td>
                            <td>{{ $row['days_off'] }}</td>
                            <td>{{ $row['hours_off'] }}</td>
                            <td class="text-success fw-bold">
                                {{ number_format($row['net_salary'], 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>

    </div>
</div>

@endsection
