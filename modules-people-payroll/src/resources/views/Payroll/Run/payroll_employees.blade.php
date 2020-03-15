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
        @include('layouts.blocks.tabler.sub-menu')
        <div class="col-md-9" id="processed-run">
            <table class="table" id="processed_employees" >
                <thead>
                <tr>
                    <th>First Name</th>
                    <th>Staff Code</th>
                    <th>Job Title</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>

                @foreach($processed_employees as $employee)
                    @if ($employee === "[]")
                        @break
                    @endif
                    <tr>
                        <td>{{$employee['firstname']}}</td>
                        <td>{{$employee['staff_code']}}</td>
                        <td>{{$employee['job_title']}}</td>
                        <td>
                            <form method="post" action="{{route('view-payslip')}}">
                                {{csrf_field()}}
                                <input type="hidden" value="{{$employee['uuid']}}" name="uuid">
                                <input type="hidden" value="{{$employee['invoice_data']}}" name="employee_details">
                                <input type="hidden" value="{{$employee['salary_amount']}}" name="base_salary">
                                <input type="hidden" value="{{$employee['amount']}}" name="payable_amount">
                                <input type="hidden" value="{{$employee['company_id']}}" name="company_id">
                                <input type="hidden" value="{{$employee['firstname']}}" name="firstname">
                                <input type="hidden" value="{{$employee['lastname']}}" name="lastname">
                                <input type="hidden" value="{{$employee['job_title']}}" name="job_title">
                                <input type="hidden" value="{{$employee['staff_code']}}" name="staff_code">
                                <input type="hidden" value="{{$employee['email']}}" name="email">
                                <input type="hidden" value="{{$employee['created_at']}}" name="date">
                                <button type="submit" class="btn btn-primary btn-sm"  >View Payslip</button>
                            </form>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('body_js')
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
<script src="{{cdn('vendors/Datatable/data-tables.min.js')}}"></script>
<script src="{{cdn('vendors/Datatable/data-tables.bootstrap.min.js')}}"></script>
<script>

    table1 = $('#processed_employees').DataTable({
        'order': [[1, 'asc']]
    });
</script>

@endsection