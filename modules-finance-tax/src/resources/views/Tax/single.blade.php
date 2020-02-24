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
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#runs">Run</a>
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
                                               data-unique-id="element_id"
                                               data-search-on-enter-key="true">
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
                    $('#tax-authorities-edit-modal').modal('show')
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
                }
            },
            mounted(){

            },
            computed:{
            }
        });

        function processElements(row,index) {
            row.created_at = moment(row.created_at).format('DD MMM, YYYY');
        }

        $(document).ready(function () {
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
