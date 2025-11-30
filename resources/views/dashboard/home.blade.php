@extends('layouts.dashboard.app')

@section('adminContent')


    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
        <div class="container" data-layout="container">

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
            </div>


          <div class="content">

            @php
       
            $expenses = getExpenses(request()->from , request()->to );
            $revenues = getRevenues(request()->from , request()->to );
            $profit = $revenues - $expenses ;

        @endphp

<script>
    var expenses = {!! json_encode($expenses) !!};
    var revenues = {!! json_encode($revenues) !!};
</script>


            <div class="row g-3 mb-3">
              <div class="col-xxl-8">
                <div class="card overflow-hidden mb-3">



                </div>

              </div>


              <div class="col-md-6 col-xxl-4">
                <div class="card echart-session-by-browser-card h-100">
                  <div class="card-header d-flex flex-between-center bg-light py-2">
                    <h6 class="mb-0">Profits</h6><span ></span>
                    <h6 class="fw-normal text-700 mb-0">{{$profit}}</h6>
                    {{-- <div class="dropdown font-sans-serif btn-reveal-trigger">
                      <button class="btn btn-link text-600 btn-sm dropdown-toggle dropdown-caret-none btn-reveal" type="button" id="dropdown-session-by-browser" data-bs-toggle="dropdown" data-boundary="viewport" aria-haspopup="true" aria-expanded="false"><span class="fas fa-ellipsis-h fs--2"></span></button>
                      <div class="dropdown-menu dropdown-menu-end border py-2" aria-labelledby="dropdown-session-by-browser"><a class="dropdown-item" href="#!">View</a><a class="dropdown-item" href="#!">Export</a>
                        <div class="dropdown-divider"></div><a class="dropdown-item text-danger" href="#!">Remove</a>
                      </div>
                    </div> --}}
                  </div>
                  <div class="card-body d-flex flex-column justify-content-between py-0">
                    <div class="my-auto py-5 py-md-0">
                        <div class="echart-doughnut-chart-example" style="min-height: 320px;" data-echart-responsive="true"></div>
                    </div>
                    <div class="border-top">
                      <table class="table table-sm mb-0">
                        <tbody>

                          <tr>
                            <td class="py-3">
                              <div class="d-flex align-items-center"><img src="../assets/img/icons/" alt="" width="16" />
                                <h6 class="text-600 mb-0 ms-2">Expenses</h6>
                              </div>
                            </td>
                            <td class="py-3">
                              <div class="d-flex align-items-center"><span class="fas fa-circle fs--2 me-2 text-primary"></span>
                                <h6 class="fw-normal text-700 mb-0">{{$expenses}}</h6>
                              </div>
                            </td>
                            {{-- <td class="py-3">
                              <div class="d-flex align-items-center justify-content-end"><span class="fas fa-caret-down text-danger"></span>
                                <h6 class="fs--2 mb-0 ms-2 text-700">2.9%</h6>
                              </div>
                            </td> --}}
                          </tr>
                          <tr>
                            <td class="py-3">
                              <div class="d-flex align-items-center"><img src=".." alt="" width="16" />
                                <h6 class="text-600 mb-0 ms-2">Revenues</h6>
                              </div>
                            </td>
                            <td class="py-3">
                              <div class="d-flex align-items-center"><span class="fas fa-circle fs--2 me-2 text-danger"></span>
                                <h6 class="fw-normal text-700 mb-0">{{$revenues}}</h6>
                              </div>
                            </td>
                            {{-- <td class="py-3">
                              <div class="d-flex align-items-center justify-content-end"><span class="fas fa-caret-up text-success"></span>
                                <h6 class="fs--2 mb-0 ms-2 text-700">29.4%</h6>
                              </div>
                            </td> --}}
                          </tr>
                          <tr>



                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  {{-- <div class="card-footer bg-light py-2">
                    <div class="row flex-between-center g-0">
                      <div class="col-auto">
                        <select class="form-select form-select-sm" data-target=".echart-session-by-browser">
                          <option value="week" selected="selected">Last 7 days</option>
                          <option value="month">Last month</option>
                          <option value="year">Last Year</option>
                        </select>
                      </div>
                      <div class="col-auto"><a class="btn btn-link btn-sm px-0 fw-medium" href="#!">Browser overview<span class="fas fa-chevron-right ms-1 fs--2"></span></a></div>
                    </div>
                  </div> --}}
                </div>
              </div>


          </div>

        </div>
      </main>
      <!-- ===============================================-->
      <!--    End of Main Content-->
      <!-- ===============================================-->



@endsection
