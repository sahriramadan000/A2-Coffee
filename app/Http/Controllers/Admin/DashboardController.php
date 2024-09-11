<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request) {
        $type = $request->input('type', 'day');
        $cashierName = $request->input('user_id', 'All');
        $date = $request->input('start_date', date('Y-m-d'));
    
        $orders = collect();
    
        if ($type == 'day') {
            if ($cashierName == 'All') {
                $orders = Order::where('payment_status', 'Paid')
                        ->whereDate('created_at', $date)
                        ->orderBy('id', 'desc')
                        ->get();
                $ordersUnpaid = Order::where('payment_status', 'Unpaid')
                        ->whereDate('created_at', $date)
                        ->orderBy('id', 'desc')
                        ->get();
            } else {
                $orders = Order::where('cashier_name', $cashierName)
                        ->where('payment_status', 'Paid')
                        ->whereDate('created_at', $date)
                        ->orderBy('id', 'desc')
                        ->get();
                $ordersUnpaid = Order::where('cashier_name', $cashierName)
                        ->where('payment_status', 'Unpaid')
                        ->whereDate('created_at', $date)
                        ->orderBy('id', 'desc')
                        ->get();
            }
        } elseif ($type == 'monthly') {
            $month = $request->input('month', date('m'));
            $monthPart = date('m', strtotime($month));
            $orders = Order::whereMonth('created_at', $monthPart)
                    ->when($cashierName != 'All', function ($query) use ($cashierName) {
                        return $query->where('cashier_name', $cashierName);
                    })
                    ->where('payment_status', 'Paid')
                    ->orderBy('id', 'desc')
                    ->get();
            $ordersUnpaid = Order::whereMonth('created_at', $monthPart)
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
                    ->where('payment_status', 'Unpaid')
                    ->orderBy('id', 'desc')
                    ->get();
            $ordersUnpaid = Order::whereYear('created_at', $year)
                    ->when($cashierName != 'All', function ($query) use ($cashierName) {
                        return $query->where('cashier_name', $cashierName);
                    })
                    ->where('payment_status', 'Unpaid')
                    ->orderBy('id', 'desc')
                    ->get();
        }
    
        $hourlyOrders = $orders->groupBy(function($order) {
            return Carbon::parse($order->created_at)->format('H');
        })->map(function($hour) {
            return [
                'count' => $hour->count(),
                'revenue' => $hour->sum('total')
            ];
        });
    
        $totalUnpaidSales = $ordersUnpaid->sum('total');
        $acceptSales = $orders->sum('total');
        $totalTransaction = $orders->count();
        $totalProduct = $orders->sum('total_qty');

        $data['acceptSales'] = $acceptSales;
        $data['totalUnpaidSales'] = $totalUnpaidSales;
        $data['totalTransaction'] = $totalTransaction;
        $data['totalProduct'] = $totalProduct;
        $data['hourlyOrders'] = $hourlyOrders;
    
        return view('admin.dashboard.index', $data);
    }
    
    
    
}
