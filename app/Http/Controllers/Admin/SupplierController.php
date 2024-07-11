<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Supplier\AddSupplierRequest;
use App\Http\Requests\Admin\Supplier\UpdateSupplierRequest;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;


class SupplierController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:supplier-list', ['only' => ['index', 'getSuppliers']]);
        $this->middleware('permission:supplier-create', ['only' => ['getModalAdd','store']]);
        $this->middleware('permission:supplier-edit', ['only' => ['getModalEdit','update']]);
        $this->middleware('permission:supplier-delete', ['only' => ['getModalDelete','destroy']]);
    }

    public function index()
    {
        $data['page_title'] = 'Supplier List';
        return view('admin.supplier.index', $data);
    }

    public function getSuppliers(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Supplier::query())
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" class="btn btn-sm btn-warning suppliers-edit-table" data-bs-target="#tabs-'.$row->id.'-edit-supplier">Edit</button>';
                $btn = $btn . ' <button type="button" class="btn btn-sm btn-danger suppliers-delete-table"  data-bs-target="#tabs-'.$row->id.'-delete-supplier">Delete</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }

    public function getModalAdd()
    {
        $code = $this->generateCode();
        return View::make('admin.supplier.modal-add')->with('code', $code);
    }

    public function generateCode()
    {
        $code = Supplier::latest()->first();
        if ($code) {
            $code = $code->code;
            $code = substr($code, 1);
            $code = intval($code) + 1;
            $code = 'S' . str_pad($code, 5, '0', STR_PAD_LEFT);
        } else {
            $code = 'S00001';
        }
        return $code;
    }

    public function store(AddSupplierRequest $request)
    {
        $dataSupplier = $request->validated();
        try {
            $supplier = new Supplier();
            $supplier->code = $dataSupplier['code'];
            $supplier->fullname = $dataSupplier['fullname'];
            $supplier->company = $dataSupplier['company'];
            $supplier->address = $dataSupplier['address'];
            $supplier->email = $dataSupplier['email'];
            $supplier->phone = $dataSupplier['phone'];

            $supplier->save();

            $request->session()->flash('success', "Create data supplier successfully!");
            return redirect(route('suppliers.index'));
        } catch (\Throwable $th) {
            $request->session()->flash('failed', "Failed to create data supplier!");
            return redirect(route('suppliers.index'));
        }
    }

    public function getModalEdit($supplierId)
    {
        $supplier = Supplier::findOrFail($supplierId);
        return View::make('admin.supplier.modal-edit')->with('supplier', $supplier);
    }


    public function update(UpdateSupplierRequest $request, $supplierId)
    {
        $dataSupplier = $request->validated();
        try {
            $supplier = Supplier::find($supplierId);

            // Check if supplier doesn't exists
            if (!$supplier) {
                $request->session()->flash('failed', "Supplier not found!");
                return redirect()->back();
            }

            $supplier->code = $dataSupplier['code'];
            $supplier->fullname = $dataSupplier['fullname'];
            $supplier->company = $dataSupplier['company'];
            $supplier->email = $dataSupplier['email'];
            $supplier->phone = $dataSupplier['phone'];
            $supplier->address = $dataSupplier['address'];

            $supplier->save();

            $request->session()->flash('success', "Update data supplier successfully!");
            return redirect(route('suppliers.index'));
        } catch (\Throwable $th) {
            $request->session()->flash('failed', "Failed to update data supplier!");
            return redirect(route('suppliers.index'));
        }
    }

    public function getModalDelete($supplierId)
    {
        $supplier = Supplier::findOrFail($supplierId);
        return View::make('admin.supplier.modal-delete')->with('supplier', $supplier);
    }

    public function destroy(Request $request, $supplierId)
    {
        try {
            $supplier = Supplier::findOrFail($supplierId);
            $supplier->delete();

            $request->session()->flash('success', "Delete data supplier successfully!");
        } catch (ModelNotFoundException $e) {
            $request->session()->flash('failed', "Supplier not found!");
        } catch (QueryException $e) {
            $request->session()->flash('failed', "Failed to delete data supplier!");
        }

        return redirect(route('suppliers.index'));
    }
}
