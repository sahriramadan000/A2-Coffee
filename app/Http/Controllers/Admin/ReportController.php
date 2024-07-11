<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function reportGross(){
        $data ['page_title'] = 'Report Sales Gross Profit';
        return view('admin.report.sales.gross-profit',$data);
    }

    public function getReportGross(Request $request)
    {
        if ($request->ajax()) {
            $query = Order::with(['orderProducts.orderProductAddons'])->select('orders.*');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('order_products', function($row) {
                    return $row->orderProducts->map(function($product) {
                        $addons = $product->orderProductAddons->map(function($addon) {
                            return $addon->name;
                        })->implode(', ');

                        return $product->name . ' (' . $addons . ')';
                    })->implode('<br>');
                })
                ->addColumn('action', function($row) {
                    return '<a href="#" class="btn btn-sm btn-primary">View</a>';
                })
                ->rawColumns(['order_products', 'action'])
                ->make(true);
        }
    }

    public function paymentMethod(){
        $data ['page_title'] = 'Report Sales Gross Profit';
        return view('admin.report.payment_method',$data);
    }
}
