@extends('layouts.tabler')
@section('head_css')
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <link href="{{cdn('vendors/Datatable/data-tables.min.css')}}"rel="stylesheet" type="text/css" />
    <link href="{{cdn('vendors/Datatable/data-tables.checkbox.min.css')}}"rel="stylesheet" type="text/css" />
@endsection

@section('body_content_main')

    @include('layouts.blocks.tabler.alert')

    <div class="row">

        @include('layouts.blocks.tabler.sub-menu')
        <div class="col-md-9 col-xl-9" id="paygroup_profile">
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-profile">
                        <div class="card-header" v-bind:style="{ 'background-image': 'url(' + backgroundImage + ')' }"></div>
                        <div class="card-body text-center">
                            <img class="card-profile-img" v-bind:src="defaultPhoto">
                            <h3 class="mb-3">@{{ paygroup.name}}</h3>
                            <button v-on:click.prevent="editPaygroup" class="btn btn-outline-primary btn-sm text-center">
                                <span class="fa fa-sliders"></span> Edit Paygroup
                            </button>
                        </div>
                        @include('modules-people-payroll::Payroll.modals.edit-payroll-paygroup')

                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-status bg-blue"></div>
                        <div class="card-header">
                            <h3 class="card-title">Activity</h3>
                        </div>
                        <div class="card-body">
                            Manage <strong>Employees</strong>, <strong>Allowances</strong>
                            for this Paygroup:
                            <ul class="nav nav-tabs nav-justified">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#employees">Employees</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#allowances">Allowances </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane container active o-auto" id="employees">
                                    <br/>
                                    <div class="row mt-2" >
                                        <div class="container ">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table" id="paygroup-employees" >
                                                    <thead>
                                                    <tr>
                                                        <th>id</th>
                                                        <th>Name</th>
                                                        <th>Job Title</th>
                                                        <th>Staff Code</th>
                                                    </tr>
                                                    </thead>
                                                 <tbody>
                                             @foreach($paygroup_employees as $employee)
                                                 <tr>
                                                     <td>{{$employee['id']}}</td>
                                                     <td>{{$employee['firstname'] . ' '. $employee['lastname']}}</td>
                                                     <td>{{$employee['job_title']}}</td>
                                                     <td>{{$employee['staff_code']}}</td>
                                                 </tr>
                                                 @endforeach
                                                 </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="form-group ml-auto">
                                                <a class="btn btn-primary btn-sm " href="#" v-on:click.prevent="showEmployees">Add Employees</a>

                                        </div>
                                        <div class="form-group mr-auto">
                                            <a class="btn btn-danger btn-sm "  id="delete-employees" href="#"  >Delete Employees</a>

                                        </div>
                                    </div>
                                    &nbsp;
                                </div>
                                <div class="tab-pane container  o-auto" id="allowances">
                                    <br/>
                                    <div class="row mt-2" >
                                        <div class="container">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table" id="paygroup-allowances" >
                                                    <thead>
                                                    <tr>
                                                        <th>id</th>
                                                        <th>Name</th>
                                                        <th> Allowance Type</th>
{{--                                                        <th> Authority Name</th>--}}
                                                    </tr>
                                                    </thead>
                                                 <tbody>
                                                 @foreach($paygroup_allowances as $allowance)
                                                     <tr>
                                                         <td>{{$allowance['id']}}</td>
                                                         <td>{{$allowance['name']}}</td>
                                                         <td>{{$allowance['allowance_type']}}</td>
{{--                                                         <td>{{$allowance->payrollAuthority}}</td>--}}
                                                     </tr>
                                                     @endforeach
                                                 </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="form-group ml-auto">
                                            <a class="btn btn-primary btn-sm" href="#" v-on:click.prevent="showAllowances">Add Allowance</a>

                                        </div>
                                        <div class="form-group mr-auto">
                                            <a class="btn btn-danger btn-sm "  id="delete-allowances" href="#"  >Delete Allowances</a>

                                        </div>
                                    </div>
                                    &nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('modules-people-payroll::Payroll.modals.add-paygroup-employees')
                    @include('modules-people-payroll::Payroll.modals.add-paygroup-allowance')

                </div>

            </div>
        </div>
    </div>

@endsection
@section('body_js')
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <script src="{{cdn('vendors/Datatable/data-tables.min.js')}}"></script>
    <script src="{{cdn('vendors/Datatable/data-tables.checkbox.min.js')}}"></script>
    <script src="{{cdn('vendors/Datatable/data-tables.bootstrap.min.js')}}"></script>
    <script type="text/javascript">
        const max_day = 28;
        const min_day = 1;
        let Paygroup = new Vue({
            el: '#paygroup_profile',
            data: {
                paygroup: {!! json_encode($paygroup) !!},
                defaultPhoto: "{{ cdn('images/avatar/avatar-7.png') }}",
                backgroundImage: "{{ cdn('images/gallery/rawpixel-com-579246-unsplash.jpg') }}",
                employees: [],
                allowances: [],
                form_data:{
                    paygroup_name: '',

                }
            },
            methods: {
                editPaygroup()
                {
                    $('#payroll-paygroups-edit-modal').modal('show')
                },
                showEmployees(){
                    $('#payroll-paygroup-employee-add-modal').modal('show')

                },
                showAllowances(){
                    $('#payroll-paygroup-allowance-add-modal').modal('show')

                },
                updatePaygroup: function () {
                    $('#edit-paygroup').addClass('btn-loading btn-icon')
                    this.form_data.paygroup_name = this.paygroup.name
                    axios.put('/mpe/payroll-paygroup/'+this.paygroup.id,this.form_data)
                        .then(response=>{
                            $('#edit-paygroup').removeClass('btn-loading btn-icon')
                            form_data = {};
                            $('#payroll-paygroups-edit-modal').modal('hide')

                            swal({
                                title:"Success!",
                                text:"Payroll Paygroup Successfully Updated",
                                type:"success",
                                showLoaderOnConfirm: true,
                            }).then(function () {
                                location.reload()
                            });
                        })
                        .catch(e=>{
                            console.log(e.response.data);
                            $('#edit-paygroup').removeClass('btn-loading btn-icon')
                            swal.fire({
                                title:"Error!",
                                text:e.response.data.message,
                                type:"error",
                                showLoaderOnConfirm: true,
                            });
                        })
                },
                addEmployees(values) {
                    const self = this;
                    values.forEach(function (value) {
                     self.employees.push(value);
                    })
                    console.log(this.employees);
                    axios.post('/mpe/payroll-employee-add/'+self.paygroup.id,{"employees":self.employees})
                        .then(response=>{
                            $('#payroll-paygroup-employee-add-modal').modal('hide')
                            $('#employee-add-form').removeClass('btn-loading btn-icon')
                            swal({
                                title:"Success!",
                                text:"Employees Successfully Added to the  Paygroup ",
                                type:"success",
                                showLoaderOnConfirm: true,
                            }).then(function () {
                                location.reload()
                            });
                        })
                        .catch(e=>{
                            console.log(e.response.data);
                            $('#employee-add-form').removeClass('btn-loading btn-icon')

                            swal.fire({
                                title:"Error!",
                                text:e.response.data.message,
                                type:"error",
                                showLoaderOnConfirm: true,
                            });
                        })
                },
                deleteEmployees(values){
                    const self = this;
                    values.forEach(function (value) {
                        self.employees.push(value);
                    })
                    $('#delete-employees').removeClass('btn-loading btn-icon')

                    Swal.fire({
                        title: "Are you sure?",
                        text: "You are about to delete these employees",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return axios.post("/mpe/payroll-employee-delete/" + self.paygroup.id, {"employees":self.employees})
                                .then(function (response) {
                                    return swal.fire({
                                        title:"Success!",
                                        text:"Employees Successfully Deleted From the  Paygroup ",
                                        type:"success",
                                        showLoaderOnConfirm: true,
                                        preConfirm: ()=>{
                                            location.reload()
                                        }
                                    })
                                }).catch(function (error) {
                                    $('#delete-employees').removeClass('btn-loading btn-icon')

                                    var message = '';
                                    console.log(error);
                                    swal.fire({
                                        title:"Error!",
                                        text:error.response.data.message,
                                        type:"error",
                                        showLoaderOnConfirm: true,
                                    });
                                });
                        },
                        allowOutsideClick: () => !Swal.isLoading()


                    });

                },
                addAllowance(values) {
                    const self = this;
                    values.forEach(function (value) {
                        self.allowances.push(value);
                    })
                    console.log(self.allowances);
                    axios.post('/mpe/payroll-allowances-add/'+self.paygroup.id,{"allowances":self.allowances})
                        .then(response=>{
                            $('#payroll-paygroup-employee-add-modal').modal('hide')
                            $('#allowance-add-form').removeClass('btn-loading btn-icon')
                            swal({
                                title:"Success!",
                                text:"Allowances Successfully Added to the  Paygroup ",
                                type:"success",
                                showLoaderOnConfirm: true,
                            }).then(function () {
                                location.reload()
                            });
                        })
                        .catch(e=>{
                            console.log(e.response.data);
                            $('#allowance-add-form').removeClass('btn-loading btn-icon')

                            swal.fire({
                                title:"Error!",
                                text:e.response.data.message,
                                type:"error",
                                showLoaderOnConfirm: true,
                            });
                        })
                },
                deleteAllowances(values){
                    const self = this;
                    values.forEach(function (value) {
                        self.allowances.push(value);
                    })
                    $('#delete-allowances').removeClass('btn-loading btn-icon')

                    Swal.fire({
                        title: "Are you sure?",
                        text: "You are about to delete these allowances",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return axios.post("/mpe/payroll-allowance-delete/" + self.paygroup.id, {"allowances":self.allowances})
                                .then(function (response) {
                                    return swal.fire({
                                        title:"Success!",
                                        text:"Allowances Successfully Deleted From the  Paygroup ",
                                        type:"success",
                                        showLoaderOnConfirm: true,
                                        preConfirm: ()=>{
                                            location.reload()
                                        }
                                    })
                                }).catch(function (error) {
                                    $('#delete-allowances').removeClass('btn-loading btn-icon')

                                    var message = '';
                                    console.log(error);
                                    swal.fire({
                                        title:"Error!",
                                        text:error.response.data.message,
                                        type:"error",
                                        showLoaderOnConfirm: true,
                                    });
                                });
                        },
                        allowOutsideClick: () => !Swal.isLoading()


                    });

                },
            },
            mounted(){
                console.log(this.form_data)
            },
            computed:{
            }
        });

        $(document).ready(function (){
            var table = $('#employee-table').DataTable({
                // 'ajax': '/lab/jquery-datatables-checkboxes/ids-arrays.txt',
                'columnDefs': [
                    {
                        'targets': 0,
                        'checkboxes': {
                            'selectRow': true
                        }
                    }
                ],
                'select': {
                    'style': 'multi'
                },
                'order': [[1, 'asc']]
            });
            var table2  = $('#allowance-table').DataTable({
                // 'ajax': '/lab/jquery-datatables-checkboxes/ids-arrays.txt',
                'columnDefs': [
                    {
                        'targets': 0,
                        'checkboxes': {
                            'selectRow': true
                        }
                    }
                ],
                'select': {
                    'style': 'multi'
                },
                'order': [[1, 'asc']]
            });
            var table3  = $('#paygroup-allowances').DataTable({
                // 'ajax': '/lab/jquery-datatables-checkboxes/ids-arrays.txt',
                'columnDefs': [
                    {
                        'targets': 0,
                        'checkboxes': {
                            'selectRow': true
                        }
                    }
                ],
                'select': {
                    'style': 'multi'
                },
                'order': [[1, 'asc']]
            });
            var table4  = $('#paygroup-employees').DataTable({
                // 'ajax': '/lab/jquery-datatables-checkboxes/ids-arrays.txt',
                'columnDefs': [
                    {
                        'targets': 0,
                        'checkboxes': {
                            'selectRow': true
                        }
                    }
                ],
                'select': {
                    'style': 'multi'
                },
                'order': [[1, 'asc']]
            });


            // Handle form submission event
            $('#employee-add-form').on('click', function(e){
                $('#employee-add-form').addClass('btn-loading btn-icon')
                var form = this;

                var rows_selected = table.column(0).checkboxes.selected();
                var rows = []
                // Iterate over all selected checkboxes
                $.each(rows_selected, function(index, rowId){
                    // Create a hidden element
                    // $(form).append(
                    //     $('<input>')
                    //         .attr('type', 'hidden')
                    //         .attr('name', 'id[]')
                    //         .val(rowId)
                    // );
                });
                // $('#example-console-rows').text();
                Paygroup.addEmployees(rows_selected.join(",").split(","));
                // Output form data to a console
                $('#example-console-form').text($(form).serialize());
            });
            $('#allowance-add-form').on('click', function(e){
                $('#allowance-add-form').addClass('btn-loading btn-icon')
                var form = this;

                var rows_selected = table2.column(0).checkboxes.selected();
                var rows = []
                // Iterate over all selected checkboxes
                $.each(rows_selected, function(index, rowId){
                    // Create a hidden element
                    // $(form).append(
                    //     $('<input>')
                    //         .attr('type', 'hidden')
                    //         .attr('name', 'id[]')
                    //         .val(rowId)
                    // );
                });
                // $('#example-console-rows').text();
                Paygroup.addAllowance(rows_selected.join(",").split(","));
                // Output form data to a console
                $('#example-console-form').text($(form).serialize());
            });
            $('#delete-employees').on('click', function(e){
                $('#delete-employees').addClass('btn-loading btn-icon')
                var form = this;

                var rows_selected = table4.column(0).checkboxes.selected();
                var rows = []
                // Iterate over all selected checkboxes
                $.each(rows_selected, function(index, rowId){
                    // Create a hidden element
                    // $(form).append(
                    //     $('<input>')
                    //         .attr('type', 'hidden')
                    //         .attr('name', 'id[]')
                    //         .val(rowId)
                    // );
                });
                // $('#example-console-rows').text();
                Paygroup.deleteEmployees(rows_selected.join(",").split(","));
                // Output form data to a console
                $('#example-console-form').text($(form).serialize());
            });
            $('#delete-allowances').on('click', function(e){
                $('#delete-allowances').addClass('btn-loading btn-icon')
                var form = this;
                var rows_selected = table3.column(0).checkboxes.selected();
                var rows = [];
                // Iterate over all selected checkboxes
                $.each(rows_selected, function(index, rowId){
                    // Create a hidden element
                    // $(form).append(
                    //     $('<input>')
                    //         .attr('type', 'hidden')
                    //         .attr('name', 'id[]')
                    //         .val(rowId)
                    // );
                });
                // $('#example-console-rows').text();
                Paygroup.deleteAllowances(rows_selected.join(",").split(","));
                // Output form data to a console
                $('#example-console-form').text($(form).serialize());
            });
        });
    </script>
@endsection
