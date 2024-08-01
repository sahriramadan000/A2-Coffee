<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Table\AddTableRequest;
use App\Http\Requests\Admin\Table\UpdateTableRequest;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;
use Milon\Barcode\Facades\DNS2DFacade as DNS2D;
use Illuminate\Support\Facades\Crypt;

class TableController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:table-list', ['only' => ['index', 'getTables']]);
        $this->middleware('permission:table-create', ['only' => ['getModalAdd','store']]);
        $this->middleware('permission:table-edit', ['only' => ['getModalEdit','update']]);
        $this->middleware('permission:table-delete', ['only' => ['getModalDelete','destroy']]);
    }

    public function index()
    {
        $data['page_title'] = 'Table List';
        return view('admin.table.index', $data);
    }

    public function getTable(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Table::query())
                ->addIndexColumn()
                ->addColumn('barcode', function ($row) {
                    $barcodeURL = 'data:image/png;base64,' . DNS2D::getBarcodePNG($row->barcode, 'QRCODE');
                    return '<a download="barcode-' . strtolower(str_replace(' ', '', $row->name)) . '.jpg" class="btn btn-primary p-2 f-12" href="' . $barcodeURL . '" title="ImageName">DOWNLOAD</a>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<button type="button" class="btn btn-sm btn-warning tables-edit-table" data-bs-target="#tabs-' . $row->id . '-edit-table">Edit</button>';
                    $btn .= ' <button type="button" class="btn btn-sm btn-danger tables-delete-table"  data-bs-target="#tabs-' . $row->id . '-delete-table">Delete</button>';
                    return $btn;
                })
                ->rawColumns(['barcode', 'action'])
                ->make(true);
        }
    }


    public function getModalAdd()
    {
        $code = $this->generateCode();
        return View::make('admin.table.modal-add')->with([
            'code' => $code
        ]);
    }

    public function generateCode()
    {
        $code = Table::latest()->first();
        if ($code) {
            $code = $code->code;
            $code = substr($code, 4);
            $code = intval($code) + 1;
            $code = 'TBL' . str_pad($code, 5, '0', STR_PAD_LEFT);
        } else {
            $code = 'TBL00001';
        }
        return $code;
    }

    public function store(AddTableRequest $request)
    {
        $dataTable = $request->validated();

         // Encrypt the name
        $encryptedName = Crypt::encryptString($dataTable['name']);

        // Generate the barcode URL with the encrypted name
        $barcode = 'https://a2coffee.jooal.pro/mobile/homepage?kode_meja=' . urlencode($encryptedName);
        
        try {
            $table = new Table();
            $table->code                = $dataTable['code'];
            $table->name                = $dataTable['name'];
            $table->status              = $dataTable['status'];
            $table->status_position     = $dataTable['status_position'];
            $table->barcode             = $barcode;
            $table->save();

            $request->session()->flash('success', "Create data Table successfully!");
            return redirect(route('tables.index'));
        } catch (\Throwable $th) {
            $request->session()->flash('failed', "Failed to create data Table!");
            return redirect(route('tables.index'));
        }
    }

    public function getModalEdit($tableId)
    {
        $table = Table::findOrFail($tableId);
        return View::make('admin.table.modal-edit')->with(
        [
            'table' => $table
        ]);
    }


    public function update(UpdateTableRequest $request, $tableId)
    {
        $dataTable = $request->validated();

         // Encrypt the name
         $encryptedName = Crypt::encryptString($dataTable['name']);

         // Generate the barcode URL with the encrypted name
         $barcode = 'https://a2coffee.jooal.pro/mobile/homepage?kode_meja=' . urlencode($encryptedName);
        try {
            $table = Table::find($tableId);

            // Check if customr$table doesn't exists
            if (!$table) {
                $request->session()->flash('failed', "Table not found!");
                return redirect()->back();
            }

            $table->code               = $dataTable['code'];
            $table->name               = $dataTable['name'];
            $table->status             = $dataTable['status'];
            $table->status_position    = $dataTable['status_position'];
            $table->barcode            = $barcode;
            $table->save();

            $request->session()->flash('success', "Update data Table successfully!");
            return redirect(route('tables.index'));
        } catch (\Throwable $th) {
            $request->session()->flash('failed', "Failed to update data Table!");
            return redirect(route('tables.index'));
        }
    }

    public function getModalDelete($tableId)
    {
        $table = Table::findOrFail($tableId);
        return View::make('admin.table.modal-delete')->with('table', $table);
    }

    public function destroy(Request $request, $tableId)
    {
        try {
            $customer = Table::findOrFail($tableId);
            $customer->delete();

            $request->session()->flash('success', "Delete data Table successfully!");
        } catch (ModelNotFoundException $e) {
            $request->session()->flash('failed', "Table not found!");
        } catch (QueryException $e) {
            $request->session()->flash('failed', "Failed to delete data Table!");
        }

        return redirect(route('tables.index'));
    }
}
