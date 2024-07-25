<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderProductAddon;
use App\Models\OtherSetting;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Midtrans;
use Str;


class TransactionController extends Controller
{

    public function __construct()
    {
        Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Midtrans\Config::$isProduction = env('MIDTRANS_IS_SANDBOX');
        Midtrans\Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
        Midtrans\Config::$is3ds = env('MIDTRANS_IS_3DS');
    }

    public function checkout(Request $request,$token){
        $order = Order::where('token', $token)->where('payment_status_midtrans', 'paid')->latest()->first();
        if ($order) {
            return redirect(route('mobile.homepage'))->with(['failed' => 'Order Failed!']);
        }

        try {
            $sessionId = 'guest';
            $session_cart   = Cart::session($sessionId)->getContent();

            $other_setting  = OtherSetting::get()->first();
            $subTotal       = \Cart::session($sessionId)->getTotal();
            $service        = $subTotal* $other_setting->layanan /100;
            $pb01           = ($subTotal + $service) * $other_setting->pb01 /100 ;
            $total_price    = $subTotal + $service + $pb01;

            // Stock
            $stockCheck    = []; // Array untuk menyimpan jumlah total produk berdasarkan ID produk

            foreach ($session_cart as $cart) {
                $productId = $cart->attributes['product']['id'];

                // Perbarui total kuantitas produk untuk pengecekan stok
                if (!isset($stockCheck[$productId])) {
                    $stockCheck[$productId] = 0;
                }
                $stockCheck[$productId] += (int) $cart->quantity;
            }

            // Pengecekan stok sebelum menyimpan ke tabel order_products
            foreach ($stockCheck as $productId => $totalQty) {
                $product = Product::findOrFail($productId);
                if ((int)$product->current_stock < $totalQty) {
                    return redirect()->back()->with(['failed' => 'Stock product ' . $product->name . ' kurang - Stock tersisa ' . $product->current_stock]);
                }
            }

            $data['dataCarts'] = $session_cart;
            $data['subTotal'] = $subTotal;
            $data['service'] = $service;
            $data['pb01'] = $pb01;
            $data['total_price'] = $total_price;
            $data['token'] = $token;

            return view('mobile.checkout.index',$data)->with('success', 'Order Telah berhasil');

        } catch (\Throwable $th) {
            dd($th->getMessage());
            return redirect()->back()->with('failed', $th->getMessage());
        }
    }

    public function store(Request $request,$token){
        $order = Order::where('token', $token)->where('payment_status_midtrans', 'paid')->latest()->first();
        if ($order) {
            return redirect(route('mobile.homepage'))->with(['failed' => 'Order Failed!']);
        }

        $checkUrl = Order::where('token', $token)->where('payment_status_midtrans', '!=' ,'paid')->latest()->first();
        if ($checkUrl) {
            return redirect($checkUrl->midtrans_url);
        }

        DB::beginTransaction();
        try {
            $sessionId = 'guest';
            $session_cart   = Cart::session($sessionId)->getContent();

            $other_setting  = OtherSetting::get()->first();
            $subTotal       = \Cart::session($sessionId)->getTotal();
            $service        = $subTotal* $other_setting->layanan /100;
            $pb01           = ($subTotal + $service) * $other_setting->pb01 /100 ;
            $total_price    = $subTotal + $service + $pb01;

            // =================Create Data Order================
            $order = Order::create([
                'no_invoice'        => $this->generateInvoice(),
                'payment_status'    => 'Unpaid',
                'payment_method'    => 'Midtrans',

                'total_qty'         => array_sum($request->quantity),
                'subtotal'          => $subTotal,
                'service'           => $service,
                'pb01'              => $pb01,
                'total'             => $total_price,
                'token'             => $token,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ]);
            // =================Create Data Order================

            // ==============Midtrans Invoice Create=============
            $paymentRedirect = $this->getSnapRedirect($order);
            // ==============Midtrans Invoice Create=============

            // Order Product
            $orderProducts = []; // Array untuk menyimpan detail produk yang telah dimasukkan ke dalam pesanan
            $stockCheck    = []; // Array untuk menyimpan jumlah total produk berdasarkan ID produk

            foreach ($session_cart as $cart) {
                $productId = $cart->attributes['product']['id'];
                $addonIds  = array_map(function($addon) {
                    return $addon['id'];
                }, $cart->attributes['addons']);

                // Buat kunci unik berdasarkan ID produk dan ID addons
                $uniqueKey = $productId . '-' . implode('-', $addonIds);

                if (!isset($orderProducts[$uniqueKey])) {
                    $orderProducts[$uniqueKey] = [
                        'id'                => $productId,
                        'name'              => $cart->attributes['product']['name'],
                        'cost_price'        => $cart->attributes['product']['cost_price'],
                        'selling_price'     => $cart->attributes['product']['selling_price'],
                        'is_discount'       => $cart->attributes['product']['is_discount'],
                        'percent_discount'  => $cart->attributes['product']['percent_discount'],
                        'price_discount'    => $cart->attributes['product']['price_discount'],
                        'qty'               => (int) $cart->quantity,
                        'addons'            => $cart->attributes['addons'],
                    ];
                } else {
                    $orderProducts[$uniqueKey]['qty'] += (int) $cart->quantity;
                }

                // Perbarui total kuantitas produk untuk pengecekan stok
                if (!isset($stockCheck[$productId])) {
                    $stockCheck[$productId] = 0;
                }
                $stockCheck[$productId] += (int) $cart->quantity;
            }

            // Pengecekan stok sebelum menyimpan ke tabel order_products
            foreach ($stockCheck as $productId => $totalQty) {
                $product = Product::findOrFail($productId);
                if ((int)$product->current_stock < $totalQty) {
                    return redirect()->back()->with(['failed' => 'Stock product ' . $product->name . ' kurang - Stock tersisa ' . $product->current_stock]);
                }

                // Kurangi stok produk
                $product->current_stock = (int) $product->current_stock - (int) $totalQty;
                $product->save();
            }

            // Simpan produk dan addons ke tabel order_products
            foreach ($orderProducts as $product) {
                // Buat entri order_product
                $orderProduct = OrderProduct::create([
                    'order_id'          => $order->id,
                    'name'              => $product['name'],
                    'cost_price'        => $product['cost_price'],
                    'selling_price'     => $product['selling_price'],
                    'is_discount'       => $product['is_discount'],
                    'percent_discount'  => $product['percent_discount'],
                    'price_discount'    => $product['price_discount'],
                    'qty'               => $product['qty'],
                ]);

                // Simpan addons terkait ke tabel order_product_addons
                foreach ($product['addons'] as $addon) {
                    $getAddon = Addon::findOrFail($addon['id']);
                    OrderProductAddon::create([
                        'order_product_id' => $orderProduct->id,
                        'name'             => $getAddon->name,
                        'price'            => $getAddon->price,
                    ]);
                }
            }

            // Jika semua operasi berhasil, lakukan commit
            DB::commit();

            // Hapus sesi keranjang setelah berhasil menyimpan data pesanan
            Cart::session($sessionId)->clear();

            return redirect($paymentRedirect);

        } catch (\Throwable $th) {
            //throw $th;
            dd($th->getMessage());
            DB::rollBack();
            return redirect()->back()->with('failed', $th->getMessage());
        }
    }

    public function getSnapRedirect(Order $order)
    {
        $orderId = $order->no_invoice;
        $price = $order->total;

        $transaction_details = [
            'order_id' => $orderId,
            // 'gross_amount' => $price,
            'gross_amount' => 1,
        ];

        $item_details[] = [
            "id" => $orderId,
            "price" => 1,
            "quantity" => $order->total_qty,
            "name" => "Payment for Midtrans"
        ];

        $userData = [
            'first_name' => $order->customer_name,
            'last_name' => "",
            'address' => "",
            'city' => "",
            'postal_code' => "",
            'phone' => $order->customer_phone,
            'country_code' => "IDN",
        ];

        $customerDetails = [
            'first_name' => $order->customer_name,
            'last_name' => "",
            'email' => $order->customer_email,
            'phone' => $order->customer_phone,
            'billing_address' => $userData,
            'shipping_address' => $userData,
        ];

        $midtrans_params = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customerDetails,
            'item_details' => $item_details,
        ];

        try {
            // Get Snap Payment Page URL
            $paymentUrl = \Midtrans\Snap::createTransaction($midtrans_params)->redirect_url;
            $order->midtrans_url = $paymentUrl;

            $order->save();

            return $paymentUrl;
        } catch (\Throwable $th) {
            //throw $th;
            dd($th->getMessage());
            return false;
        }
    }

    public function midtransCallback(Request $request)
    {
        $notif = $request->method() == 'POST' ? new Midtrans\Notification() :  Midtrans\Transaction::status($request->order_id);

        $transaction_status = $notif->transaction_status;
        $fraud = $notif->fraud_status;

        $order_invoice  = $notif->order_id;
        $order          = Order::find($order_invoice);

        if ($transaction_status == 'capture') {
            if ($fraud == 'challenge') {
            // TODO Set payment status in merchant's database to 'challenge'
            $order->payment_status_midtrans = 'pending';
            }
            else if ($fraud == 'accept') {
                // TODO Set payment status in merchant's database to 'success'
                $order->payment_status_midtrans = 'paid';
            }
        }
        else if ($transaction_status == 'cancel') {
            if ($fraud == 'challenge') {
                // TODO Set payment status in merchant's database to 'failure'
                $order->payment_status_midtrans = 'failed';
            }
            else if ($fraud == 'accept') {
                // TODO Set payment status in merchant's database to 'failure'
                $order->payment_status_midtrans = 'failed';
            }
        }
        else if ($transaction_status == 'deny') {
            // TODO Set payment status in merchant's database to 'failure'
            $order->payment_status_midtrans = 'failed';
        }
        else if ($transaction_status == 'settlement') {
            // TODO set payment status in merchant's database to 'Settlement'
            $order->payment_status_midtrans = 'paid';
        }
        else if ($transaction_status == 'pending') {
            // TODO set payment status in merchant's database to 'Pending'
            $order->payment_status_midtrans = 'pending';
        }
        else if ($transaction_status == 'expire') {
            // TODO set payment status in merchant's database to 'expire'
            $order->payment_status_midtrans = 'failed';
        }

        $order->save();
        return view('mobile.checkout.success');
    }

    private function generateInvoice()
    {
        // Ambil tanggal hari ini
        $today = Carbon::today();
        $formattedDate = $today->format('ymd'); // Format tanggal: yyMMdd

        // Ambil order terakhir yang dibuat hari ini dan sudah dibayar
        $lastOrder = Order::whereDate('created_at', $today)
                          ->orderBy('id', 'desc')
                          ->first();

        if ($lastOrder) {
            // Cek apakah order dibuat pada tanggal yang sama dengan hari ini
            $lastInvoiceNumber = $lastOrder->no_invoice;
            // Ambil nomor order dari string no_invoice (sesuaikan dengan format substring jika diperlukan)
            $lastOrderNumber   = (int)substr($lastInvoiceNumber, 7);
            $nextOrderNumber   = $lastOrderNumber + 1;
        } else {
            $nextOrderNumber   = 1;
        }

        // Tambahkan padding agar nomor order menjadi 3 digit
        $paddedOrderNumber = str_pad($nextOrderNumber, 3, '0', STR_PAD_LEFT);
        // Buat nomor invoice
        $invoiceNumber = $formattedDate . '-' . $paddedOrderNumber;

        return $invoiceNumber;
    }
}
