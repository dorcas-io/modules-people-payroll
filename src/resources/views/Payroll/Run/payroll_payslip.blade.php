@extends('layouts.tabler')
@section('head_css')
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <link href="{{cdn('vendors/Datatable/data-tables.min.css')}}" rel="stylesheet" type="text/css" />
    <style>

    </style>
@endsection
@section('body_content_header_extras')

@endsection

@section('body_content_main')

    @include('layouts.blocks.tabler.alert')


    <div class="row">
        <div class="card">
            <div class="card-header d-print-none">
                <h3 class="card-title">Payslip</h3>
                <div class="card-options">
                    <button type="button" class="btn btn-primary" onclick="javascript:window.print();"><i class="si si-printer"></i> Print Payslip
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <p class="h3">{{$dorcasUser->company['data']['name']}}</p>
                    </div>
                    <div class="col-6 text-right">
                        <p class="h3">{{$response['firstname'].' '.  $response['lastname']}}</p>
                            {{$response['email']}} <br>
                            {{$response['job_title']}}<br>
                           {{$response['staff_code']}}<br>
                    </div>
                    <div class="col-12 my-5">
                        <h1>{{\Carbon\Carbon::parse($response['date'])->format(' F Y'). ' Payslip'}}</h1>
                        <h4>Date of Payment : {{\Carbon\Carbon::parse($response['date'])->format('Y-m-d')}}</h4>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        @php  $i = 1  @endphp

                        <thead>
                        <tr>
                            <th class="text-center" style="width: 1%"></th>
                            <th>Earnings </th>
                            <th> </th>
                            <th> </th>
                            <th class="text-right" style="width: 1%">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td class="text-center">{{$i}}</td>
                            <td>
                                <p class="strong mb-1">Base Salary</p>
                            </td>
                            <td class="text-center">

                            </td>
                            <td class="text-right"></td>
                            <td class="text-right">{{'&#8358;'.\Dorcas\ModulesPeoplePayroll\Http\Helpers\Helper::MoneyConvert($response['base_salary'],'full')}}</td>
                        </tr>

                        <tr>
                            <td colspan="4" class="strong text-right">Gross Payment</td>
                            <td class="text-right">{{'&#8358;'.\Dorcas\ModulesPeoplePayroll\Http\Helpers\Helper::MoneyConvert($response['base_salary'],'full')}}</td>
                        </tr>

                        </tbody>
                        <thead class="bg-secondary">
                        <tr>
                            <th class="text-center" style="width: 1%"></th>
                            <th class="text-dark">Company Allowances</th>
                            <th class="text-dark"> type </th>
                            <th class="text-dark"> name</th>
                            <th class="text-right text-dark" style="width: 1%">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($allowances))
                            @forelse($allowances['Allowances'] as $key => $allowance)
                                    @if ($key === 'deduction')
                                        @foreach($allowances['Allowances'][$key] as $key2 => $deduction)
                                            @php  $i++  @endphp
                                            <tr>
                                                <td class="text-center">{{$i}}</td>

                                                <td>Allowance</td>
                                                <td>{{$key}}</td>
                                                <td>{{$key2}}</td>
                                                <td class="text-right" style="width: 1%">{{'&#8358; '.\Dorcas\ModulesPeoplePayroll\Http\Helpers\Helper::MoneyConvert($deduction,'full')}}</td>
                                            </tr>
                                        @endforeach
                                    @elseif($key === 'benefits')

                                        @foreach($allowances['Allowances'][$key] as $key2 => $addition)
                                            @php  $i++  @endphp

                                            <tr>
                                                <td class="text-center">{{$i}}</td>
                                                <td>Allowance</td>
                                                <td>{{$key}}</td>
                                                <td>{{$key2}}</td>
                                                <td class="text-right" style="width: 1%">{{'&#8358; '.\Dorcas\ModulesPeoplePayroll\Http\Helpers\Helper::MoneyConvert($addition,'full')}}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    {{--                                <td>{{$allowance['']}}</td>--}}
                            @empty
                                <p>No Allowances for this Employee</p>
                            @endforelse
                        @endif

                        </tbody>
                        <thead class="bg-gray">
                        <tr>
                            <th class="text-center" style="width: 1%"></th>
                            <th class="text-dark"> Special SpecialAdditions / Subtractions</th>
                            <th class="text-dark"> type </th>
                            <th class="text-dark"> name</th>
                            <th class="text-right text-dark" style="width: 1%">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($transactions))
                            @forelse($transactions['Transactions'] as $key => $transaction)

                                    @if ($key === 'deductions')
                                        @foreach($transactions['Transactions'][$key] as $key2 => $deduction)
                                            @php  $i++;  @endphp
                                           <tr>
                                               <td class="text-center">{{$i}}</td>
                                               <td> Deductions</td>
                                               <td>{{$key}}</td>
                                               <td>{{$key2}}</td>
                                               <td class="text-right" style="width: 1%">{{'&#8358; '.\Dorcas\ModulesPeoplePayroll\Http\Helpers\Helper::MoneyConvert($deduction,'full')}}</td>
                                           </tr>
                                        @endforeach
                                    @elseif($key === 'additions')
                                        @foreach($transactions['Transactions'][$key] as $key2 => $addition)
                                            @php  $i++;  @endphp
                                            <tr>
                                                <td class="text-center">{{$i}}</td>
                                                <td> Benefits</td>
                                                <td>{{$key}}</td>
                                                <td>{{$key2}}</td>
                                                <td class="text-right" style="width: 1%">{{'&#8358;'.\Dorcas\ModulesPeoplePayroll\Http\Helpers\Helper::MoneyConvert($addition,'full')}}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    {{--                                <td>{{$allowance['']}}</td>--}}
                            @empty
                                <p>No Allowances for this Employee</p>
                            @endforelse
                        @endif

                        </tbody>
                        <tr>
                            <td colspan="4" class="font-weight-bold text-uppercase text-right">Total Payable</td>
                            <td class="font-weight-bold text-right">{{'&#8358;'. \Dorcas\ModulesPeoplePayroll\Http\Helpers\Helper::MoneyConvert($response['payable_amount'],'full')}}</td>
                        </tr>
                    </table>


                </div>
            </div>
        </div>
    </div>
@endsection
@section('body_js')
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
@endsection