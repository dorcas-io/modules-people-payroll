@extends('layouts.tabler')
@section('body_content_header_extras')

@endsection

@section('body_content_main')

    @include('layouts.blocks.tabler.alert')


    <div class="row">

        @include('layouts.blocks.tabler.sub-menu')
        <div class="col-md-9 col-xl-9">
            <div class="row row-cards row-deck " >
                <div class="col-sm-12" id="payroll_transaction">
                    @if(!empty($payroll_transactions))
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap bootstrap-table"
                                   data-pagination="true"
                                   data-search="true"
                                   data-side-pagination="server"
                                   data-show-refresh="true"
                                   data-unique-id="id"
                                   data-id-field="id"
                                   data-row-attributes="processRows"
                                   data-url="{{ route('payroll-transaction-search') . '?' . http_build_query($args) }}"
                                   data-page-list="[10,25,50,100,200,300,500]"
                                   data-sort-class="sortable"
                                   data-search-on-enter-key="true"
                                   id="transactions-table"
                                   v-on:click="clickAction($event)">
                                <thead>
                                <tr>
                                    <th data-field="remarks">transaction</th>
                                    <th data-field="amount_type">Type</th>
                                    <th data-field="amount">Amount</th>
                                    <th data-field="created_at">Added On</th>
                                    <th data-field="buttons">Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    @else
                        <div class="col s12" >
                            @component('layouts.blocks.tabler.empty-fullpage')
                                @slot('title')
                                    No Payroll transaction Generated
                                @endslot
                                <a href="#" class="btn btn-primary" v-on:click.prevent="setPayrolltransaction">Add Payroll transaction</a>
                                &nbsp;
                                @slot('buttons')
                                @endslot
                            @endcomponent
                        </div>
                    @endif
                    @include('modules-people-payroll::Payroll.modals.edit-payroll-transaction')

                </div>

                @include('modules-people-payroll::Payroll.modals.add-payroll-transaction')


            </div>

        </div>
    </div>

@endsection
@section('body_js')
    <script>
        app.currentUser = {!! json_encode($dorcasUser) !!};
        const table = $('.bootstrap-table');
        let  Modal = new Vue({
            el: '#payroll-transactions-add-modal',
            data() {
                return {
                    employees: {!! json_encode($employees) !!},
                    end_time: false,
                    form_data:{
                    }

                }
            },
            methods: {
                toggleStatus(event ){
                    switch(event.target.value){
                        case 'repeat':
                            this.end_time = true;
                            break;
                        case 'one_time':
                            this.end_time = false;
                            break;
                    }
                },
                submitForm: function () {
                    $('#submit-transaction').addClass('btn-loading btn-icon')
                    axios.post('/mpe/payroll-transaction',this.form_data)
                        .then(response=>{
                            $('#submit-transaction').removeClass('btn-loading btn-icon')
                            this.form_data = {};
                            dropdown.hidePayrolltransactionModal();
                            swal({
                                title:"Success!",
                                text:"Payroll transaction Successfully Created",
                                type:"success",
                                showLoaderOnConfirm: true,
                            }).then(function () {
                                location.reload()
                            });
                        })
                        .catch(e=>{
                            console.log(e.response.data);
                            $('#submit-transaction').removeClass('btn-loading btn-icon')
                            swal.fire({
                                title:"Error!",
                                text:e.response.data.message,
                                type:"error",
                                showLoaderOnConfirm: true,
                            });
                        })
                }

            },

        });
        let dropdown = new Vue({
            el : '#sub-menu-action',
            data:{


            },
            methods:{
                viewPayrolltransactionModal(){
                    $('#payroll-transactions-add-modal').modal('show');
                },
                hidePayrolltransactionModal(){
                    $('#payroll-transactions-add-modal').modal('hide');

                }
            },

        });
        let Payroll =  new Vue({
            el: '#payroll_transaction',
            data:{
                employees: {!! json_encode($employees) !!},
                end_time: false,
                form_data:{
                    employee:  {},
                    "selected_employee" :undefined
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
                        case 'delete_transaction':
                            this.deletetransaction(id,index,name);
                            break;
                        case 'edit_transaction':
                            this.editTransaction(id,index,name);
                            break;
                    }

                },
                async editTransaction(id)
                {
                    const self = this;
                    await axios.get("/mpe/payroll-transaction/" + id)
                        .then(function (response) {
                            const {status_type, id,amount_type,amount,end_time,remarks,employee} = response.data[0];
                            switch(status_type){
                                case "repeat":
                                    self.end_time = true;
                                    break;
                                case "one_time":
                                    self.end_time = false;

                                    break;
                            }
                            self.form_data =  {
                                "allowance_id" : id,
                                "status_type" : status_type,
                                "transaction_type" : amount_type,
                                "amount" : amount,
                                "end_time" :end_time,
                                "remarks" :remarks,
                                "employee" :employee.data,

                            }
                            console.log(self.form_data)
                            $('#payroll-transactions-edit-modal').modal('show')

                        })
                        .catch(function (error) {
                            var message = '';
                            console.log(error);
                            swal.fire({
                                title:"Error!",
                                text:error.response.data,
                                type:"error",
                                showLoaderOnConfirm: true,
                            });
                        });


                },

                deletetransaction(id,index,name){
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You are about to delete  " + name + " from this transactions.",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return axios.delete("/mpe/payroll-transaction/" + id)
                                .then(function (response) {
                                    $('#transactions-table').bootstrapTable('removeByUniqueId', response.data.id);
                                    return swal("Deleted!", "The transaction was successfully deleted.", "success");
                                }).catch(function (error) {
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


                toggleStatus(event ){
                    switch(event.target.value){
                        case 'repeat':
                            this.end_time = true;
                            break;
                        case 'one_time':
                            this.end_time = false;
                            break;
                    }
                },
                updateTransaction: function () {
                    $('#edit-transaction').addClass('btn-loading btn-icon')
                    axios.put('/mpe/payroll-transaction/'+this.form_data.allowance_id,this.form_data)
                        .then(response=>{
                            $('#edit-transaction').removeClass('btn-loading btn-icon')
                            form_data = {};
                            $('#payroll-transactions-edit-modal').modal('hide')

                            swal({
                                title:"Success!",
                                text:"Payroll transaction Successfully Updated",
                                type:"success",
                                showLoaderOnConfirm: true,
                            }).then(function () {
                                location.reload()
                            });
                        })
                        .catch(e=>{
                            console.log(e.response.data);
                            $('#edit-transaction').removeClass('btn-loading btn-icon')
                            swal.fire({
                                title:"Error!",
                                text:e.response.data.message,
                                type:"error",
                                showLoaderOnConfirm: true,
                            });
                        })
                },
            },
            mounted(){
            }
        })

        function processRows(row, index) {
            row.created_at = moment(row.created_at).format('DD MMM, YYYY');
            row.buttons = '<a class="btn btn-sm btn-primary text-white"  data-index="'+index+'"  data-action="edit_transaction" data-id="'+row.id+'" data-name="'+row.name+'">Update</a> &nbsp; ' +
                '<a class="btn btn-sm btn-danger text-white"   data-index="'+index+'" data-action="delete_transaction" data-id="'+row.id+'" data-name="'+row.remarks+'">Delete</a>'
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
