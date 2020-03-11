@extends('layouts.tabler')
@section('head_css')
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <link href="{{cdn('vendors/Datatable/data-tables.min.css')}}"rel="stylesheet" type="text/css" />
    <link href="{{cdn('vendors/Datatable/data-tables.checkbox.min.css')}}"rel="stylesheet" type="text/css" />
@endsection
@section('body_content_header_extras')

@endsection

@section('body_content_main')

    @include('layouts.blocks.tabler.alert')


    <div class="row">

        @include('layouts.blocks.tabler.sub-menu')
        <div class="col-md-9 col-xl-9">
            <div class="row row-cards row-deck " >
                <div class="col-sm-12" id="payroll_run">
                    @if(!empty($payroll_runs))
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap bootstrap-table"
                                   data-pagination="true"
                                   data-search="true"
                                   data-side-pagination="server"
                                   data-show-refresh="true"
                                   data-unique-id="id"
                                   data-id-field="id"
                                   data-row-attributes="processRows"
                                   data-url="{{ route('payroll-run-search') . '?' . http_build_query($args) }}"
                                   data-page-list="[10,25,50,100,200,300,500]"
                                   data-sort-class="sortable"
                                   data-search-on-enter-key="true"
                                   id="runs-table"
                                   v-on:click="clickAction($event)">
                                <thead>
                                <tr>
                                    <th data-field="title">Run Title</th>
                                    <th data-field="run">Run Name</th>
                                    <th data-field="status">Status</th>
                                    <th data-field="buttons">Actions</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    @else
                        <div class="col s12" >
                            @component('layouts.blocks.tabler.empty-fullpage')
                                @slot('title')
                                    No Payroll Run  Generated
                                @endslot
                                <a href="#" class="btn btn-primary" v-on:click.prevent="setPayrollrun">Add Payroll run</a>
                                &nbsp
                                @slot('buttons')
                                @endslot
                            @endcomponent
                        </div>
                    @endif
                        @include('modules-people-payroll::Payroll.modals.edit-payroll-run')

                </div>

                @include('modules-people-payroll::Payroll.modals.add-payroll-run')


            </div>

        </div>
    </div>

@endsection
@section('body_js')
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <script src="{{cdn('vendors/Datatable/data-tables.min.js')}}"></script>
    <script src="{{cdn('vendors/Datatable/data-tables.checkbox.min.js')}}"></script>
    <script>
        app.currentUser = {!! json_encode($dorcasUser) !!};
        const table = $('.bootstrap-table');
        let  Modal = new Vue({
            el: '#payroll-run-add-modal',
            data() {
                return {
                    table1 : null,
                    table2 : null,
                    currentStep:1,
                    form_data:{
                        title:'',
                        run:'',
                        status:'draft',
                        employees:[],
                        paygroups:[],

                    }

                }
            },
            methods: {
                submitForm: function (paygroups,employees) {
                        const self = this;
                        for(let i = 0; i < paygroups.length; i++){
                            if (paygroups[i] !== "") {
                                self.form_data.paygroups.push(paygroups[i])
                            }
                            else{
                                break;
                            }
                        }

                    for(let i = 0; i < employees.length; i++){
                        if (employees[i] !== "") {
                            self.form_data.employees.push(employees[i])
                        }
                        else{
                            break;
                        }
                    }

                    $('#submit-run').addClass('btn-loading btn-icon')
                    axios.post('/mpe/payroll-run',this.form_data)
                        .then(response=>{
                            $('#submit-run').removeClass('btn-loading btn-icon')
                            this.form_data = {};
                            dropdown.hidePayrollrunModal();
                            swal({
                                title:"Success!",
                                text:"Payroll run Successfully Created",
                                type:"success",
                                showLoaderOnConfirm: true,
                            }).then(function () {
                                location.reload()
                            });
                        })
                        .catch(e=>{
                            console.log(e.response.data);
                            $('#submit-run').removeClass('btn-loading btn-icon')
                            swal.fire({
                                title:"Error!",
                                text:e.response.data.message,
                                type:"error",
                                showLoaderOnConfirm: true,
                            });
                        })
                },
                goToStep(step){
                    this.currentStep = step;
                    if(step === 2){
                        $('#tables').css(
                            'display','block'
                        )
                    }
                    else{
                        $('#tables').css(
                            'display','none'
                        )
                    }
                },
                previousStep(step){
                    this.currentStep = step;
                },
                submitRun() {
                        $('#submit-run').addClass('btn-loading btn-icon')
                    let employee_rows_selected = this.table1.column(0).checkboxes.selected();
                    let paygroup_rows_selected = this.table2.column(0).checkboxes.selected();
                        let paygroups = paygroup_rows_selected.join(",").split(",");
                        let employees = employee_rows_selected.join(",").split(",");
                        // Iterate over all selected checkboxes
                        // $('#example-console-rows').text();
                        this.submitForm(paygroups,employees);
                        // Output form data to a console
                }
            },

            mounted() {
                this.table1 = $('#run_employees').DataTable({
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
                this.table2 = $('#run_paygroups').DataTable({
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
            }

        });
        let dropdown = new Vue({
            el : '#sub-menu-action',
            data:{

            },
            methods:{
                viewPayrollrunModal(){
                    $('#payroll-runs-add-modal').modal('show');
                },
                hidePayrollrunModal(){
                    $('#payroll-runs-add-modal').modal('hide');

                }
            },

        });
        let Payroll =  new Vue({
            el: '#payroll_run',
            data:{
                table1 : null,
                table2 : null,
                currentStep:1,
                employees:null,
                form_data:{
                    title:'',
                    run:'',
                    status:'',
                    employees:[],
                    paygroups:[],
                    run_id:null,

                }
            },
            methods:{
                clickAction: function (event) {
                    let target = event.target;
                    if (!target.hasAttribute('data-action')) {
                        target = target.parentNode.hasAttribute('data-action') ? target.parentNode : target;
                    }

                    let action = target.getAttribute('data-action');
                    let name = target.getAttribute('data-name');
                    let id = target.getAttribute('data-id');
                    let index = parseInt(target.getAttribute('data-index'), 10);
                    switch (action) {
                        case 'view':
                            return true;
                        case 'delete_run':
                            this.deleteRun(id,index,name);
                            break;
                        case 'editRun':
                            this.editRun(id,index,name);
                            break;
                    }

                },
                goToStep(step){
                    this.currentStep = step;
                    if(step === 2){
                        $('#edit-tables').css(
                            'display','block'
                        )
                    }
                    else{
                        $('#edit-tables').css(
                            'display','none'
                        )
                    }
                },
                previousStep(step){
                    this.currentStep = step;
                },
                editRun(id)
                {
                    const self = this;
                    axios.get("/mpe/payroll-run/" + id)
                        .then(function (response) {
                           const {title,run,status,employees,id} = response.data[0];
                           self.form_data.run = run;
                           self.form_data.title = title;
                           self.form_data.status = status;
                           self.employees = employees.data;
                           self.form_data.run_id = id;
                           console.log(employees);
                            self.employees.forEach(employee =>{
                                self.table1.row.add([
                                    employee.id,
                                    employee.firstname,
                                    employee.job_title,
                                    employee.staff_code
                                ]).draw(false)
                            });
                            self.table1.columns().checkboxes.select(true)
                            $('#payroll-run-edit-modal').modal('show')
                        })
                        .catch(function (error) {
                            let message = '';
                            console.log(error);
                            swal.fire({
                                title:"Error!",
                                text:error.response.data,
                                type:"error",
                                showLoaderOnConfirm: true,
                            });
                        });
                    console.log(Payroll.form_data)
                },
                setPayrollrun(){
                    dropdown.viewPayrollrunModal()
                },
                deleteRun(id,index,name){
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You are about to delete  " + name + " from this runs.",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return axios.delete("/mpe/payroll-run/" + id)
                                .then(function (response) {
                                    $('#runs-table').bootstrapTable('removeByUniqueId', response.data.id);
                                    return swal("Deleted!", "The run was successfully deleted.", "success");
                                }).catch(function (error) {
                                    let  message = '';
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
                updateRun() {

                    $('#edit-run').addClass('btn-loading btn-icon')
                    let employee_rows_selected = this.table1.column(0).checkboxes.selected();
                    let paygroup_rows_selected = this.table2.column(0).checkboxes.selected();
                    let paygroups = paygroup_rows_selected.join(",").split(",");
                    let employees = employee_rows_selected.join(",").split(",");
                    // Iterate over all selected checkboxes
                    // $('#example-console-rows').text();
                    this.updateForm(paygroups,employees);
                    // Output form data to a console
                },
                updateForm: function (paygroups,employees) {
                    const self = this;
                    for(let i = 0; i < paygroups.length; i++){
                        if (paygroups[i] !== "") {
                            self.form_data.paygroups.push(paygroups[i])
                        }
                        else{
                            break;
                        }
                    }

                    for(let i = 0; i < employees.length; i++){
                        if (employees[i] !== "") {
                            self.form_data.employees.push(employees[i])
                        }
                        else{
                            break;
                        }
                    }

                    $('#submit-run').addClass('btn-loading btn-icon')
                    axios.put('/mpe/payroll-run/'+this.form_data.run_id,this.form_data)
                        .then(response=>{
                            $('#edit-run').removeClass('btn-loading btn-icon')

                            this.form_data = {};
                            dropdown.hidePayrollrunModal();
                            swal({
                                title:"Success!",
                                text:"Payroll run Successfully Updated",
                                type:"success",
                                showLoaderOnConfirm: true,
                            }).then(function () {
                                location.reload()
                            });
                        })
                        .catch(e=>{
                            console.log(e.response.data);
                            $('#submit-run').removeClass('btn-loading btn-icon')
                            swal.fire({
                                title:"Error!",
                                text:e.response.data.message,
                                type:"error",
                                showLoaderOnConfirm: true,
                            });
                        })
                },
            },

            mounted() {
                this.table1 = $('#run_edit_employees').DataTable({
                    // 'ajax': '/lab/jquery-datatables-checkboxes/ids-arrays.txt',
                    'columnDefs': [
                        {
                            'targets': 0,
                            'checkboxes': {
                                'selectRow': true,
                            },
                        }
                    ],
                    'select': {
                        'style': 'multi'
                    },
                    'order': [[1, 'asc']]
                });
                this.table2 = $('#run_edit_paygroups').DataTable({
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
                // this.table1.rows({selected:true})

            }
        })

        function processRows(row, index) {
            row.created_at = moment(row.created_at).format('DD MMM, YYYY');
            row.buttons =
                '<a class="btn btn-sm btn-cyan text-white" data-index="' + index + '"  data-action="editRun" data-id="' + row.id + '" data-name="' + row.title + '">Update</a> &nbsp; ' +
                '<a class="btn btn-sm btn-danger text-white"   data-index="' + index + '" data-action="delete_run" data-id="' + row.id + '" data-name="' + row.title + '">Delete</a>'
            // row.account_link = '<a href="/mfn/finance-entries?account=' + row.account.data.id + '">' + row.account.data.display_name + '</a>';
            // row.created_at = moment(row.created_at).format('DD MMM, YYYY');
            // row.buttons = '<a class="btn btn-danger btn-sm remove" data-action="remove" href="#" data-id="'+row.id+'">Delete</a>';
            // if (typeof row.account.data !== 'undefined' && row.account.data.name == 'unconfirmed') {
            //     row.buttons += '<a class="btn btn-warning btn-sm views" data-action="views" href="/mfn/finance-entries/' + row.id + '" >Confirm</a>'
            // }
            // return row;
        }



    </script>
@endsection
