<div class="modal fade" id="payroll-run-edit-modal" tabindex="-1" role="dialog" aria-labelledby="entries-add-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update  Payroll Run</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" @submit.prevent="updateRun()"  id="payroll_run_edit" method="put">
                    <fieldset>
                        <div class="row" v-if="currentStep == 1">
                            <div class="form-group col-md-12">
                                <label class="form-label" for="run">Title</label>
                                <input class="form-control" id="run" v-model="form_data.title" placeholder="Enter Run  Title" type="text" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label" for="run">Run</label>
                                <input class="form-control" id="run" v-model="form_data.run" placeholder="Enter Payroll Run Name" type="text" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label" for="run">Run Status</label>
                                <select class="select-dropdown form-control" v-model="form_data.status">
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                            <button  class="btn btn-primary float-right " type="button" @click.prevent="goToStep(2)" >Next Step <i class="fe fe-arrow-right"></i></button>

                        </div>
                        <div class="row" id="edit-tables" style="display: none">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-status bg-blue"></div>
                                    <div class="card-header">
                                        <h3 class="card-title">Activity</h3>
                                    </div>
                                    <div class="card-body">
                                        Add <strong>Paygroups</strong>, <strong>Employees</strong> ,
                                        for this Run:
                                        <ul class="nav nav-tabs nav-justified">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-toggle="tab" href="#edit-paygroups">Paygroups </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link " data-toggle="tab" href="#edit-employees">Employees</a>
                                            </li>

                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane container active o-auto" id="edit-paygroups">
                                                <br/>
                                                <div class="row mt-2" >
                                                    <div class="container ">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <table class="table" id="run_edit_paygroups" >
                                                                    <thead>
                                                                    <tr>
                                                                        <th>id</th>
                                                                        <th>Name</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @foreach($paygroups as $paygroup)
                                                                        <tr>
                                                                            <td>{{$paygroup->id}}</td>
                                                                            <td>{{$paygroup->name}}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                &nbsp;
                                            </div>
                                            <div class="tab-pane container  o-auto" id="edit-employees">
                                                <br/>
                                                <div class="row mt-2" >
                                                    <div class="container">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <table class="table" id="run_edit_employees" >
                                                                    <thead>
                                                                    <tr>
                                                                        <th>id</th>
                                                                        <th>Name</th>
                                                                        <th>Job Title</th>
                                                                        <th>Staff Code</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody >
                                                                    <tbody >
{{--                                                                    @foreach($employees as $employee)--}}
{{--                                                                        <tr>--}}
{{--                                                                            <td>{{$employee->id}}</td>--}}
{{--                                                                            <td>{{$employee->firstname}}</td>--}}
{{--                                                                            <td>{{$employee->job_title}}</td>--}}
{{--                                                                            <td>{{$employee->staff_code}}</td>--}}
{{--                                                                        </tr>--}}
{{--                                                                    @endforeach--}}
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                &nbsp;
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <button  class="btn btn-primary float-left " type="button" @click.prevent="goToStep(1)">Previous Step <i class="fe fe-arrow-left"></i></button>

                        </div>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit"  name="action" id="edit-run" form="payroll_run_add" class="btn btn-primary" v-if="currentStep==2">Submit</button>
            </div>
        </div>
    </div>
</div>
