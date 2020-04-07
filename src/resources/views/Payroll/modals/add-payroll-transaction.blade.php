<div class="modal fade" id="payroll-transactions-add-modal" tabindex="-1" role="dialog" aria-labelledby="entries-add-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add   Transaction</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" @submit.prevent="submitForm()"  id="payroll_transaction_add" method="post">
                    <fieldset>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="form-label" for="transaction">Remarks</label>
                                <input class="form-control" id="transaction" v-model="form_data.remarks" placeholder="Enter Transaction remarks" type="text" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label" for="transaction">Employees</label>
                                <select   class="form-control custom-select selectized" tabindex="-1"  v-model="form_data.employee" required >
                                    <option selected value="null" disabled>Select Employee </option>
                                    <option :value="employee.id" v-for="employee in employees">@{{ employee.firstname + employee.lastname }}</option>
                                </select>

                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label" for="transaction" >Transaction Status  </label>
                                <select  class="form-control custom-select selectized" tabindex="-1" @change="toggleStatus($event)" v-model="form_data.status" >
                                    <option selected value="null">Select Status </option>
                                    <option  value="one_time">Onetime</option>
                                    <option  value="repeat"> Repetitive </option>
                                </select>

                            </div>

                            <div class="form-group col-md-12" v-if="end_time">
                                <label class="form-label" for="transaction">End Time</label>
                                <input class="form-control" v-model="form_data.end_time"  type="date" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label" for="transaction" >Transaction Type  </label>
                                <select  class="form-control custom-select selectized" tabindex="-1"  v-model="form_data.transaction_type" >
                                    <option selected value="null">Select Transaction Type </option>
                                    <option  value="deduction">deduction</option>
                                    <option  value="addition"> addition </option>
                                </select>

                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label" for="transaction" >Amount   </label>
                                <input class="form-control" id="transaction" v-model="form_data.amount" placeholder="Enter amount" type="text" required>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit"  name="action" id="submit-transaction" form="payroll_transaction_add" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>
