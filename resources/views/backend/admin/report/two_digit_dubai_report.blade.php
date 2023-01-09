<div class="row">
    <div class="col-12 grid-margin stretch-card d-none d-md-flex">
      <div class="card">
        <div class="card-header bg-primary text-white">
          2 Digit Dubai Today Report
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12 mx-auto">

              <ul class="nav nav-pills nav-pills-custom" id="pills-tab-custom" role="tablist">
                 @foreach( $dubai_times as $key => $dubai )
                <li class="nav-item">
                  <a class="nav-link {{ $key == 0 ? 'active' : '' }}" id="pills-profile-tab-custom" data-toggle="pill" href="#dubai-{{ $dubai->id }}" role="tab" aria-controls="pills-profile" aria-selected="false" style="padding:0.5rem 1rem" >
                    {{ $dubai->time }}
                  </a>
                </li>
                @endforeach
              </ul>

              <div class="tab-content tab-content-custom-pill" id="pills-tabContent-custom">
                
                <div class="tab-pane fade show active" id="dubai-3" role="tabpanel" aria-labelledby="pills-home-tab-custom">
                  <div class="icons-list row">
                    @foreach($two_digits as $digit)
                    <div class="col-sm-6 col-md-4 col-lg-2 align-middle">
                      <button type="button" class="btn btn-info btn-rounded btn-icon">
                        {{ $digit->number }}
                      </button> &nbsp;
                      @foreach($dubai_one as $draw)
                      @if($digit->id == $draw->two_digit_id)
                      <span class="badge badge-success badge-pill">Ks {{ $draw->amount }}</span>
                      @endif
                      @endforeach
                    </div>
                    @endforeach
                  </div>
                  <div class="icons-list row">
                    <div class="col-12 align-middle">
                      <h4 class="text-right">Total : {{ $dubai_one_total }} MMK</h4>
                    </div>
                  </div>
                </div>

                <div class="tab-pane fade" id="dubai-4" role="tabpanel" aria-labelledby="pills-profile-tab-custom">
                  <div class="icons-list row">
                    @foreach($two_digits as $digit)
                    <div class="col-sm-6 col-md-4 col-lg-2 align-middle">
                      <button type="button" class="btn btn-info btn-rounded btn-icon">
                        {{ $digit->number }}
                      </button> &nbsp;
                      @foreach($dubai_two as $draw)
                      @if($digit->id == $draw->two_digit_id)
                      <span class="badge badge-success badge-pill">Ks {{ $draw->amount }}</span>
                      @endif
                      @endforeach
                    </div>
                    @endforeach
                  </div>
                  <div class="icons-list row">
                    <div class="col-12 align-middle">
                      <h4 class="text-right">Total : {{ $dubai_two_total }} MMK</h4>
                    </div>
                  </div>
                </div>

                <div class="tab-pane fade" id="dubai-5" role="tabpanel" aria-labelledby="pills-profile-tab-custom">
                  <div class="icons-list row">
                    @foreach($two_digits as $digit)
                    <div class="col-sm-6 col-md-4 col-lg-2 align-middle">
                      <button type="button" class="btn btn-info btn-rounded btn-icon">
                        {{ $digit->number }}
                      </button> &nbsp;
                      @foreach($dubai_three as $draw)
                      @if($digit->id == $draw->two_digit_id)
                      <span class="badge badge-success badge-pill">Ks {{ $draw->amount }}</span>
                      @endif
                      @endforeach
                    </div>
                    @endforeach
                  </div>
                  <div class="icons-list row">
                    <div class="col-12 align-middle">
                      <h4 class="text-right">Total : {{ $dubai_three_total }} MMK</h4>
                    </div>
                  </div>
                </div>

                <div class="tab-pane fade" id="dubai-6" role="tabpanel" aria-labelledby="pills-profile-tab-custom">
                  <div class="icons-list row">
                    @foreach($two_digits as $digit)
                    <div class="col-sm-6 col-md-4 col-lg-2 align-middle">
                      <button type="button" class="btn btn-info btn-rounded btn-icon">
                        {{ $digit->number }}
                      </button> &nbsp;
                      @foreach($dubai_four as $draw)
                      @if($digit->id == $draw->two_digit_id)
                      <span class="badge badge-success badge-pill">Ks {{ $draw->amount }}</span>
                      @endif
                      @endforeach
                    </div>
                    @endforeach
                  </div>
                  <div class="icons-list row">
                    <div class="col-12 align-middle">
                      <h4 class="text-right">Total : {{ $dubai_four_total }} MMK</h4>
                    </div>
                  </div>
                </div>

                <div class="tab-pane fade" id="dubai-7" role="tabpanel" aria-labelledby="pills-profile-tab-custom">
                  <div class="icons-list row">
                    @foreach($two_digits as $digit)
                    <div class="col-sm-6 col-md-4 col-lg-2 align-middle">
                      <button type="button" class="btn btn-info btn-rounded btn-icon">
                        {{ $digit->number }}
                      </button> &nbsp;
                      @foreach($dubai_five as $draw)
                      @if($digit->id == $draw->two_digit_id)
                      <span class="badge badge-success badge-pill">Ks {{ $draw->amount }}</span>
                      @endif
                      @endforeach
                    </div>
                    @endforeach
                  </div>
                  <div class="icons-list row">
                    <div class="col-12 align-middle">
                      <h4 class="text-right">Total : {{ $dubai_five_total }} MMK</h4>
                    </div>
                  </div>
                </div>

                <div class="tab-pane fade" id="dubai-8" role="tabpanel" aria-labelledby="pills-profile-tab-custom">
                  <div class="icons-list row">
                    @foreach($two_digits as $digit)
                    <div class="col-sm-6 col-md-4 col-lg-2 align-middle">
                      <button type="button" class="btn btn-info btn-rounded btn-icon">
                        {{ $digit->number }}
                      </button> &nbsp;
                      @foreach($dubai_six as $draw)
                      @if($digit->id == $draw->two_digit_id)
                      <span class="badge badge-success badge-pill">Ks {{ $draw->amount }}</span>
                      @endif
                      @endforeach
                    </div>
                    @endforeach
                  </div>
                  <div class="icons-list row">
                    <div class="col-12 align-middle">
                      <h4 class="text-right">Total : {{ $dubai_six_total }} MMK</h4>
                    </div>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>