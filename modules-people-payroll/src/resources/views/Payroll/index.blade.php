@extends('layouts.tabler')
@section('body_content_header_extras')

@endsection

@section('body_content_main')

    @include('layouts.blocks.tabler.alert')


    <div class="row">

        @include('layouts.blocks.tabler.sub-menu')
        <div class="col-md-9 col-xl-9">
            <div class="row row-cards row-deck " >
                <div class="col-sm-4">
                    @component('layouts.blocks.tabler.empty-fullpage')
                        @slot('title')
                            Payroll Authorities
                        @endslot
                        <a href="{{route('payroll-authorities')}}" class="btn btn-primary">View </a>
                        &nbsp;
                        @slot('buttons')
                        @endslot
                    @endcomponent

                </div>
                <div class="col-sm-4">
                    @component('layouts.blocks.tabler.empty-fullpage')
                        @slot('title')
                            Payroll Allowances
                        @endslot
                        <a href="{{route('payroll-allowances')}}" class="btn btn-primary">View </a>
                        &nbsp;
                        @slot('buttons')
                        @endslot
                    @endcomponent

                </div>
                <div class="col-sm-4">
                    @component('layouts.blocks.tabler.empty-fullpage')
                        @slot('title')
                            Payroll Paygroups
                        @endslot
                        <a href="{{route('payroll-paygroup')}}" class="btn btn-primary">View </a>
                        &nbsp;
                        @slot('buttons')
                        @endslot
                    @endcomponent

                </div>
                <div class="col-sm-4">
                    @component('layouts.blocks.tabler.empty-fullpage')
                        @slot('title')
                            Payroll Transactions
                        @endslot
                        <a href="{{route('payroll-transactions')}}" class="btn btn-primary">View </a>
                        &nbsp;
                        @slot('buttons')
                        @endslot
                    @endcomponent

                </div>

                <div class="col-sm-4">
                    @component('layouts.blocks.tabler.empty-fullpage')
                        @slot('title')
                            Payroll Run
                        @endslot
                        <a href="{{route('payroll-runs')}}" class="btn btn-primary">View </a>
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
