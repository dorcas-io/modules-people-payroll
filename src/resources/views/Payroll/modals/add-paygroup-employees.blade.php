
<div class="modal fade" id="payroll-paygroup-employee-add-modal" tabindex="-1" role="dialog" aria-labelledby="entries-add-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add   Employees</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mt-2" >
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <table id="employee-table" class="table" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th> id </th>
                                        <th>Staff Code</th>
                                        <th>First Name</th>
                                        <th>Last  Name</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($employees as $employee)
                                        <tr>
                                            <td>  {{$employee->id}}</td>
                                            <td>  {{$employee->staff_code}}</td>
                                            <td>  {{$employee->firstname}}</td>
                                            <td>  {{$employee->lastname}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <button type="button" id="employee-add-form" class="btn btn-primary pull-left ">Submit</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>