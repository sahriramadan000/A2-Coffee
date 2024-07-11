<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Customer\AddCustomerRequest;
use App\Http\Requests\Admin\Customer\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;


class CustomerController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:customer-list', ['only' => ['index', 'getCustomers']]);
        $this->middleware('permission:customer-create', ['only' => ['getModalAdd','store']]);
        $this->middleware('permission:customer-edit', ['only' => ['getModalEdit','update']]);
        $this->middleware('permission:customer-delete', ['only' => ['getModalDelete','destroy']]);
    }

    public function index()
    {
        $data['page_title'] = 'Customer List';
        return view('admin.customer.index', $data);
    }

    public function getCustomers(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Customer::query())
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" class="btn btn-sm btn-warning customers-edit-table" data-bs-target="#tabs-'.$row->id.'-edit-customer">Edit</button>';
                $btn = $btn . ' <button type="button" class="btn btn-sm btn-danger customers-delete-table"  data-bs-target="#tabs-'.$row->id.'-delete-customer">Delete</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }

    public function getModalAdd()
    {
        $code = $this->generateCode();
        return View::make('admin.customer.modal-add')->with([
            'code' => $code
        ]);
    }

    public function generateCode()
    {
        $code = Customer::latest()->first();
        if ($code) {
            $code = $code->code;
            $code = substr($code, 4);
            $code = intval($code) + 1;
            $code = 'CUST' . str_pad($code, 5, '0', STR_PAD_LEFT);
        } else {
            $code = 'CUST00001';
        }
        return $code;
    }

    public function store(AddCustomerRequest $request)
    {
        $dataCustomer = $request->validated();
        try {
            $customer = new Customer();
            $customer->code               = $dataCustomer['code'];
            $customer->name               = $dataCustomer['name'];
            $customer->email              = $dataCustomer['email'];
            $customer->phone              = $dataCustomer['phone'];
            $customer->gender             = $dataCustomer['gender'];
            $customer->address            = $dataCustomer['address'];
            $customer->total_transaction  = 0;

            $customer->save();

            $request->session()->flash('success', "Create data customer successfully!");
            return redirect(route('customers.index'));
        } catch (\Throwable $th) {
            $request->session()->flash('failed', "Failed to create data customer!");
            return redirect(route('customers.index'));
        }
    }

    public function getModalEdit($customerId)
    {
        $customer = Customer::findOrFail($customerId);
        return View::make('admin.customer.modal-edit')->with(
        [
            'customer' => $customer
        ]);
    }


    public function update(UpdateCustomerRequest $request, $customerId)
    {
        $dataCustomer = $request->validated();
        try {
            $customer = Customer::find($customerId);

            // Check if customr$customer doesn't exists
            if (!$customer) {
                $request->session()->flash('failed', "Customer not found!");
                return redirect()->back();
            }

            $customer->code               = $dataCustomer['code'];
            $customer->name               = $dataCustomer['name'];
            $customer->email              = $dataCustomer['email'];
            $customer->phone              = $dataCustomer['phone'];
            $customer->gender             = $dataCustomer['gender'];
            $customer->address            = $dataCustomer['address'];

            $customer->save();

            $request->session()->flash('success', "Update data customer successfully!");
            return redirect(route('customers.index'));
        } catch (\Throwable $th) {
            $request->session()->flash('failed', "Failed to update data customer!");
            return redirect(route('customers.index'));
        }
    }

    public function getModalDelete($customerId)
    {
        $customer = Customer::findOrFail($customerId);
        return View::make('admin.customer.modal-delete')->with('customer', $customer);
    }

    public function destroy(Request $request, $customerId)
    {
        try {
            $customer = Customer::findOrFail($customerId);
            $customer->delete();

            $request->session()->flash('success', "Delete data customer successfully!");
        } catch (ModelNotFoundException $e) {
            $request->session()->flash('failed', "Customer not found!");
        } catch (QueryException $e) {
            $request->session()->flash('failed', "Failed to delete data customer!");
        }

        return redirect(route('customers.index'));
    }
}
