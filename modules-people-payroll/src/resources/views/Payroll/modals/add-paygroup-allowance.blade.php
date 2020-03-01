
<div class="modal fade" id="payroll-paygroup-allowance-add-modal" tabindex="-1" role="dialog" aria-labelledby="entries-add-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add  Payroll Allowance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mt-2" >
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <table id="allowance-table" class="display" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th> id </th>
                                        <th>Allowance Name</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($allowances as $allowance)
                                        <tr>
                                            <td>  {{$allowance['id']}}</td>
                                            <td>  {{$allowance['name']}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <button type="button" id="allowance-add-form" class="btn btn-primary pull-left ">Add Allowance(s)</button>

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