@extends('layouts.tabler')
@section('body_content_header_extras')

@endsection

@section('body_content_main')

    @include('layouts.blocks.tabler.alert')


    <div class="row">

        @include('layouts.blocks.tabler.sub-menu')
        <div class="col-md-9 col-xl-9">
            <div class="row row-cards row-deck " >
              <div class="col-sm-6">
                  @component('layouts.blocks.tabler.empty-fullpage')
                      @slot('title')
                          Configuration
                      @endslot
                          @slot('buttons')
                          @endslot
                            <div class="card-body">
                                <div class="card-text">
                                     Manage multiple settings such as payroll authorities, paygroups and allowances
                                </div>
                            </div>
                          <div class="row row-cards row-deck">
                              <div class="col-md-4 mt-4 ">
                                  <a  href="{{route('payroll-authorities')}}" class="btn btn-primary  "> Authorities </a>

                              </div>
                              <div class="col-md-4 mt-4">
                                  <a href="{{route('payroll-allowances')}}" class="btn btn-primary">Allowances </a>


                              </div>
                              <div class="col-md-4 mt-4 ">
                                  <a href="{{route('payroll-paygroup')}}" class="btn btn-primary ">Pay-groups </a>

                              </div>

                          </div>
                  @endcomponent
              </div>
                <div class="col-sm-6">
                    @component('layouts.blocks.tabler.empty-fullpage')
                        @slot('title')
                             Transactions
                        @endslot
                            <div class="card-body">
                                <div class="card-text">
                                     Add one-off or recurring  payroll transactions for employees
                                </div>
                            </div>
                        <a href="{{route('payroll-transactions')}}" class="btn btn-primary">Manage </a>
                        &nbsp;
                        @slot('buttons')
                        @endslot
                    @endcomponent

                </div>

                <div class="col-sm-6">
                    @component('layouts.blocks.tabler.empty-fullpage')
                        @slot('title')
                             Sessions
                        @endslot
                            <div class="card-body">
                                <div class="card-text">
                                   Create and  Manage a Payroll Session
                                </div>
                            </div>
                        <a href="{{route('payroll-runs')}}" class="btn btn-primary">Manage </a>
                        &nbsp;
                        @slot('buttons')
                        @endslot
                    @endcomponent

                </div>

            </div>

        </div>
    </div>

@endsection
@section('body_js')

@endsection
