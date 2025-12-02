@extends('layouts.dashboard.app')

@section('adminContent')

<main class="main" id="top">
    <div class="container" data-layout="container">

        <div id="table-customers-replace-element">
            <form style="display: inline-block" action="" method="GET">

                <select name="month" class="form-control d-inline-block" style="width:150px" onchange="this.form.submit()">
                    <option value="">Select Month</option>
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request()->month == $m ? 'selected' : '' }}>
                            {{ Carbon\Carbon::create()->month($m)->format('F') }}
                        </option>
                    @endfor
                </select>

                <select name="year" class="form-control d-inline-block" style="width:150px" onchange="this.form.submit()">
                    @for ($y = 2025; $y <= now()->year; $y++)
                        <option value="{{ $y }}" {{ request()->year == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>

            </form>
        </div>

        <div class="content">

            @php
                $expenses = getExpenses(request()->from, request()->to);
                $revenues = getRevenues(request()->from, request()->to);
                $profit = $revenues - $expenses;
            @endphp

            <script>
                var expenses = {!! json_encode($expenses) !!};
                var revenues = {!! json_encode($revenues) !!};
            </script>

            <div class="row g-3 my-3">
                <div class="col-md-6 col-xxl-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between bg-light py-2">
                            <h6 class="mb-0">Profits</h6>
                            <h6 class="fw-normal text-700 mb-0">{{ $profit }}</h6>
                        </div>

                        <div class="card-body py-0">
                            <div class="my-auto py-5">
                                <div class="echart-doughnut-chart-example" style="min-height: 320px;" data-echart-responsive="true"></div>
                            </div>

                            <div class="border-top">
                                <table class="table table-sm mb-0">
                                    <tbody>

                                        <tr>
                                            <td class="py-3">
                                                <div class="d-flex align-items-center">
                                                    <h6 class="text-600 mb-0 ms-2">Expenses</h6>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="fas fa-circle fs--2 me-2 text-primary"></span>
                                                    <h6 class="fw-normal text-700 mb-0">{{ $expenses }}</h6>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="py-3">
                                                <div class="d-flex align-items-center">
                                                    <h6 class="text-600 mb-0 ms-2">Revenues</h6>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="fas fa-circle fs--2 me-2 text-danger"></span>
                                                    <h6 class="fw-normal text-700 mb-0">{{ $revenues }}</h6>
                                                </div>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
</main>

@endsection
