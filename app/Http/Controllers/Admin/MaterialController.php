<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Material\AddMaterialRequest;
use App\Http\Requests\Admin\Material\UpdateMaterialRequest;
use App\Models\Material;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;

class MaterialController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:material-list', ['only' => ['index', 'getMaterials']]);
        $this->middleware('permission:material-create', ['only' => ['getModalAdd','store']]);
        $this->middleware('permission:material-edit', ['only' => ['getModalEdit','update']]);
        $this->middleware('permission:material-delete', ['only' => ['getModalDelete','destroy']]);
    }

    public function index()
    {
        $data['page_title'] = 'Material List';
        return view('admin.material.index', $data);
    }

    public function getMaterials(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Material::query())
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" class="btn btn-sm btn-warning materials-edit-table" data-bs-target="#tabs-'.$row->id.'-edit-material">Edit</button>';
                $btn = $btn . ' <button type="button" class="btn btn-sm btn-danger materials-delete-table"  data-bs-target="#tabs-'.$row->id.'-delete-material">Delete</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }

    public function getModalAdd()
    {
        $code = $this->generateCode();
        $suppliers = Supplier::select(['id', 'code', 'fullname'])->get();
        return View::make('admin.material.modal-add')->with([
            'code' => $code,
            'suppliers' => $suppliers
        ]);
    }

    public function generateCode()
    {
        $code = Material::latest()->first();
        if ($code) {
            $code = $code->code;
            $code = substr($code, 3);
            $code = intval($code) + 1;
            $code = 'MTR' . str_pad($code, 5, '0', STR_PAD_LEFT);
        } else {
            $code = 'MTR00001';
        }
        return $code;
    }

    public function store(AddMaterialRequest $request)
    {
        $dataMaterial = $request->validated();
        try {
            $material = new Material();
            $material->code             = $dataMaterial['code'];
            $material->name             = $dataMaterial['name'];
            $material->unit             = $dataMaterial['unit'];
            $material->minimum_stock    = $dataMaterial['minimum_stock'];
            $material->supplier_id      = $dataMaterial['supplier_id'];
            $material->description      = $dataMaterial['description'];

            $material->save();

            $request->session()->flash('success', "Create data material successfully!");
            return redirect(route('materials.index'));
        } catch (\Throwable $th) {
            $request->session()->flash('failed', "Failed to create data material!");
            return redirect(route('materials.index'));
        }
    }

    public function getModalEdit($materialId)
    {
        $material = Material::findOrFail($materialId);
        $suppliers = Supplier::select(['id', 'code', 'fullname'])->get();
        return View::make('admin.material.modal-edit')->with(
        [
            'material' => $material,
            'suppliers' => $suppliers
        ]);
    }


    public function update(UpdateMaterialRequest $request, $materialId)
    {
        $dataMaterial = $request->validated();
        try {
            $material = Material::find($materialId);

            // Check if material doesn't exists
            if (!$material) {
                $request->session()->flash('failed', "Material not found!");
                return redirect()->back();
            }

            $material->code             = $dataMaterial['code'];
            $material->name             = $dataMaterial['name'];
            $material->unit             = $dataMaterial['unit'];
            $material->minimum_stock    = $dataMaterial['minimum_stock'];
            $material->supplier_id      = $dataMaterial['supplier_id'];
            $material->description      = $dataMaterial['description'];

            $material->save();

            $request->session()->flash('success', "Update data material successfully!");
            return redirect(route('materials.index'));
        } catch (\Throwable $th) {
            $request->session()->flash('failed', "Failed to update data material!");
            return redirect(route('materials.index'));
        }
    }

    public function getModalDelete($materialId)
    {
        $material = Material::findOrFail($materialId);
        return View::make('admin.material.modal-delete')->with('material', $material);
    }

    public function destroy(Request $request, $materialId)
    {
        try {
            $material = Material::findOrFail($materialId);
            $material->delete();

            $request->session()->flash('success', "Delete data material successfully!");
        } catch (ModelNotFoundException $e) {
            $request->session()->flash('failed', "Material not found!");
        } catch (QueryException $e) {
            $request->session()->flash('failed', "Failed to delete data material!");
        }

        return redirect(route('materials.index'));
    }
}
