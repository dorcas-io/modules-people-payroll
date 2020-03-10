<div class="modal fade" id="payroll-allowances-edit-modal" tabindex="-1" role="dialog" aria-labelledby="entries-add-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update  Payroll Allowance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" @submit.prevent="updateAllowance()"  id="payroll_allowance_edit">
                    <fieldset>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="form-label" for="allowance">Name</label>
                                <input class="form-control" id="allowance" v-model="form_data.allowance_name" placeholder="Enter Payroll Alloance Name" type="text" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label" for="allowance">Allowance Type</label>
                                <select   class="form-control custom-select selectized" tabindex="-1"  v-model="form_data.allowance_type" required >
                                    <option selected value="" disabled>Select Allowance Type</option>
                                    <option value="benefit">Benefit</option>
                                    <option value="deduction">Deduction</option>
                                </select>

                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label" for="allowance" >Authority ( Optional)  </label>
                                <select  class="form-control custom-select selectized" tabindex="-1"  v-model="form_data.authority_id" >
                                    <option  :value="authority.id" v-for="authority in authorities">@{{ authority.name }}</option>
                                </select>

                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label" for="allowance" >Allowance Model</label>
                                <select   class="form-control custom-select selectized" @change="toggleModel($event)"  v-model="form_data.allowance_model"tabindex="-1" required >
                                    <option selected value="" disabled>Select Allowance Model</option>
                                    <option value="percent_of_base">Percentage of Base</option>
                                    <option value="fixed">Fixed</option>
                                    <option value="computational">Computational</option>
                                </select>

                            </div>
                            <div class="form-group col-md-12"  v-if="form_data.isFixed">
                                <label class="form-label" >Fixed</label>
                                <div class="form-row">
                                    <input type="hidden" v-model='fixed_data.fixed_type' class="form-control" >
                                    <div class="col-md-6">
                                        <input type="text" placeholder="value" v-model="fixed_data.fixed_value" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12" v-if="form_data.isComputational" >
                                <label class="form-label" >Computational </label>
                                <div class="form-row" v-for="(item,index) in computational_fields" >
                                    <div class="col-md-3 mr-4 mt-3">
                                        <input type="text" placeholder="Taxable Income" v-model="item.range" class="form-control" >
                                    </div>
                                    <div class="col-md-3  mr-4 mt-3">
                                        <input type="text" placeholder="Rate" class="form-control" v-model="item.rate" >
                                    </div>
                                    <label class=" col-md-3  mt-4 form-check form-switch" v-if="computational_fields.lastIndexOf(item) + 1  == computational_fields.length ">
                                        <input class="form-check-input form-control" type="checkbox" :checked="item.isRest" v-model="item.isRest">
                                        <span class="form-check-label">Rest Income</span>
                                    </label>
                                    <div class="">
                                        <button type="button" class=" mt-2 btn btn-icon btn-primary btn-danger"  @click="deleteValue(index)" ><i class="fe fe-trash"></i></button>
                                    </div>
                                </div>
                                <button type="button" class="mt-4 btn btn-outline-primary" @click="addValue()"><i class="fe fe-plus mr-2"></i>More More Computations</button>

                            </div>
                            <div class="form-group col-md-12" v-if="form_data.isPercentage">
                                <label class="form-label" >Percentage of Base </label>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <label class="form-label"> Base  Ratio </label>
                                        <input type="text" placeholder="Base Ratio"   v-model='percentage_data.base_ratio' class="form-control" >
                                    </div>
                                    <div class="col-md-6 mt-3 mt-lg-0">
                                        <label class="form-label"> Base Employer Ratio </label>

                                        <input type="text" placeholder="Employer Base Ratio"  v-model='percentage_data.employer_base_ratio' class="form-control mt-2 mt-lg-0" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit"  name="action" id="edit-allowance" form="payroll_allowance_edit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>
