<div class="modal fade" id="payroll-transactions-edit-modal" tabindex="-1" role="dialog" aria-labelledby="entries-add-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update  Transaction</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" @submit.prevent="updateTransaction()"  id="payroll_transaction_edit" method="post">
                    <fieldset>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="form-label" for="transaction">Remarks</label>
                                <input class="form-control" id="transaction" v-model="form_data.remarks" placeholder="Enter Transaction remarks" type="text" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label" for="transaction">Employees</label>
                                <select   class="form-control custom-select "  v-model="form_data.selected_employee" required >
                                    <option  value="form_data.employee.id" > @{{ form_data.employee.firstname  }} </option>
                                    <option :value="employee.id" v-for="(employee,index) in employees" :key="index">@{{ employee.firstname + employee.lastname }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label" for="transaction" >Transaction Status  </label>
                                <select  class="form-control custom-select selectized" @change="toggleStatus($event)" tabindex="-1"  v-model="form_data.status_type" >
                                    <option selected :value="form_data.status_type">@{{ form_data.status_type }}</option>
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
                                    <option selected :value="form_data.transaction_type"> @{{ form_data.transaction_type }}</option>
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
                <button type="submit"  name="action" id="edit-transaction" form="payroll_transaction_edit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>
