<?php

namespace Dorcas\ModulesPeoplePayroll\Http\Controllers;
use App\Dorcas\Hub\Utilities\UiResponse\UiResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use Hostville\Dorcas\Sdk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ModulesPeoplePayrollController extends Controller
{
    public function __construct()
    {
        $this->data = [
            'page' => ['title' => config('modules-people.title')],
            'header' => ['title' => config('modules-people-payroll.title')],
            'selectedMenu' => 'modules-people-payroll',
            'submenuConfig' => 'navigation-menu.modules-people.sub-menu',
            'submenuAction' => ''
        ];
    }

    public function index(Request $request, Sdk $sdk){
        try{
            $this->data['page']['title'] .= ' &rsaquo; Payroll';
            $this->data['header']['title'] = 'People Payroll';
            $this->data['selectedSubMenu'] = 'payroll-main';
            $this->data['submenuAction'] = '';

            $this->setViewUiResponse($request);

            return view('modules-people-payroll::Payroll/index',$this->data);
        }catch (\Exception $e){
            $this->setViewUiResponse($request);
            return view('modules-people-payroll::Payroll/index',$this->data);
        }


    }



    public function authorityIndex(Request $request, Sdk $sdk, string $id = null){
        try {
            $this->data['page']['title'] .= ' &rsaquo; Payroll Authority';
            $this->data['header']['title'] = 'People Payroll Authority';
            $this->data['submenuAction'] = '';
            $this->setViewUiResponse($request);
            $this->data['args'] = $request->query->all();
            $this->data['payroll_authorities'] = $this->getPeoplePayrollAuthorities($sdk);
            switch ($this->data){
                case !empty($this->data['payroll_authorities']):
                    $this->data['submenuAction'] .= '
                    <div class="dropdown"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Actions</button>
                            <div class="dropdown-menu">
                          <a href="#" data-toggle="modal" data-target="#payroll-authorities-add-modal" class="dropdown-item">Add Payroll Authority</a>
                          </div>
                          </div>';

            }
            return view('modules-people-payroll::Payroll/Authority/payroll_authority', $this->data);

        }
        catch (\Exception $e){
            $this->setViewUiResponse($request);
            return view('modules-people-payroll::Payroll/Authority/payroll_authority', $this->data);

        }

    }

    public function createAuthority(Request $request ,Sdk $sdk){
        try{
            $resource = $sdk->createPayrollResource();
            $resource = $resource->addBodyParam('authority_name',$request->authority_name)
                ->addBodyParam('payment_mode',$request->payment_type)
                ->addBodyParam('payment_details',$request->fields)
                ->addBodyParam('default_payment_details',$request->default_fields);
            $response = $resource->send('post',['authority']);
            if (!$response->isSuccessful()) {
                $message = $response->errors[0]['title'] ?? '';
                throw new \RuntimeException('Failed while adding the Payroll Authority '.$message);

            }
            return response()->json(['message'=>'Payroll Authority Created Successfully'],200);

        }
        catch (\Exception $e){
            return response()->json(['message'=>$e->getMessage()],400);


        }
    }

    public function searchAuthority(Request $request, Sdk $sdk)
    {

        $search = $request->query('search', '');
        $offset = (int) $request->query('offset', 0);
        $limit = (int) $request->query('limit', 10);

        # get the request parameters
        $path = ['authority'];

        $query = $sdk->createPayrollResource();
        $query = $query->addQueryArgument('limit', $limit)
            ->addQueryArgument('page', get_page_number($offset, $limit));
        if (!empty($search)) {
            $query = $query->addQueryArgument('search', $search);
        }
        $response = $query->send('get', $path);
        # make the request
        if (!$response->isSuccessful()) {
            // do something here
            throw new RecordNotFoundException($response->errors[0]['title'] ?? 'Could not find any matching authorities.');
        }
        $this->data['total'] = $response->meta['pagination']['total'] ?? 0;
        # set the total
        $this->data['rows'] = $response->data;
        # set the data
        return response()->json($this->data);
    }

    public function deleteAuthority(Request $request, Sdk $sdk, string $id){
        try{
            $resource = $sdk->createPayrollResource();
            $response = $resource->send('delete', ['authority',$id]);
            if (!$response->isSuccessful()) {
                throw new \RuntimeException($response->errors[0]['title'] ?? 'Failed while deleting the authority.');

            }
            $this->data = $response->getData();
            return response()->json($this->data);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function updateAuthority(Request $request, Sdk $sdk, string $id)
    {
        try {
            $resource = $sdk->createPayrollResource();
            $resource = $resource->addBodyParam('authority_name', $request->authority_name)
                ->addBodyParam('payment_mode', $request->payment_type)
                ->addBodyParam('payment_details', json_encode($request->fields))
                ->addBodyParam('default_payment_details', json_encode($request->default_fields));
            $response = $resource->send('put', ['authority',$id]);
            if (!$response->isSuccessful()) {
                $message = $response->errors[0]['title'] ?? '';
                throw new \RuntimeException('Failed while adding the Payroll Authority ' . $message);

            }
            return response()->json(['message' => 'Payroll Authority Updated Successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function singleAuthority(Request $request , Sdk $sdk, string $id){
        try {
            $response = $sdk->createPayrollResource()->send('get',['authority',$id]);
            if(!$response->isSuccessful()){
                throw new RecordNotFoundException($response->errors[0]['title'] ?? 'Could not find the authority');
            }
            $authority = $response->getData(true);
            return response()->json([$authority, 200]);
        }
        catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);

        }
    }




    private function getPayrollAlowances(Sdk $sdk, string $id = null){
        $sdk = $sdk ?: app(Sdk::class);
        $company = auth()->user()->company(true, true);
        # get the company

        $allowances = Cache::remember('payroll.allowances.'.$company->id, 30, function () use ($sdk) {
            $response = $sdk->createPayrollResource()->addQueryArgument('limit', 10000)
                ->send('get', ['allowance']);
            if (!$response->isSuccessful()) {
                return null;
            }
            return collect($response->getData())->map(function ($allowances) {
                return (object) $allowances;
            });
        });
        return $allowances;
    }

    public function allowanceIndex(Request $request, Sdk $sdk, string $id = null){
        try {
            $this->data['page']['title'] .= ' &rsaquo; Payroll Allowance';
            $this->data['header']['title'] = 'People Payroll Allowance';
            $this->data['submenuAction'] = '';
            $this->setViewUiResponse($request);
            $this->data['args'] = $request->query->all();
            $this->data['payroll_allowances'] = $this->getPayrollAlowances($sdk);
            $this->data['payroll_authorities'] = $this->getPeoplePayrollAuthorities($sdk);
            switch ($this->data){
                case !empty($this->data['payroll_allowances']):
                    $this->data['submenuAction'] .= '
                    <div class="dropdown"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Actions</button>
                            <div class="dropdown-menu">
                          <a href="#" data-toggle="modal" data-target="#payroll-allowances-add-modal" class="dropdown-item">Add Payroll Allowance</a>
                          </div>
                          </div>';

            }
            return view('modules-people-payroll::Payroll/Allowances/payroll_allowances', $this->data);

        }
        catch (\Exception $e){
            $this->setViewUiResponse($request);
            return view('modules-people-payroll::Payroll/Authority/payroll_authority', $this->data);

        }

    }

    public function searchAllowance(Request $request, Sdk $sdk)
    {

        $search = $request->query('search', '');
        $offset = (int) $request->query('offset', 0);
        $limit = (int) $request->query('limit', 10);

        # get the request parameters
        $path = ['allowance'];

        $query = $sdk->createPayrollResource();
        $query = $query->addQueryArgument('limit', $limit)
            ->addQueryArgument('page', get_page_number($offset, $limit));
        if (!empty($search)) {
            $query = $query->addQueryArgument('search', $search);
        }
        $response = $query->send('get', $path);
        # make the request
        if (!$response->isSuccessful()) {
            // do something here
            throw new RecordNotFoundException($response->errors[0]['title'] ?? 'Could not find any matching allowances.');
        }
        $this->data['total'] = $response->meta['pagination']['total'] ?? 0;
        # set the total
        $this->data['rows'] = $response->data;
        # set the data
        return response()->json($this->data);
    }

    public function createAllowance(Request $request, Sdk $sdk){
        try{
            $resource = $sdk->createPayrollResource();
            $resource = $resource->addBodyParam('allowance_name',$request->allowance_name)
                ->addBodyParam('allowance_type',$request->allowance_type)
                ->addBodyParam('model',$request->allowance_model)
                ->addBodyParam('model_data',json_encode($request->model_data));
            if($request->has('authority_id')){
                $resource->addBodyParam('authority',$request->authority_id);
            }
            $response = $resource->send('post',['allowance']);
            if (!$response->isSuccessful()) {
                $message = $response->errors[0]['title'] ?? '';
                throw new \RuntimeException('Failed while adding the Payroll Allowance '.$message);

            }
            return response()->json(['message'=>'Payroll Allowance Created Successfully'],200);

        }
        catch (\Exception $e){
            return response()->json(['message'=>$e->getMessage()],400);


        }
    }

    public function deleteAllowance(Request $request, Sdk $sdk, string $id){
        try{
            $resource = $sdk->createPayrollResource();
            $response = $resource->send('delete', ['allowance',$id]);
            if (!$response->isSuccessful()) {
                throw new \RuntimeException($response->errors[0]['title'] ?? 'Failed while deleting the allowance.');
            }
            $this->data = $response->getData();
            return response()->json($this->data);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function singleAllowance(Request $request , Sdk $sdk, string $id){
        try {
            $response = $sdk->createPayrollResource()->send('get',['allowance',$id]);
            if(!$response->isSuccessful()){
                throw new RecordNotFoundException($response->errors[0]['title'] ?? 'Could not find the allowance');
            }
            $authority = $response->getData(true);
            return response()->json([$authority, 200]);
        }
        catch (\Exception $e){
            return response()->json(['message' => $e->getMessage()], 400);

        }
    }

    public function updateAllowance(Request $request, Sdk $sdk, string $id)
    {
        try {
            $resource = $sdk->createPayrollResource();
            $resource = $resource->addBodyParam('allowance_name',$request->allowance_name)
                ->addBodyParam('allowance_type',$request->allowance_type)
                ->addBodyParam('model',$request->allowance_model)
                ->addBodyParam('model_data',json_encode($request->model_data));
            if($request->has('authority_id')){
                $resource->addBodyParam('authority',$request->authority_id);
            }
            $response = $resource->send('put', ['allowance',$id]);
            if (!$response->isSuccessful()) {
                $message = $response->errors[0]['title'] ?? '';
                throw new \RuntimeException('Failed while adding the Payroll Allowance ' . $message);

            }
            return response()->json(['message' => 'Payroll Allowance Updated Successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    private function getPayrollPaygroups(Sdk $sdk, string $id = null){
        $sdk = $sdk ?: app(Sdk::class);
        $company = auth()->user()->company(true, true);
        # get the company

        $allowances = Cache::remember('payroll.paygroups.'.$company->id, 30, function () use ($sdk) {
            $response = $sdk->createPayrollResource()->addQueryArgument('limit', 10000)
                ->send('get', ['paygroup']);
            if (!$response->isSuccessful()) {
                return null;
            }
            return collect($response->getData())->map(function ($allowances) {
                return (object) $allowances;
            });
        });
        return $allowances;
    }

    public function paygroupIndex(Request $request, Sdk $sdk, string $id = null){
        try {
            $this->data['page']['title'] .= ' &rsaquo; Payroll Pay Group';
            $this->data['header']['title'] = 'People Payroll Pay Group';
            $this->data['submenuAction'] = '';
            $this->setViewUiResponse($request);
            $this->data['args'] = $request->query->all();
            $this->data['payroll_paygroups'] = $this->getPayrollPaygroups($sdk);
            switch ($this->data){
                case !empty($this->data['payroll_paygroups']):
                    $this->data['submenuAction'] .= '
                    <div class="dropdown"><button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Actions</button>
                            <div class="dropdown-menu">
                          <a href="#" data-toggle="modal" data-target="#payroll-paygroups-add-modal" class="dropdown-item">Add Payroll Paygroup</a>
                          </div>
                          </div>';

            }
            return view('modules-people-payroll::Payroll/Paygroup/payroll_paygroup', $this->data);

        }
        catch (\Exception $e){
            $this->setViewUiResponse($request);
            return view('modules-people-payroll::Payroll/Paygroup/payroll_paygroup', $this->data);

        }

    }

    public function searchPaygroup(Request $request, Sdk $sdk)
    {

        $search = $request->query('search', '');
        $offset = (int) $request->query('offset', 0);
        $limit = (int) $request->query('limit', 10);

        # get the request parameters
        $path = ['paygroup'];

        $query = $sdk->createPayrollResource();
        $query = $query->addQueryArgument('limit', $limit)
            ->addQueryArgument('page', get_page_number($offset, $limit));
        if (!empty($search)) {
            $query = $query->addQueryArgument('search', $search);
        }
        $response = $query->send('get', $path);
        # make the request
        if (!$response->isSuccessful()) {
            // do something here
            throw new RecordNotFoundException($response->errors[0]['title'] ?? 'Could not find any matching paygroup.');
        }
        $this->data['total'] = $response->meta['pagination']['total'] ?? 0;
        # set the total
        $this->data['rows'] = $response->data;
        # set the data
        return response()->json($this->data);
    }

    public function createPaygroup(Request $request, Sdk $sdk){
        try{
            $resource = $sdk->createPayrollResource();
            $resource = $resource->addBodyParam('group_name',$request->paygroup_name);
            $response = $resource->send('post',['paygroup']);
            if (!$response->isSuccessful()) {
                $message = $response->errors[0]['title'] ?? '';
                throw new \RuntimeException('Failed while adding the Payroll Paygroup '.$message);

            }
            return response()->json(['message'=>'Payroll Paygroup Created Successfully'],200);

        }
        catch (\Exception $e){
            return response()->json(['message'=>$e->getMessage()],400);


        }
    }

    public function deletePaygroup(Request $request, Sdk $sdk, string $id){
        try{
            $resource = $sdk->createPayrollResource();
            $response = $resource->send('delete', ['paygroup',$id]);
            if (!$response->isSuccessful()) {
                throw new \RuntimeException($response->errors[0]['title'] ?? 'Failed while deleting the paygroup.');
            }
            $this->data = $response->getData();
            return response()->json($this->data);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function singlePaygroup(Request $request , Sdk $sdk, string $id){
        try {
            $this->data['page']['title'] .= ' &rsaquo; Payroll ';
            $this->data['header']['title'] = 'People Pay Group';
            $this->data['submenuAction'] = '';
            $this->data['args'] = $request->query->all();
            $this->setViewUiResponse($request);
            $response = $sdk->createPayrollResource()->send('get',['paygroup',$id]);

            if(!$response->isSuccessful()){

                $response = (tabler_ui_html_response(['Could not find the Payroll Paygroup']))->setType(UiResponse::TYPE_ERROR);
                return redirect(url()->route('payroll-paygroup'))->with('UiResponse', $response);
            }
            $paygroup = $response->getData(true);
            $this->data['paygroup'] = $paygroup;
            return view('modules-people-payroll::Payroll/Paygroup/single', $this->data);

        }
        catch (\Exception $e){
            return view('modules-finance-tax::Tax/single',$this->data);
        }
    }

    public function updatePaygroup(Request $request, Sdk $sdk, string $id)
    {
        try {
            $resource = $sdk->createPayrollResource();
            $resource = $resource->addBodyParam('group_name',$request->paygroup_name);
            $response = $resource->send('put', ['paygroup',$id]);
            if (!$response->isSuccessful()) {
                $message = $response->errors[0]['title'] ?? '';
                throw new \RuntimeException('Failed while adding the Payroll Paygroup ' . $message);

            }
            return response()->json(['message' => 'Payroll Paygroup Updated Successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    public function searchEmployee(Request $request, Sdk $sdk){
        $search = $request->query('search', '');
        $offset = (int) $request->query('offset', 0);
        $limit = (int) $request->query('limit', 10);

        # get the request parameters
        $path = ['paygroup'];

        $query = $sdk->createEmployeeResource();
        $query = $query->addQueryArgument('limit', $limit)
            ->addQueryArgument('page', get_page_number($offset, $limit));
        if (!empty($search)) {
            $query = $query->addQueryArgument('search', $search);
        }
        $response = $query->send('get', $path);
        # make the request
        if (!$response->isSuccessful()) {
            // do something here
            throw new RecordNotFoundException($response->errors[0]['title'] ?? 'Could not find any matching paygroup.');
        }
        $this->data['total'] = $response->meta['pagination']['total'] ?? 0;
        # set the total
        $this->data['rows'] = $response->data;
        # set the data
        return response()->json($this->data);
        }
}