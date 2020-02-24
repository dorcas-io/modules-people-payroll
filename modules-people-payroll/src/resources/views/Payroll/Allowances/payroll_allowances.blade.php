@extends('layouts.tabler')
@section('body_content_header_extras')

@endsection

@section('body_content_main')

    @include('layouts.blocks.tabler.alert')


    <div class="row">

        @include('layouts.blocks.tabler.sub-menu')
        <div class="col-md-9 col-xl-9">
            <div class="row row-cards row-deck " >
                <div class="col-sm-12" id="payroll_allowance">
                    @if(!empty($payroll_allowances))
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap bootstrap-table"
                                   data-pagination="true"
                                   data-search="true"
                                   data-side-pagination="server"
                                   data-show-refresh="true"
                                   data-unique-id="id"
                                   data-id-field="id"
                                   data-row-attributes="processRows"
                                   data-url="{{ route('payroll-allowance-search') . '?' . http_build_query($args) }}"
                                   data-page-list="[10,25,50,100,200,300,500]"
                                   data-sort-class="sortable"
                                   data-search-on-enter-key="true"
                                   id="allowances-table"
                                   v-on:click="clickAction($event)">
                                <thead>
                                <tr>
                                    <th data-field="name">Allowance</th>
                                    <th data-field="allowance_type">Type</th>
                                    <th data-field="model">Type</th>
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
                                    No Payroll Allowance Generated
                                @endslot
                                <a href="#" class="btn btn-primary" v-on:click.prevent="setPayrollAllowance">Add Payroll Allowance</a>
                                &nbsp;
                                @slot('buttons')
                                @endslot
                            @endcomponent
                        </div>
                    @endif
                        @include('modules-people-payroll::Payroll.modals.edit-payroll-allowance')

                </div>

                @include('modules-people-payroll::Payroll.modals.add-payroll-allowance')


            </div>

        </div>
    </div>

@endsection
@section('body_js')
    <script>
        app.currentUser = {!! json_encode($dorcasUser) !!};
        const table = $('.bootstrap-table');
        let  Modal = new Vue({
            el: '#payroll-allowances-add-modal',
            data() {
                return {
                    authorities: {!! $payroll_authorities !!},
                    percentage_data: {  base_ratio:'',employer_base_ratio:'0'},
                    computational_fields: [{range:'',rate:'',isRest:false}],
                    fixed_data:{fixed_type:'fixed',fixed_value:''},
                    form_data:{
                        isPercentage:false,
                        isComputational:false,
                        isFixed:false,
                        authority_id:null,
                        allowance_model:'',
                        model_data:'',
                        allowance_type:'',
                        allowance_name:''

                    }

                }
            },
            methods: {
                deleteValue: function(index){
                    this.computational_fields.splice(index, 1);
                },
                addValue: function() {
                    this.computational_fields.push({range:'',rate:'',isRest:false});
                    // this.$emit('input', this.fields);
                },
                toggleModel(event ){
                    switch(event.target.value){
                        case 'percent_of_base':
                            this.form_data.isPercentage = true;
                            this.form_data.isComputational= false;
                            this.form_data.isFixed = false;
                            this.form_data.model_data = this.percentage_data
                            break;
                        case 'fixed':
                            this.form_data.isPercentage = false;
                            this.form_data.isComputational= false;
                            this.form_data.isFixed = true;
                            this.form_data.model_data = this.fixed_data

                            break;
                        case 'computational':
                            this.form_data.isPercentage = false;
                            this.form_data.isComputational= true;
                            this.form_data.isFixed = false;
                            this.form_data.model_data = this.computational_fields
                            break;

                    }
                },
                submitForm: function () {
                    $('#submit-allowance').addClass('btn-loading btn-icon')
                    axios.post('/mpe/payroll-allowance',this.form_data)
                        .then(response=>{
                            $('#submit-allowance').removeClass('btn-loading btn-icon')
                            this.form_data = {};
                            dropdown.hidePayrollAllowanceModal();
                            swal({
                                title:"Success!",
                                text:"Payroll Allowance Successfully Created",
                                type:"success",
                                showLoaderOnConfirm: true,
                            }).then(function () {
                                location.reload()
                            });
                        })
                        .catch(e=>{
                            console.log(e.response.data);
                            $('#submit-allowance').removeClass('btn-loading btn-icon')
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
                viewPayrollAllowanceModal(){
                    $('#payroll-allowances-add-modal').modal('show');
                },
                hidePayrollAllowanceModal(){
                    $('#payroll-allowances-add-modal').modal('hide');

                }
            },

        });
       let Payroll =  new Vue({
            el: '#payroll_allowance',
            data:{
                allowances: {!! $payroll_allowances !!},
                authorities: {!! $payroll_authorities !!},
                percentage_data: {  base_ratio:'',employer_base_ratio:'0'},
                computational_fields: [{range:'',rate:'',isRest:false}],
                fixed_data:{fixed_type:'fixed',fixed_value:''},

                form_data:{
                    isPercentage:false,
                    isComputational:false,
                    isFixed:false,
                    authority_id:null,
                    allowance_model:'',
                    model_data:'',
                    allowance_type:'',
                    allowance_id:'',
                    allowance_name:'',
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
                        case 'delete_allowance':
                            this.deleteAllowance(id,index,name);
                            break;
                        case 'editAllowance':
                            this.editAllowance(id,index,name);
                            break;
                    }

                },
                editAllowance(id)
                {
                    axios.get("/mpe/payroll-allowance/" + id)
                        .then(function (response) {
                            switch(response.data[0].model){
                                case "percent_of_base":
                                    Payroll.form_data.isPercentage = true;
                                    Payroll.form_data.isCompuatational = false;
                                    Payroll.form_data.isFixed = false;
                                    Payroll.percentage_data = JSON.parse(response.data[0].model_data)
                                    Payroll.form_data.model_data = JSON.parse(response.data[0].model_data)

                                    break;
                                case "fixed":
                                    Payroll.form_data.isPercentage = false;
                                    Payroll.form_data.isCompuatational = false;
                                    Payroll.form_data.isFixed = true;
                                    Payroll.fixed_data = JSON.parse(response.data[0].model_data)
                                    Payroll.form_data.model_data = JSON.parse(response.data[0].model_data)

                                    break;
                                case "computational":
                                    Payroll.form_data.isPercentage = false;
                                    Payroll.form_data.isComputational = true;
                                    Payroll.form_data.isFixed = false;
                                    Payroll.computational_fields = JSON.parse(response.data[0].model_data)
                                    Payroll.form_data.model_data = JSON.parse(response.data[0].model_data)

                                    break;

                            }
                                 Payroll.form_data.authority_id =response.data[0].authority,
                                 Payroll.form_data.allowance_model = response.data[0].model,
                                 Payroll.form_data.allowance_type =response.data[0].allowance_type,
                                 Payroll.form_data.allowance_name =response.data[0].name,
                                 Payroll.form_data.allowance_id = id,

                            $('#payroll-allowances-edit-modal').modal('show')

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
                setPayrollAllowance(){
                    dropdown.viewPayrollAllowanceModal()
                },
                deleteAllowance(id,index,name){
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You are about to delete  " + name + " from this Allowances.",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return axios.delete("/mpe/payroll-allowance/" + id)
                                .then(function (response) {
                                    $('#allowances-table').bootstrapTable('removeByUniqueId', response.data.id);
                                    return swal("Deleted!", "The Allowance was successfully deleted.", "success");
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
                toggleModel(event ){
                    switch(event.target.value){
                        case 'percent_of_base':
                            this.form_data.isPercentage = true;
                            this.form_data.isComputational= false;
                            this.form_data.isFixed = false;
                            break;
                        case 'fixed':
                            this.form_data.isPercentage = false;
                            this.form_data.isComputational= false;
                            this.form_data.isFixed = true;

                            break;
                        case 'computational':
                            this.form_data.isPercentage = false;
                            this.form_data.isComputational= true;
                            this.form_data.isFixed = false;
                            break;

                    }
                },
                updateAllowance: function () {
                    $('#edit-allowance').addClass('btn-loading btn-icon')
                    switch(this.form_data.allowance_model){
                        case 'percent_of_base':
                            this.form_data.model_data = this.percentage_data
                            break;
                        case 'fixed':
                            this.form_data.model_data = this.fixed_data

                            break;
                        case 'computational':
                            this.form_data.model_data = this.computational_fields
                            break;
                    }
                    axios.put('/mpe/payroll-allowance/'+this.form_data.allowance_id,this.form_data)
                        .then(response=>{
                            $('#edit-allowance').removeClass('btn-loading btn-icon')
                            form_data = {};
                            $('#payroll-allowances-edit-modal').modal('hide')

                            swal({
                                title:"Success!",
                                text:"Payroll Allowance Successfully Updated",
                                type:"success",
                                showLoaderOnConfirm: true,
                            }).then(function () {
                                location.reload()
                            });
                        })
                        .catch(e=>{
                            console.log(e.response.data);
                            $('#edit-allowance').removeClass('btn-loading btn-icon')
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
            row.buttons = '<a class="btn btn-sm btn-primary text-white"  data-index="'+index+'"  data-action="editAllowance" data-id="'+row.id+'" data-name="'+row.name+'">Update</a> &nbsp; ' +
                '<a class="btn btn-sm btn-danger text-white"   data-index="'+index+'" data-action="delete_allowance" data-id="'+row.id+'" data-name="'+row.name+'">Delete</a>'
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
