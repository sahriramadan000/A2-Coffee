<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Stock\AddStockInRequest;
use App\Http\Requests\Admin\Stock\UpdateStockInRequest;
use App\Http\Requests\Admin\Stock_in\AddStockInRequest as Stock_inAddStockInRequest;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\View;


class StockInController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware('permission:tag-list', ['only' => ['index', 'getTags']]);
    //     $this->middleware('permission:tag-create', ['only' => ['getModalAdd','store']]);
    //     $this->middleware('permission:tag-edit', ['only' => ['getModalEdit','update']]);
    //     $this->middleware('permission:tag-delete', ['only' => ['getModalDelete','destroy']]);
    // }

    public function index(Request $request){
        $data ['page_title'] = 'Stock In';
        $data['account_users'] = User::get();

        $type = $request->input('type', 'day');
        $cashierName = $request->user_id;
        $date = $request->input('start_date', date('Y-m-d'));

        // Initialize $stockIn as an empty collection
        $stockIn = collect();

        if ($type == 'day') {
            if ($cashierName == 'All') {
                $stockIn = StockIn::whereDate('created_at', $date)
                            ->orderBy('id', 'desc')
                            ->get();
            } else {
                $stockIn = StockIn::whereDate('created_at', $date)
                            ->orderBy('id', 'desc')
                            ->get();
            }
        } elseif ($type == 'monthly') {
            $month = $request->input('month', date('m'));
            $monthPart = date('m', strtotime($month)); // Ensures the input is in 'm' format
            $stockIn = StockIn::whereMonth('created_at', $monthPart)
                        ->orderBy('id', 'desc')
                        ->get();
        } elseif ($type == 'yearly') {
            $year = $request->input('year', date('Y'));
            $stockIn = StockIn::whereYear('created_at', $year)
                        ->orderBy('id', 'desc')
                        ->get();
        }

        $totalStockIn = $stockIn->sum('stock_in');

        $data['total_stock_in'] = $totalStockIn;

        return view('admin.stock-in.index',$data);
    }


    public function getTags(Request $request)
    {
        $page_title = 'Stock In';
        $account_users = User::get();

        $type = $request->input('type', 'day');
        $cashierName = $request->user_id;
        $date = $request->input('start_date', date('Y-m-d'));

        // Initialize $stockIn as an empty collection
        $stockIn = collect();

        if ($type == 'day') {
            if ($cashierName == 'All') {
                $stockIn = StockIn::whereDate('created_at', $date)
                            ->orderBy('id', 'desc')
                            ->get();
            } else {
                $stockIn = StockIn::whereDate('created_at', $date)
                            ->orderBy('id', 'desc')
                            ->get();
            }
        } elseif ($type == 'monthly') {
            $month = $request->input('month', date('m'));
            $monthPart = date('m', strtotime($month)); // Ensures the input is in 'm' format
            $stockIn = StockIn::whereMonth('created_at', $monthPart)
                        ->orderBy('id', 'desc')
                        ->get();
        } elseif ($type == 'yearly') {
            $year = $request->input('year', date('Y'));
            $stockIn = StockIn::whereYear('created_at', $year)
                        ->orderBy('id', 'desc')
                        ->get();
        }

        if ($request->ajax()) {
            return DataTables::of($stockIn)
            ->addIndexColumn()
            ->addColumn('product', function($row){
                return $row->product->name;
            })
            ->addColumn('action', function ($row) {
                $btn = '';
                $btn = $btn . ' <button type="button" class="btn btn-sm btn-danger stock-ins-delete-table"  data-bs-target="#tabs-'.$row->id.'-delete-stock-in">Delete</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }

    public function getModalAdd()
    {
        $product = Product::orderBy('name','asc')->get();
        return View::make('admin.stock-in.modal-add')->with([
            'products'      => $product,
        ]);
    }

    public function store(Stock_inAddStockInRequest $request)
    {
        $dataStockIn = $request->validated();
        try {
            $stockIn = new StockIn();
            $stockIn->product_id    = $dataStockIn['product_id'];
            $stockIn->stock_in      = $dataStockIn['stock_in'];
            $stockIn->description   = $dataStockIn['description'];

            $product = Product::find($dataStockIn['product_id']);
            $product->current_stock += $dataStockIn['stock_in'];

            $product->save();
            $stockIn->save();



            $request->session()->flash('success', "Create data Stock In successfully!");
            return redirect(route('stock-ins.index'));
        } catch (\Throwable $th) {
            $request->session()->flash('failed', "Failed to create data Stock In!");
            return redirect(route('stock-ins.index'));
        }
    }

    public function getModalEdit($tagId)
    {
        $tag = StockIn::findOrFail($tagId);
        return View::make('admin.stock-in.modal-edit')->with('tag', $tag);
    }


    public function update(UpdateStockInRequest $request, $stokId)
    {
        $dataStockIn = $request->validated();
        try {
            $stockIn = StockIn::find($stokId);

            // Check if tag doesn't exists
            if (!$stockIn) {
                $request->session()->flash('failed', "Tag not found!");
                return redirect()->back();
            }

            $stockIn->product_id    = $dataStockIn['product_id'];
            $stockIn->stock_in      = $dataStockIn['stock_in'];
            $stockIn->description   = $dataStockIn['description'];

            $stockIn->save();

            $request->session()->flash('success', "Update data Stock In successfully!");
            return redirect(route('stock-ins.index'));
        } catch (\Throwable $th) {
            $request->session()->flash('failed', "Failed to update data Stock In!");
            return redirect(route('stock-ins.index'));
        }
    }

    public function getModalDelete($stockId)
    {
        $stockIn = StockIn::findOrFail($stockId);
        return View::make('admin.stock-in.modal-delete')->with('stock_in', $stockIn);
    }

    public function destroy(Request $request, $stockId)
    {
        try {
            $stockIn = StockIn::findOrFail($stockId);

            $product = Product::find($stockIn->product_id);
            $product->current_stock -= $stockIn->stock_in;

            $product->save();
            $stockIn->delete();

            $request->session()->flash('success', "Delete data Stock In successfully!");
        } catch (ModelNotFoundException $e) {
            $request->session()->flash('failed', "Stock In not found!");
        } catch (QueryException $e) {
            $request->session()->flash('failed', "Failed to delete data Stock In!");
        }

        return redirect(route('stock-ins.index'));
    }
}
