<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use PDF;

class SettlementController extends Controller
{
    public function index(){
        $data ['page_title'] = 'Report Settlement';
        $data['account_users'] = User::get();

        return view('admin.settlement.index',$data);
    }

    public function getSettlement(Request $request)
    {
        $page_title = 'Report Settlement';
        // $store = Store::first(); // Assuming there's only one store
        
        $type = $request->type;
        $shift = $request->shift;
        $date = $request->start_date;
        
        // Initialize $orders as an empty collection
        $orders = collect();
        
        if ($type == 'day') {
            
            if ($shift == 'all') {
                $orders = Order::where('payment_status', 'Paid')
                ->whereDate('created_at', $date)
                ->orderBy('id', 'desc')
                ->get();
            } else {
                $store = Store::where('shift',$request->shift)->first(); // Assuming there's only one store
                // Ensure open and close times are in the correct format
                $openTime = \Carbon\Carbon::parse($store->open_store)->format('H:i:s');
                $closeTime = \Carbon\Carbon::parse($store->close_store)->format('H:i:s');
    
                // Combine date with times correctly
                $openDateTime = $date . ' ' . $openTime;
                $closeDateTime = $date . ' ' . $closeTime;
    
                // Ensure the datetime strings are in the correct format
                $openDateTime = date('Y-m-d H:i:s', strtotime($openDateTime));
                $closeDateTime = date('Y-m-d H:i:s', strtotime($closeDateTime));
                $orders = Order::where('payment_status', 'Paid')
                                ->whereBetween('created_at', [$openDateTime, $closeDateTime])
                                ->orderBy('id', 'desc')
                                ->get();
            }


        } elseif ($type == 'monthly') {
            $month = $request->input('month', date('m'));
            $orders = Order::whereMonth('created_at', $month)
                        ->where('payment_status', 'Paid')
                        ->orderBy('id', 'desc')
                        ->get();
        } elseif ($type == 'yearly') {
            $year = $request->input('year', date('Y'));
            $orders = Order::whereYear('created_at', $year)
                        ->where('payment_status', 'Paid')
                        ->orderBy('id', 'desc')
                        ->get();
        }
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
            ->rawColumns(['order_products'])
            ->make(true);

    }

    public function printSettlement(Request $request){
        $type = $request->type;
        $shift = $request->shift;
        $date = $request->start_date;
        $store = Store::where('shift', $request->shift)->whereDate('open_store', $date)->first();
    
        $orders = collect();
        if ($type == 'day') {
            if ($shift == 'all') {
                $orders = Order::where('payment_status', 'Paid')
                               ->whereDate('created_at', $date)
                               ->orderBy('id', 'desc')
                               ->get();
            } else {
                $openTime = \Carbon\Carbon::parse($store->open_store)->format('H:i:s');
                $closeTime = \Carbon\Carbon::parse($store->close_store)->format('H:i:s');
                $openDateTime = date('Y-m-d H:i:s', strtotime($date . ' ' . $openTime));
                $closeDateTime = date('Y-m-d H:i:s', strtotime($date . ' ' . $closeTime));
                $orders = Order::where('payment_status', 'Paid')
                               ->whereBetween('created_at', [$openDateTime, $closeDateTime])
                               ->orderBy('id', 'desc')
                               ->get();
            }
        } elseif ($type == 'monthly') {
            $month = $request->input('month', date('m'));
            $orders = Order::whereMonth('created_at', $month)
                           ->where('payment_status', 'Paid')
                           ->orderBy('id', 'desc')
                           ->get();
        } elseif ($type == 'yearly') {
            $year = $request->input('year', date('Y'));
            $orders = Order::whereYear('created_at', $year)
                           ->where('payment_status', 'Paid')
                           ->orderBy('id', 'desc')
                           ->get();
        }
    
        $groupedData = [];
        $totalAll = 0;
        $pb01 = 0;
        $service = 0;
    
        // Aggregate product quantities
        $productQuantities = [];
    
        foreach ($orders as $order) {
            $paymentMethod = $order->payment_method ?? 'Unknown';
    
            if (!isset($groupedData[$paymentMethod])) {
                $groupedData[$paymentMethod] = [
                    'payment_method' => $paymentMethod,
                    'total' => 0,
                    'quantity_method' => 0,
                ];
            }
    
            $groupedData[$paymentMethod]['total'] += $order->total ?? 0;
            $groupedData[$paymentMethod]['quantity_method']++;
            $totalAll += $order->total ?? 0;
            $pb01 += $order->pb01 ?? 0;
            $service += $order->service ?? 0;
    
            // Aggregate product quantities
            foreach ($order->orderProducts as $orderProduct) {
                if (!isset($productQuantities[$orderProduct->name])) {
                    $productQuantities[$orderProduct->name] = 0;
                }
                $productQuantities[$orderProduct->name] += $orderProduct->qty;
            }
        }
        
        if (count($orders) != 0) {
            $connector = new NetworkPrintConnector("192.168.123.120", 9100);
            $printer = new Printer($connector);
                
            /* Initialize */
            $printer -> initialize();

            // Membuat judul
            $printer->initialize();
            $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT); // Setting teks menjadi lebih besar
            $printer->setJustification(Printer::JUSTIFY_CENTER); // Setting teks menjadi rata tengah
            $printer->text("A2 Coffee & Eatery\n");
            $printer->text("\n");

            // Data transaksi
            $printer->initialize();
            $printer->text("--------------------------------\n");
            $printer->text("Kasir   : ". Auth::user()->username."\n");
            $printer->text("Waktu   : ". $store->open_store."\n");
            foreach ($groupedData as $paymentMethod => $data){
                $printer->text($paymentMethod ." : ". number_format($data['total'],0)."\n");
            }
            $printer->text("Total Keseluruhan   : ". $orders->count()."\n");
            $printer->text("--------------------------------\n");

            $printer->text("Pajak           : Rp.".number_format($pb01,0)."\n");
            $printer->text("Biaya Layanan   : Rp.".number_format($service,0)."\n");
            $printer->text("Transaksi       : Rp.".number_format($orders->count(),0)."\n");
            
            
            $printer->text("--------------------------------\n");
            foreach ($productQuantities as $productName => $qty) {
                $printer->text($productName."                       ".$qty."\n");
            } 

            
            $printer->text("\n");

            // Pesan penutup
            $printer->initialize();
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->feed(3); // mencetak 5 baris kosong agar terangkat (pemotong kertas saya memiliki jarak 5 baris dari toner)
            $printer->cut();
            $printer->close();
            return redirect()->back()->with('success','Berhasil Tercetak ');
        }else{
            return redirect()->back()->with('success','Print Gagal ');
        }
    }

    // public function printSettlement(Request $request){
    //     $type = $request->type;
    //     $shift = $request->shift;
    //     $date = $request->start_date;
    //     $store = Store::where('shift', $request->shift)->whereDate('open_store', $date)->first();
    
    //     $orders = collect();
    //     if ($type == 'day') {
    //         if ($shift == 'all') {
    //             $orders = Order::where('payment_status', 'Paid')
    //                            ->whereDate('created_at', $date)
    //                            ->orderBy('id', 'desc')
    //                            ->get();
    //         } else {
    //             $openTime = \Carbon\Carbon::parse($store->open_store)->format('H:i:s');
    //             $closeTime = \Carbon\Carbon::parse($store->close_store)->format('H:i:s');
    //             $openDateTime = date('Y-m-d H:i:s', strtotime($date . ' ' . $openTime));
    //             $closeDateTime = date('Y-m-d H:i:s', strtotime($date . ' ' . $closeTime));
    //             $orders = Order::where('payment_status', 'Paid')
    //                            ->whereBetween('created_at', [$openDateTime, $closeDateTime])
    //                            ->orderBy('id', 'desc')
    //                            ->get();
    //         }
    //     } elseif ($type == 'monthly') {
    //         $month = $request->input('month', date('m'));
    //         $orders = Order::whereMonth('created_at', $month)
    //                        ->where('payment_status', 'Paid')
    //                        ->orderBy('id', 'desc')
    //                        ->get();
    //     } elseif ($type == 'yearly') {
    //         $year = $request->input('year', date('Y'));
    //         $orders = Order::whereYear('created_at', $year)
    //                        ->where('payment_status', 'Paid')
    //                        ->orderBy('id', 'desc')
    //                        ->get();
    //     }
    
    //     $groupedData = [];
    //     $totalAll = 0;
    //     $pb01 = 0;
    //     $service = 0;
    
    //     // Aggregate product quantities
    //     $productQuantities = [];
    
    //     foreach ($orders as $order) {
    //         $paymentMethod = $order->payment_method ?? 'Unknown';
    
    //         if (!isset($groupedData[$paymentMethod])) {
    //             $groupedData[$paymentMethod] = [
    //                 'payment_method' => $paymentMethod,
    //                 'total' => 0,
    //                 'quantity_method' => 0,
    //             ];
    //         }
    
    //         $groupedData[$paymentMethod]['total'] += $order->total ?? 0;
    //         $groupedData[$paymentMethod]['quantity_method']++;
    //         $totalAll += $order->total ?? 0;
    //         $pb01 += $order->pb01 ?? 0;
    //         $service += $order->service ?? 0;
    
    //         // Aggregate product quantities
    //         foreach ($order->orderProducts as $orderProduct) {
    //             if (!isset($productQuantities[$orderProduct->name])) {
    //                 $productQuantities[$orderProduct->name] = 0;
    //             }
    //             $productQuantities[$orderProduct->name] += $orderProduct->qty;
    //         }
    //     }
    
    //     $data['orders'] = $orders;
    //     $data['totalTransaction'] = $orders->count();
    //     $data['stores'] = $store;
    //     $data['groupedData'] = $groupedData;
    //     $data['totalAll'] = $totalAll;
    //     $data['pb01'] = $pb01;
    //     $data['service'] = $service;
    //     $data['productQuantities'] = $productQuantities; // Pass the aggregated product quantities
    
    //     return PDF::loadview('admin.settlement.print-settlement', $data)->stream('settlement-' . '.pdf');
    // }
    


}
