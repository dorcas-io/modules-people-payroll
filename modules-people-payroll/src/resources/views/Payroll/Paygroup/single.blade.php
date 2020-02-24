@extends('layouts.tabler')
@section('head_css')
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
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
                            Manage <strong>Employees</strong>, <strong>Allowances</strong> , <strong>Paygroup Runs</strong>
                            for this Paygroup:
                            <ul class="nav nav-tabs nav-justified">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#employees">Elements</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#allowances">Allowances </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane container active o-auto" id="elements">
                                    <br/>
                                    <div class="row section" id="employees-list" >
                                        <table class="bootstrap-table responsive-table"
                                               data-url="{{ route('payroll-employee-search') . '?' . http_build_query($args). '&id='.$paygroup->id }}"
                                               data-page-list="[10,25,50,100,200,300,500]"
                                               data-row-attributes="processEmployees"
                                               data-side-pagination="server"
                                               data-show-refresh="true"
                                               data-sort-class="sortable"
                                               data-pagination="true"
                                               data-search="true"
                                               data-unique-id="employee_id"
                                               data-search-on-enter-key="true">
                                            <thead>
                                            <tr>
                                                <th data-field="name">Employee Name</th>

                                                <th data-field="buttons">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                        <div class="col s12" v-else>
                                            @component('layouts.blocks.tabler.empty-fullpage')
                                                @slot('title')
                                                    No Employees
                                                @endslot
                                                Add Paygroup Employees to generate Paygroup Runs, and keep track of your Taxes.
                                                @slot('buttons')
                                                    <a class="btn btn-primary btn-sm" href="#" v-on:click.prevent="addEmployees" >Add Element</a>
                                                @endslot
                                            @endcomponent
                                        </div>
                                    </div>
                                    <div class="row mt-2" >
                                        <a class="btn btn-primary btn-sm" href="#" v-on:click.prevent="addEmployees">Add Element</a>
                                    </div>
                                    &nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('modules-people-payroll::Payroll.modals.add-paygroup-employees')

                </div>

            </div>
        </div>
    </div>

@endsection
@section('body_js')
    <script src="https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        const max_day = 28;
        const min_day = 1;
        let Paygroup = new Vue({
            el: '#paygroup_profile',
            data: {
                paygroup: {!! json_encode($paygroup) !!},
                defaultPhoto: "{{ cdn('images/avatar/avatar-7.png') }}",
                backgroundImage: "{{ cdn('images/gallery/rawpixel-com-579246-unsplash.jpg') }}",
                form_data:{
                    paygroup_name: ''
                }
            },
            methods: {
                editPaygroup()
                {
                    $('#payroll-paygroups-edit-modal').modal('show')
                },
                addEmployee(){
                    $('#payroll-paygroup-employee-add-modal').modal('show')
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

            },
            mounted(){
                console.log(this.form_data)
            },
            computed:{
            }
        });

        function processElements(row,index) {
            // row.created_at = moment(row.created_at).format('DD MMM, YYYY');
        }




    </script>
@endsection
