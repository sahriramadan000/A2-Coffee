<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Addon\AddAddonRequest;
use App\Http\Requests\Admin\Addon\UpdateAddonRequest;
use App\Models\Addon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class AddonController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:addon-list', ['only' => ['index', 'getAddons']]);
        $this->middleware('permission:addon-create', ['only' => ['getModalAdd','store']]);
        $this->middleware('permission:addon-edit', ['only' => ['getModalEdit','update']]);
        $this->middleware('permission:addon-delete', ['only' => ['getModalDelete','destroy']]);
    }

    public function index()
    {
        $data['page_title'] = 'Addon List';
        return view('admin.addon.index', $data);
    }

    public function getAddons(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Addon::query()
            ->whereNull('parent_id')
            ->orderBy('position', 'asc')
            )
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" class="btn btn-sm btn-warning addons-edit-table" data-bs-target="#tabs-'.$row->id.'-edit-addon">Edit</button>';
                $btn = $btn . ' <button type="button" class="btn btn-sm btn-danger addons-delete-table"  data-bs-target="#tabs-'.$row->id.'-delete-addon">Delete</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }

    public function getModalAdd()
    {
        $parents = Addon::whereNull('parent_id')->get();
        return View::make('admin.addon.modal-add')->with(['parents' => $parents]);
    }

    public function store(AddAddonRequest $request)
    {
        $dataAddon = $request->validated();
        try {
            $parentId = $request->input('parent_id');
            $isPrice = $parentId !== null ? true : false;

            $addon = new Addon();
            $addon->name            = $dataAddon['name'];
            $addon->position        = $dataAddon['position'];
            $addon->is_price        = $isPrice;
            $addon->price           = (int) str_replace('.', '',$dataAddon['price']);
            $addon->parent_id       = $dataAddon['parent_id'];
            $addon->choose          = $dataAddon['choose'] ?? null;
            $addon->status_optional = $dataAddon['status_optional'] ?? null;
            $addon->status          = $dataAddon['status'];

            $addon->save();

            $request->session()->flash('success', "Create data addon successfully!");
            return redirect(route('addons.index'));
        } catch (\Throwable $th) {
            $request->session()->flash('failed', "Failed to create data addon!");
            return redirect(route('addons.index'));
        }
    }

    public function getModalDetail($addonId)
    {
        $childrens = Addon::where('parent_id', $addonId)->orderBy('position', 'asc')->get();
        return View::make('admin.addon.modal-detail')->with([
            'addonId' => $addonId,
            'childrens' => $childrens
        ]);
    }

    public function getModalEdit($addonId)
    {
        $addon = Addon::findOrFail($addonId);
        $parents = Addon::whereNull('parent_id')->get();
        return View::make('admin.addon.modal-edit')->with([
            'addon' => $addon,
            'parents' => $parents,
        ]);
    }


    public function update(UpdateAddonRequest $request, $addonId)
    {
        $parentId = $request->input('parent_id');
        $isPrice = $parentId !== null ? true : false;

        $dataAddon = $request->validated();
        try {
            $addon = Addon::find($addonId);

            // Check if addon doesn't exists
            if (!$addon) {
                $request->session()->flash('failed', "Addon not found!");
                return redirect()->back();
            }

            $addon->name            = $dataAddon['name'];
            $addon->position        = $dataAddon['position'];
            $addon->is_price        = $isPrice;
            $addon->price           = (int) str_replace('.', '',$dataAddon['price']);
            $addon->parent_id       = $dataAddon['parent_id'];
            $addon->choose          = $dataAddon['choose'] ?? null;
            $addon->status_optional = $dataAddon['status_optional'] ?? null;
            $addon->status          = $dataAddon['status'];

            $addon->save();

            $request->session()->flash('success', "Update data addon successfully!");
            return redirect(route('addons.index'));
        } catch (\Throwable $th) {
            dd($th->getMessage());
            $request->session()->flash('failed', "Failed to update data addon!");
            return redirect(route('addons.index'));
        }
    }

    public function getModalDelete($addonId)
    {
        $addon = Addon::findOrFail($addonId);
        return View::make('admin.addon.modal-delete')->with('addon', $addon);
    }

    public function destroy(Request $request, $addonId)
    {
        try {
            $addon = Addon::findOrFail($addonId);
            $addon->delete();

            $request->session()->flash('success', "Delete data addon successfully!");
        } catch (ModelNotFoundException $e) {
            $request->session()->flash('failed', "Addon not found!");
        } catch (QueryException $e) {
            $request->session()->flash('failed', "Failed to delete data addon!");
        }

        return redirect(route('addons.index'));
    }
}
