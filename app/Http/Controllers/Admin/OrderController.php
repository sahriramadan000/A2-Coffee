<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Coupons;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderCoupon;
use App\Models\OrderProduct;
use App\Models\OrderProductAddon;
use App\Models\OtherSetting;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout(Request $request, $token)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $session_cart   = Cart::session(Auth::user()->id)->getContent();
            $other_setting  = OtherSetting::get()->first();
            $checkToken     = Order::where('token',$token)->get();
            // $checkToken     = Order::where('token',$token)->where('payment_status', 'Paid')->get();
            $service        = (int) str_replace('.', '', $other_setting->layanan);
            $pb01           = $other_setting->pb01/100;
            $cash           = (int) str_replace('.', '', $request['cash']);
            $total_price    = 0;
            $customer       = null;

            if ($request->customer_id) {
                $customer = Customer::whereId($request->customer_id)->get()->first();
            }

            if (count($checkToken) != 0) {
                return redirect()->back()->with(['failed' => 'Tidak dapat mengulang transaksi!']);
            }
            
            if ($request->payment_method == 'Cash') {
                if ($request->cash == null) {
                    return redirect()->back()->with(['failed' => 'Harap Mengisi Uang Cash!']);
                }
            }

            if ($other_setting->layanan != 0) {
                $biaya_layanan  = Cart::getTotal() + $service;
                $total_price    = $biaya_layanan;
            }else{
                $total_price    = (Cart::getTotal() ?? '0');
            }

            if ($other_setting->pb01 != 0) {
                $biaya_pb01  = $total_price * ($other_setting->pb01/100);
                $pb01        = $biaya_pb01;
                $total_price = $total_price + $biaya_pb01;
            }else{
                $total_price = ($total_price ?? '0');
            }

            // ===================By Discount====================
            $getDiscountPrice   = ($request->discount_price ? (int) str_replace('.', '', $request->discount_price) : 0);
            $getDiscountPercent = ($request->discount_percent ? (int) $request->discount_percent : 0);

            if ($request->type_discount == 'percent') {
                $discount_amount = Cart::getTotal() * $getDiscountPercent / 100;
            } else {
                $discount_amount = $getDiscountPrice;
            }

            $subtotal = Cart::getTotal();
            $service_by_discount     = ($subtotal - $discount_amount) + (int) str_replace('.', '', $other_setting->layanan);
            $tax_by_discount         = $service_by_discount * $other_setting->pb01 / 100;
            $total_price_by_discount = $service_by_discount + $tax_by_discount;
            // ===================By Discount====================

            // Kembalian
            $kembalian = $cash - ($request->type_discount ? $total_price_by_discount : $total_price);

            // =================Create Data Order================
            if ($request->name_open_bill == null) {
                $order = Order::create([
                    'no_invoice'        => $this->generateInvoice(),
                    'cashier_name'      => Auth::user()->fullname,
                    'customer_name'     => $customer->name ?? null,
                    'customer_email'    => $customer->email ?? null,
                    'customer_phone'    => $customer->phone ?? null,
                    'payment_status'    => 'Paid',
                    'payment_method'    => $request->payment_method,
    
                    'total_qty'         => array_sum($request->qty),
                    'subtotal'          => $subtotal,
                    'type_discount'     => ($request->type_discount ? $request->type_discount : null) ,
                    'price_discount'    => $getDiscountPrice,
                    'percent_discount'  => $getDiscountPercent,
                    'service'           => $service,
                    'pb01'              => ($request->type_discount ? $tax_by_discount : $pb01),
                    'total'             => ($request->type_discount ? $total_price_by_discount : $total_price),
                    'cash'              => $cash,
                    'kembalian'         => $kembalian,
                    'token'             => $token,
                    'created_at'        => date('Y-m-d H:i:s'),
                    'updated_at'        => date('Y-m-d H:i:s'),
                ]);
            }else{
                $order = Order::create([
                    'no_invoice'        => $this->generateInvoice(),
                    'cashier_name'      => Auth::user()->fullname,
                    'customer_name'     => $customer->name ?? null,
                    'customer_email'    => $customer->email ?? null,
                    'customer_phone'    => $customer->phone ?? null,
                    'payment_status'    => 'Unpaid',
                    'payment_method'    => 'Open Bill',
    
                    'total_qty'         => array_sum($request->qty),
                    'subtotal'          => $subtotal,
                    'type_discount'     => ($request->type_discount ? $request->type_discount : null) ,
                    'price_discount'    => $getDiscountPrice,
                    'percent_discount'  => $getDiscountPercent,
                    'service'           => $service,
                    'pb01'              => ($request->type_discount ? $tax_by_discount : $pb01),
                    'total'             => ($request->type_discount ? $total_price_by_discount : $total_price),
                    'cash'              => $cash,
                    'kembalian'         => $kembalian,
                    'token'             => $token,
                    'created_at'        => date('Y-m-d H:i:s'),
                    'updated_at'        => date('Y-m-d H:i:s'),
                ]);
                // dd('masuk');
            }
            // =================Create Data Order================

            // =================Order Coupon=====================
            // Check jika ada coupon yang dipilih
            if ($request->coupon_id) {
                $coupon         = Coupons::findOrFail($request->coupon_id);
                $coupon_type    = $coupon->type;
                $coupon_amount  = 1;
                $temp_total     = 0;
                $orderCoupon = OrderCoupon::create([
                    'order_id'           => $order->id,
                    'name'               => $coupon->name,
                    'code'               => $coupon->code,
                    'type'               => $coupon->type,
                    'discount_value'     => $coupon->discount_value,
                    'discount_threshold' => (($coupon->type == 'Percentage Discount') ? $coupon->discount_threshold : null),
                    'max_discount_value' => (($coupon->type == 'Percentage Discount') ? $coupon->max_discount_value : null),
                ]);

                $coupon->current_usage += 1;
                $coupon->save();

                // Calculate discount amount based on coupon type
                if ($coupon_type == 'Percentage Discount') {
                    $coupon_amount = $subtotal * $coupon->discount_value / 100;

                    // Apply max discount value if applicable
                    if ($subtotal >= $coupon->discount_threshold && $coupon_amount > $coupon->max_discount_value) {
                        $coupon_amount = $coupon->max_discount_value;
                    }
                    $order->percent_discount = (int)$coupon->discount_value;
                    $order->price_discount   = $coupon_amount;
                } else {
                    $coupon_amount  = (int)$coupon->discount_value;
                }

                // Check Layanan
                if ($other_setting->layanan != 0) {
                    $biaya_layanan  = ($subtotal - $coupon_amount) + $service;
                    $temp_total     = $biaya_layanan;
                }else{
                    $temp_total     = (($subtotal - $coupon_amount) ?? 0);
                }

                // Update tax & total price
                $taxPriceByCoupon   = $temp_total * ($other_setting->pb01 / 100);
                $totalPriceByCoupon = $temp_total + $taxPriceByCoupon;

                // Set Data
                $order->is_coupon   = true;
                $order->pb01        = $taxPriceByCoupon;
                $order->total       = $totalPriceByCoupon;
                $order->save();
            }
            // =================Order Coupon=====================

            // ==================================================================================================
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
            Cart::session(Auth::user()->id)->clear();

            if ($request->cash) {
                return redirect()->route('pos')->with('success','Uang Yang Di kembalikan '. $kembalian);
            }else{
                return redirect()->route('pos')->with('success', 'Order Telah berhasil');
            }
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return redirect()->back()->with('failed', $th->getMessage());
        }
    }

    private function generateInvoice()
    {
        // Ambil tanggal hari ini
        $today = Carbon::today();
        $formattedDate = $today->format('ymd'); // Format tanggal: yyMMdd

        // Ambil order terakhir yang dibuat hari ini dan sudah dibayar
        $lastOrder = Order::whereDate('created_at', $today)
                        //   ->where('payment_status', 'Paid')
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
