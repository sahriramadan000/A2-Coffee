<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function reportGross(Request $request){
        $data ['page_title'] = 'Report Sales Gross Profit';
        $data['account_users'] = User::get();

        $type = $request->input('type', 'day');
        $cashierName = $request->user_id;
        $date = $request->input('start_date', date('Y-m-d'));

        // Initialize $orders as an empty collection
        $orders = collect();

        if ($type == 'day') {
            if ($cashierName == 'All') {
                $orders = Order::where('payment_status', 'Paid')
                            ->whereDate('created_at', $date)
                            ->orderBy('id', 'desc')
                            ->get();
            } else {
                $orders = Order::where('payment_status', 'Paid')
                            ->whereDate('created_at', $date)
                            ->orderBy('id', 'desc')
                            ->get();
            }
        } elseif ($type == 'monthly') {
            $month = $request->input('month', date('m'));
            $monthPart = date('m', strtotime($month)); // Ensures the input is in 'm' format
            $orders = Order::whereMonth('created_at', $monthPart)
                        ->when($cashierName != 'All', function ($query) use ($cashierName) {
                            return $query;
                        })
                        ->where('payment_status', 'Paid')
                        ->orderBy('id', 'desc')
                        ->get();
        } elseif ($type == 'yearly') {
            $year = $request->input('year', date('Y'));
            $orders = Order::whereYear('created_at', $year)
                        ->when($cashierName != 'All', function ($query) use ($cashierName) {
                            return $query;
                        })
                        ->where('payment_status', 'Paid')
                        ->orderBy('id', 'desc')
                        ->get();
        }

        // Define an array to store the grouped data
        $groupedData = [];

        // Iterate through the $stok array and group by payment method
        foreach ($orders as $order) {
            $paymentMethod = $order->payment_method ?? 'Unknown';

            // If the payment method is not already in the groupedData array, initialize it
            if (!isset($groupedData[$paymentMethod])) {
                $groupedData[$paymentMethod] = [
                    'payment_method' => $paymentMethod,
                    'total' => 0,
                    'quantity_method' => 0,
                ];
            }

            // Update the total price and count for the current payment method
            $groupedData[$paymentMethod]['total'] += $order->total ?? 0;
            $groupedData[$paymentMethod]['quantity_method']++;
        }

        $groupedData = array_values($groupedData);

        $totalPriceSum = $orders->sum('subtotal');
        $modal = OrderProduct::whereIn('order_id', $orders->pluck('id'))
            ->selectRaw('sum(cost_price * qty) as total_cost')
            ->value('total_cost');

        $data['total_price'] = $totalPriceSum;
        $data['nett_sales'] = (int)$totalPriceSum -(int) $modal;

        return view('admin.report.sales.gross-profit',$data);
    }

    public function getReportGross(Request $request)
    {
        $page_title = 'Report Sales Gross Profit';
        $account_users = User::get();

        $type = $request->input('type', 'day');
        $cashierName = $request->user_id;
        $date = $request->input('start_date', date('Y-m-d'));

        // Initialize $orders as an empty collection
        $orders = collect();

        if ($type == 'day') {
            if ($cashierName == 'All') {
                $orders = Order::where('payment_status', 'Paid')
                            ->whereDate('created_at', $date)
                            ->orderBy('id', 'desc')
                            ->get();
            } else {
                $orders = Order::where('cashier_name', $cashierName)
                            ->where('payment_status', 'Paid')
                            ->whereDate('created_at', $date)
                            ->orderBy('id', 'desc')
                            ->get();
            }
        } elseif ($type == 'monthly') {
            $month = $request->input('month', date('m'));
            $monthPart = date('m', strtotime($month)); // Ensures the input is in 'm' format
            $orders = Order::whereMonth('created_at', $monthPart)
                        ->when($cashierName != 'All', function ($query) use ($cashierName) {
                            return $query->where('cashier_name', $cashierName);
                        })
                        ->where('payment_status', 'Paid')
                        ->orderBy('id', 'desc')
                        ->get();
        } elseif ($type == 'yearly') {
            $year = $request->input('year', date('Y'));
            $orders = Order::whereYear('created_at', $year)
                        ->when($cashierName != 'All', function ($query) use ($cashierName) {
                            return $query->where('cashier_name', $cashierName);
                        })
                        ->where('payment_status', 'Paid')
                        ->orderBy('id', 'desc')
                        ->get();
        }

        if ($request->ajax()) {
            // $query = Order::with(['orderProducts.orderProductAddons'])->select('orders.*');
            return DataTables::of($orders)
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
        $data['account_users'] = User::get();

        return view('admin.report.sales.payment-method',$data);
    }

    public function getReportPayment(Request $request)
    {
        $type = $request->input('type', 'day');
        $cashierName = $request->user_id;
        $date = $request->input('start_date', date('Y-m-d'));

        // Initialize $orders as an empty collection
        $orders = collect();

        if ($type == 'day') {
            if ($cashierName == 'All') {
                $orders = Order::where('payment_status', 'Paid')
                            ->whereDate('created_at', $date)
                            ->orderBy('id', 'desc')
                            ->get();
            } else {
                $orders = Order::where('payment_status', 'Paid')
                            ->whereDate('created_at', $date)
                            ->orderBy('id', 'desc')
                            ->get();
            }
        } elseif ($type == 'monthly') {
            $month = $request->input('month', date('m'));
            $monthPart = date('m', strtotime($month)); // Ensures the input is in 'm' format
            $orders = Order::whereMonth('created_at', $monthPart)
                        ->when($cashierName != 'All', function ($query) use ($cashierName) {
                            return $query;
                        })
                        ->where('payment_status', 'Paid')
                        ->orderBy('id', 'desc')
                        ->get();
        } elseif ($type == 'yearly') {
            $year = $request->input('year', date('Y'));
            $orders = Order::whereYear('created_at', $year)
                        ->when($cashierName != 'All', function ($query) use ($cashierName) {
                            return $query;
                        })
                        ->where('payment_status', 'Paid')
                        ->orderBy('id', 'desc')
                        ->get();
        }

        // Define an array to store the grouped data
        $groupedData = [];

        // Iterate through the $stok array and group by payment method
        foreach ($orders as $order) {
            $paymentMethod = $order->payment_method ?? 'Unknown';

            // If the payment method is not already in the groupedData array, initialize it
            if (!isset($groupedData[$paymentMethod])) {
                $groupedData[$paymentMethod] = [
                    'payment_method' => $paymentMethod,
                    'total' => 0,
                    'quantity_method' => 0,
                ];
            }

            // Update the total price and count for the current payment method
            $groupedData[$paymentMethod]['total'] += $order->total ?? 0;
            $groupedData[$paymentMethod]['quantity_method']++;
        }

        $groupedData = array_values($groupedData);

        if ($request->ajax()) {
            return DataTables::of($groupedData)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function refund(){
        $data ['page_title'] = 'Report Refund';
        $data['account_users'] = User::get();

        return view('admin.report.sales.refund',$data);
    }

    public function getReportRefund(Request $request)
    {
        $page_title = 'Report Refund';
        $account_users = User::get();

        $type = $request->input('type', 'day');
        $cashierName = $request->user_id;
        $date = $request->input('start_date', date('Y-m-d'));

        // Initialize $orders as an empty collection
        $orders = collect();

        if ($type == 'day') {
            if ($cashierName == 'All') {
                $orders = Order::where('payment_status', 'Unpaid')
                            ->where('payment_method', 'Return')
                            ->whereDate('created_at', $date)
                            ->orderBy('id', 'desc')
                            ->get();
            } else {
                $orders = Order::where('cashier_name', $cashierName)
                            ->where('payment_status', 'Unpaid')
                            ->where('payment_method', 'Return')
                            ->whereDate('created_at', $date)
                            ->orderBy('id', 'desc')
                            ->get();
            }
        } elseif ($type == 'monthly') {
            $month = $request->input('month', date('m'));
            $monthPart = date('m', strtotime($month)); // Ensures the input is in 'm' format
            $orders = Order::whereMonth('created_at', $monthPart)
                        ->when($cashierName != 'All', function ($query) use ($cashierName) {
                            return $query->where('cashier_name', $cashierName);
                        })
                        ->where('payment_status', 'Unpaid')
                        ->where('payment_method', 'Return')
                        ->orderBy('id', 'desc')
                        ->get();
        } elseif ($type == 'yearly') {
            $year = $request->input('year', date('Y'));
            $orders = Order::whereYear('created_at', $year)
                        ->when($cashierName != 'All', function ($query) use ($cashierName) {
                            return $query->where('cashier_name', $cashierName);
                        })
                        ->where('payment_status', 'Paid')
                        ->where('payment_method', 'Return')
                        ->orderBy('id', 'desc')
                        ->get();
        }

        if ($request->ajax()) {
            // $query = Order::with(['orderProducts.orderProductAddons'])->select('orders.*');
            return DataTables::of($orders)
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

    public function reportAbsensi(){
        $data ['page_title'] = 'Report Absensi';
        $data['account_users'] = User::get();
        $data['attendances'] = Attendance::get();

        return view('admin.report.sales.absensi',$data);
    }

    public function getReportAbsensi(Request $request)
    {
        $page_title = 'Report Refund';
        $account_users = User::get();

        $type = $request->input('type', 'day');
        $user = $request->user_id;
        $date = $request->input('start_date', date('Y-m-d'));

        // Initialize $orders as an empty collection
        $orders = collect();

        if ($type == 'day') {
            if ($user == 'All') {
                $orders = Attendance::whereDate('created_at', $date)
                            ->orderBy('id', 'desc')
                            ->get();
            } else {
                $orders = Attendance::where('user_id', $user)
                            ->whereDate('created_at', $date)
                            ->orderBy('id', 'desc')
                            ->get();
            }
        } elseif ($type == 'monthly') {
            $month = $request->input('month', date('m'));
            $monthPart = date('m', strtotime($month)); // Ensures the input is in 'm' format
            $orders = Attendance::whereMonth('created_at', $monthPart)
                        ->when($user != 'All', function ($query) use ($user) {
                            return $query->where('user_id', $user);
                        })
                        ->orderBy('id', 'desc')
                        ->get();
        } elseif ($type == 'yearly') {
            $year = $request->input('year', date('Y'));
            $orders = Attendance::whereYear('created_at', $year)
                        ->when($user != 'All', function ($query) use ($user) {
                            return $query->where('user_id', $user);
                        })
                        ->orderBy('id', 'desc')
                        ->get();
        }

        if ($request->ajax()) {
            // $query = Order::with(['orderProducts.orderProductAddons'])->select('orders.*');
            return DataTables::of($orders)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->user->username; // Access the related user's name
                })
                ->make(true);
        }
    }
}
