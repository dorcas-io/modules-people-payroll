@extends('layouts.tabler')
@section('body_content_header_extras')

@endsection

@section('body_content_main')

    @include('layouts.blocks.tabler.alert')


    <div class="row">

        @include('layouts.blocks.tabler.sub-menu')
        <div class="col-md-9 col-xl-9">
            <div class="row row-cards row-deck " >
                <a href="#" class="btn btn-primary col-md-2 ml-auto mb-2" data-toggle="modal" data-target="#payroll-paygroups-add-modal"  >
                    Add  Pay-Group
                </a>
                <div class="col-md-12 align-items-end" >

                    <a href="{{route('payroll-main')}}">
                        <span><i class="fe fe-arrow-left"></i></span>
                        Payroll Home
                    </a>
                </div>
                <div class="col-sm-12" id="payroll_paygroup">
                    @if(!empty($payroll_paygroups))
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap bootstrap-table"
                                   data-pagination="true"
                                   data-search="true"
                                   data-side-pagination="server"
                                   data-show-refresh="true"
                                   data-unique-id="id"
                                   data-id-field="id"
                                   data-row-attributes="processRows"
                                   data-url="{{ route('payroll-paygroup-search') . '?' . http_build_query($args) }}"
                                   data-page-list="[10,25,50,100,200,300,500]"
                                   data-sort-class="sortable"
                                   data-search-on-enter-key="true"
                                   id="paygroups-table"
                                   v-on:click="clickAction($event)">
                                <thead>
                                <tr>
                                    <th data-field="name">Paygroup</th>
                                    <th data-field="buttons">Actions</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    @else
                        <div class="col s12" >
                            @component('layouts.blocks.tabler.empty-fullpage')
                                @slot('title')
                                    No Payroll Paygroup Generated
                                @endslot
                                <a href="#" class="btn btn-primary" v-on:click.prevent="setPayrollPaygroup">Add Payroll Paygroup</a>
                                &nbsp;
                                @slot('buttons')
                                @endslot
                            @endcomponent
                        </div>
                    @endif

                </div>

                @include('modules-people-payroll::Payroll.modals.add-payroll-paygroup')


            </div>

        </div>
    </div>

@endsection
@section('body_js')
    <script>
        app.currentUser = {!! json_encode($dorcasUser) !!};
        const table = $('.bootstrap-table');
        let  Modal = new Vue({
            el: '#payroll-paygroups-add-modal',
            data() {
                return {
                    form_data:{
                        paygroup_name:'',
                    }

                }
            },
            methods: {
                submitForm: function () {
                    $('#submit-paygroup').addClass('btn-loading btn-icon')
                    axios.post('/mpe/payroll-paygroup',this.form_data)
                        .then(response=>{
                            $('#submit-paygroup').removeClass('btn-loading btn-icon')
                            this.form_data = {};
                            dropdown.hidePayrollPaygroupModal();
                            swal({
                                title:"Success!",
                                text:"Payroll Paygroup Successfully Created",
                                type:"success",
                                showLoaderOnConfirm: true,
                            }).then(function () {
                                location.reload()
                            });
                        })
                        .catch(e=>{
                            console.log(e.response.data);
                            $('#submit-paygroup').removeClass('btn-loading btn-icon')
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
                viewPayrollPaygroupModal(){
                    $('#payroll-paygroups-add-modal').modal('show');
                },
                hidePayrollPaygroupModal(){
                    $('#payroll-paygroups-add-modal').modal('hide');

                }
            },

        });
        let Payroll =  new Vue({
            el: '#payroll_paygroup',
            data:{
                form_data:{
                    paygroup_name:'',
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
                        case 'delete_paygroup':
                            this.deletePaygroup(id,index,name);
                            break;
                        case 'editPaygroup':
                            this.editPaygroup(id,index,name);
                            break;
                    }

                },
                editPaygroup(id)
                {
                    axios.get("/mpe/payroll-paygroup/" + id)
                        .then(function (response) {
                                Payroll.form_data.paygroup_name =response.data[0].name,
                                Payroll.form_data.paygroup_id = id
                                $('#payroll-paygroups-edit-modal').modal('show')
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
                    console.log(Payroll.form_data)
                },
                setPayrollPaygroup(){
                    dropdown.viewPayrollPaygroupModal()
                },
                deletePaygroup(id,index,name){
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You are about to delete  " + name + " from this Paygroups.",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return axios.delete("/mpe/payroll-paygroup/" + id)
                                .then(function (response) {
                                    $('#paygroups-table').bootstrapTable('removeByUniqueId', response.data.id);
                                    return swal("Deleted!", "The Paygroup was successfully deleted.", "success");
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
                deleteValue: function(index){
                    this.computational_fields.splice(index, 1);
                },
                addValue: function() {
                    this.computational_fields.push({range:'',rate:'',isRest:false});
                    // this.$emit('input', this.fields);
                },


            },
            mounted(){
            }
        })

        function processRows(row, index) {
            row.created_at = moment(row.created_at).format('DD MMM, YYYY');
            row.buttons =
                // '<a class="btn btn-sm btn-primary text-white"  data-index="'+index+'"  data-action="editPaygroup" data-id="'+row.id+'" data-name="'+row.name+'">Update</a> &nbsp; ' +
                '<a class="btn btn-sm btn-cyan text-white" href="/mpe/payroll-paygroup/'+ row.id + '">View</a> &nbsp; ' +
                '<a class="btn btn-sm btn-danger text-white"   data-index="'+index+'" data-action="delete_paygroup" data-id="'+row.id+'" data-name="'+row.name+'">Delete</a>'
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
