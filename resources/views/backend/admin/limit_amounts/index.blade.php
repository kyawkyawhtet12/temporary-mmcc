@extends('layouts.master')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- start page title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Limit Amount</h4>

                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                <li class="breadcrumb-item active">Limit Amount</li>
                            </ol>
                        </div>

                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                          <h4 class="card-title">Limit Amount </h4>
                          <p class="card-description">
                            Click to change<code>minimum and maximum amount</code>
                          </p>
                          <div class="row">
                            <div class="col-12">
                              <div class="table-responsive">
                                <table class="table table-bordered nowrap">
                                  <thead>
                                    <tr class="bg-primary text-white" role="row">
                                        <th>Minimum Amount</th>
                                        <th>Maximum Amount</th>
                                        <th>Updated Date</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @foreach($limit_amounts as $key => $amount)
                                    <tr>
                                      <td>
                                        <form id="editable-form" class="editable-form">
                                          <div class="form-group row">
                                            <div class="col-6 col-lg-8 d-flex align-items-center">
                                              <a href="#" id="min_amount" data-type="number" data-pk="{{ $amount->id }}">{{ $amount->min_amount }}</a>
                                            </div>
                                          </div>
                                        </form>
                                      </td>
                                      <td>
                                        <form id="editable-form" class="editable-form">
                                          <div class="form-group row">
                                            <div class="col-6 col-lg-8 d-flex align-items-center">
                                              <a href="#" id="max_amount" data-type="number" data-pk="{{ $amount->id }}">{{ $amount->max_amount }}</a>
                                            </div>
                                          </div>
                                        </form>
                                      </td>
                                      <td> 
                                        <label class="badge badge-info badge-pill">{{ date("F j, Y, g:i A", strtotime($amount->updated_at)) }}</label>
                                      </td>
                                    </tr>
                                    @endforeach
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                </div>
            </div>
        </div>
    </div>
@endsection