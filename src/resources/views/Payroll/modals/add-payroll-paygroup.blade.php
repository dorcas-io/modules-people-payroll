<div class="modal fade" id="payroll-paygroups-add-modal" tabindex="-1" role="dialog" aria-labelledby="entries-add-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add  Payroll Pay Group</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" @submit.prevent="submitForm()"  id="payroll_paygroup_add" method="post">
                    <fieldset>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="form-label" for="paygroup">Name</label>
                                <input class="form-control" id="paygroup" v-model="form_data.paygroup_name" placeholder="Enter Payroll Alloance Name" type="text" required>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit"  name="action" id="submit-paygroup" form="payroll_paygroup_add" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>
