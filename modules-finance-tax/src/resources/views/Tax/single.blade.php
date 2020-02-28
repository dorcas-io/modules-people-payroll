@extends('layouts.tabler')
@section('head_css')
    <link href="https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('body_content_main')

    @include('layouts.blocks.tabler.alert')

    <div class="row">

        @include('layouts.blocks.tabler.sub-menu')
        <div class="col-md-9 col-xl-9" id="tax_profile">
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-profile">
                        <div class="card-header" v-bind:style="{ 'background-image': 'url(' + backgroundImage + ')' }"></div>
                        <div class="card-body text-center">
                            <img class="card-profile-img" v-bind:src="defaultPhoto">
                            <h3 class="mb-3">@{{ authority.name}}</h3>
                            <button v-on:click.prevent="editAuthority" class="btn btn-outline-primary btn-sm text-center">
                                <span class="fa fa-sliders"></span> Edit Authority
                            </button>
                        </div>
                        @include('modules-finance-tax::modals.edit-tax-authority')

                    </div>
                    <div class="card">
                        <div class="card-status bg-green"></div>
                        <div class="card-header">
                            <h3 class="card-title">Payment Mode & Details</h3>
                        </div>
                        <div class="card-body">
                            <div class="col-sm-12 col-lg-12">
                                Payment Mode: &nbsp;{{$authority->payment_mode}}
                                    <table class="table card-table">
                                        <tbody>
                                        <span class="badge badge-secondary">Primary Bank </span>
                                        <tr v-if="default_bank_detail !== null " >
                                            <td>@{{ default_bank_detail.bank }}</td>
                                            <td>@{{ default_bank_detail.account }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                <table class="table card-table">
                                    <tbody>
                                    <span class="badge badge-primary">Secondary Bank </span>
                                    <tr v-if="bank_details !== 'null' " v-for="bank_detail in bank_details" >
                                        <td>@{{ bank_detail.bank }}</td>
                                        <td>@{{ bank_detail.account }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-status bg-blue"></div>
                        <div class="card-header">
                            <h3 class="card-title">Activity</h3>
                        </div>
                        <div class="card-body">
                            Manage <strong>Elements</strong>, <strong>Run</strong> activities for this Authority:
                            <ul class="nav nav-tabs nav-justified">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#elements">Elements</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane container active o-auto" id="elements">
                                    <br/>
                                    <div class="row section" id="element-list" >
                                        <table class="bootstrap-table responsive-table" v-if="Object.entries(elements).length !== 0 "
                                               data-url="{{ route('element-search') . '?' . http_build_query($args). '&id='.$authority->id }}"
                                               data-page-list="[10,25,50,100,200,300,500]"
                                               data-row-attributes="processElements"
                                               data-side-pagination="server"
                                               data-show-refresh="true"
                                               data-sort-class="sortable"
                                               data-pagination="true"
                                               data-search="true"
                                               data-unique-id="id"
                                               data-id-field="id"
                                               id="elements_table"
                                               data-search-on-enter-key="true"
                                               v-on:click="clickAction($event)">
                                            <thead>
                                            <tr>
                                                <th data-field="name">Element Name</th>
                                                <th data-field="element_type">Element Type</th>
                                                <th data-field="frequency">Frequency</th>
                                                <th data-field="created_at">Added On</th>
                                                <th data-field="taxAuthority.data.name">Authority</th>
                                                <th data-field="buttons">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>

                                        <div class="col s12" v-else>
                                            @component('layouts.blocks.tabler.empty-fullpage')
                                                @slot('title')
                                                    No Elements
                                                @endslot
                                                    Add Tax elements to generate Tax Runs, and keep track of your Taxes.
                                                @slot('buttons')
                                                    <a class="btn btn-primary btn-sm" href="#" v-on:click.prevent="addElement" >Add Element</a>
                                                @endslot
                                            @endcomponent
                                        </div>
                                    </div>
                                    <div class="row mt-2" >
                                        <a class="btn btn-primary btn-sm" href="#" v-on:click.prevent="addElement">Add Element</a>
                                    </div>
                                    &nbsp;
                                </div>
                            </div>
                        </div>
                    </div>
               @include('modules-finance-tax::modals.add-tax-element')
                    @include('modules-finance-tax::modals.edit-tax-element')


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
        const table = $('.bootstrap-table');
      let authority = new Vue({
            el: '#tax_profile',
            data: {
                authority: {!! json_encode($authority) !!},
                elements: {!! json_encode($elements) !!},
                accounts: {!! json_encode($accounts) !!},
                default_bank_detail:{!! ($authority->default_payment_details == null ?  "{ bank: '', account: ''},": $authority->default_payment_details )!!},
                bank_details:{!! ($authority->payment_details == null ? '[]': $authority->payment_details ) !!},
                defaultPhoto: "{{ cdn('images/avatar/avatar-6.png') }}",
                backgroundImage: "{{ cdn('images/gallery/imani-clovis-547617-unsplash.jpg') }}",
                elements_form:{
                    element_name:null,
                    element_type:'',
                    accounts: '',
                    unselected_accounts:'',
                    isPercent:false,
                    isFixed:false,
                    isYearly:false,
                    isMonthly:false,
                    frequency:null,
                    frequency_year:null,
                    frequency_month:null,
                    type_data: {element_type: '', value: ''}

                }
            },
            methods: {
                editAuthority()
                {
                    $('#tax-element-edit-modal').modal('show')
                },
                toggleElementType(e){
                    switch(e.target.value){
                        case 'percentage':
                            this.elements_form.isPercent = true;
                            break;
                        case 'fixed':
                            this.elements_form.isFixed = true;
                            break;
                        default:
                            return false;
                    }
                },
                toggleFrequency(e){
                    switch(e.target.value){
                        case 'yearly':
                            this.elements_form.isMonthly = false;
                            this.elements_form.isYearly = true;

                            break;
                        case 'monthly':
                            this.elements_form.isYearly = false;
                            this.elements_form.isMonthly = true;
                            break;
                        default:
                            return false;
                    }
                },
                validateDay(event){
                    let input_val = event.target.value;
                    if(input_val > max_day){
                       this.elements_form.frequency_month = max_day
                    }

                },
                getAccounts(value){
                    this.elements_form.accounts = value
                    console.log(this.elements_form.accounts)
                },
                addElement(){
                    $('#tax-element-add-modal').modal('show')
                },
                deleteValue: function(index){
                    this.bank_details.splice(index, 1);
                },
                addValue: function() {
                    this.bank_details.push({ bank: '', account: ''});
                    // this.$emit('input', this.fields);
                },
                updateAuthority: function () {
                    $('#edit-authority').addClass('btn-loading btn-icon')
                    let form_data = {
                        default_fields:this.default_bank_detail,
                        fields: (this.bank_details),
                        authority_name:this.authority.name,
                        payment_mode:this.authority.payment_mode.toLowerCase(),
                    }
                    console.log(form_data)
                    axios.put('/mfn/tax-authorities/'+this.authority.id,form_data)
                        .then(response=>{
                            $('#edit-authority').removeClass('btn-loading btn-icon')
                            form_data = {};
                            $('#tax-element-add-modal').modal('hide')

                            swal({
                                title:"Success!",
                                text:"Tax Authority Successfully Updated",
                                type:"success",
                                showLoaderOnConfirm: true,
                            }).then(function () {
                                location.reload()
                            });
                        })
                        .catch(e=>{
                            console.log(e.response.data);
                            $('#edit-authority').removeClass('btn-loading btn-icon')
                            swal.fire({
                                title:"Error!",
                                text:e.response.data.message,
                                type:"error",
                                showLoaderOnConfirm: true,
                            });
                        })
                },
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
                        case 'delete_element':
                            this.deleteElement(id,index,name);
                            break;
                        case 'editElement':
                            this.showElement(id,index,name);
                            break;
                        case 'view_run':
                            location.href='/mfn/tax-runs/'+id;
                            // this.showElement(id,index,name);
                            break;

                    }

                },
                submitElement: function () {
                    $('#submit-element').addClass('btn-loading btn-icon')
                    // console.log(this.form_data)
                    this.elements_form['authority'] = this.authority.id
                    console.log(this.elements_form)
                    axios.post('/mfn/tax-elements',this.elements_form)
                        .then(response=>{
                            $('#submit-element').removeClass('btn-loading btn-icon')
                            this.form_data = {};
                            $('#tax-element-add-modal').modal('hide')

                            swal({
                                title:"Success!",
                                text:"Tax Element Successfully Created",
                                type:"success",
                                showLoaderOnConfirm: true,
                            }).then(function () {
                                location.reload()
                            });
                        })
                        .catch(e=>{
                            console.log(e.response.data);
                            $('#submit-element').removeClass('btn-loading btn-icon')

                            swal.fire({
                                title:"Error!",
                                text:e.response.data.message,
                                type:"error",
                                showLoaderOnConfirm: true,
                            });
                        })
                },
                showElement: function (id) {
                    const self = this;
                    axios.get("/mfn/tax-element/" + id)
                        .then(function (response) {
                            switch(response.data.frequency){
                                case 'yearly':
                                    self.elements_form.isYearly= true;
                                    self.elements_form.isMonthly= false;
                                    break;
                                case 'monthly':
                                    self.elements_form.isYearly= false;
                                    self.elements_form.isMonthly= true;
                                    break;
                            }
                            switch(response.data.element_type){
                                case 'percentage':
                                    self.elements_form.isPercent= true;
                                    break;
                            }
                                self.elements_form.element_name = response.data.name,
                                self.elements_form.element_type = response.data.element_type,
                                self.elements_form.accounts =  response.data.target_accounts_name,
                                self.elements_form.unselected_accounts =  response.data.accounts_name,
                                self.elements_form.frequency= response.data.frequency,
                                self.elements_form.frequency_year= response.data.frequency_year,
                                self.elements_form.frequency_month= response.data.frequency_month,
                                self.elements_form.type_data=  JSON.parse(response.data.type_data)

                            $('#tax-element-edit-modal').modal('show')

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
                deleteElement(id,index,name){
                    Swal.fire({
                        title: "Are you sure?",
                        text: "You are about to delete  " + name + " from this Element.",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Yes, delete it!",
                        showLoaderOnConfirm: true,
                        preConfirm: () => {
                            return axios.delete("/mfn/tax-element/" + id)
                                .then(function (response) {
                                    $('#elements_table').bootstrapTable('removeByUniqueId', response.data.id);
                                    return swal("Deleted!", "The element was successfully deleted.", "success");
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
                }
            },
            mounted(){
                table.bootstrapTable({data: this.elements})
            },
            computed:{
            }
        });

        function processElements(row,index) {
            row.created_at = moment(row.created_at).format('DD MMM, YYYY');
            row.buttons =
                '<a class="text-warning"  data-index="'+index+'"  data-action="editElement" data-id="'+row.id+'" data-name="'+row.name+'"><i class="fe fe-edit-3"></i></a> &nbsp; ' +
                '<a class="text-danger" data-index="'+index+'" data-action="delete_element" data-id="'+row.id+'" data-name="'+row.name+'"><i class="fe fe-trash-2"></i></a> &nbsp;' +
                '<a class="btn btn-purple text-white" data-index="'+index+'" data-action="view_run" data-id="'+row.id+'" data-name="'+row.name+'">Tax Run</a>';

        }

        $(document).ready(function () {
            $('#select-tags-accounts').selectize({
                plugins: ['remove_button'],
            });

            $('#select-tags-advanced').selectize({
                plugins: ['remove_button'],
                onChange: function(value) {
                    console.log(value)
                    authority.getAccounts(value);
                }
            });

            $('.custom-datepicker').datepicker({
                uiLibrary: 'bootstrap4',
                format: 'yyyy-mm-dd'
                /*close: function (e) {
                    vm.due_date = e.target.value;
                }*/
            });
        });



    </script>
@endsection
