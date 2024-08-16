<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\CacheOnholdControl;
use App\Models\Coupons;
use App\Models\Customer;
use App\Models\KeyVoid;
use App\Models\Order;
use App\Models\OrderCoupon;
use App\Models\OrderProduct;
use App\Models\OrderProductAddon;
use App\Models\OtherSetting;
use App\Models\Product;
use App\Models\ProductTag;
use App\Models\Table;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Support\Facades\Cache;
use PDF;

class TransactionController extends Controller
{
    public function index(){
        $data['page_title']     = 'Transaction';
        $data['data_items']     = Cart::session(Auth::user()->id)->getContent();
        $data['products']       = Product::orderby('id', 'asc')->get();
        $data['other_setting']  = OtherSetting::get()->first();
        $data['tables']         = Table::get();
        $service                = $data['other_setting']->layanan / 100;
        $subtotal               = Cart::getTotal();
        // dd($data['data_items']);
        $data['subtotal'] = $subtotal;
        $data['service']  = $subtotal * $service;
        $data['tax']      = (($data['subtotal'] + ($data['data_items']->isEmpty() ? 0 : $data['service'])) * $data['other_setting']->pb01/100);
        $data['total']    = ($data['subtotal'] + ($data['data_items']->isEmpty() ? 0 : $data['service'])) + $data['tax'];

        return view('admin.pos.index', $data);
    }

    // ========================================================================================
    // Modal View
    // ========================================================================================

    // Modal add Discount
    public function modalAddDiscount()
    {
        return View::make('admin.pos.modal.modal-add-discount');
    }

    // Modal add Coupon
    public function modalAddCoupon()
    {
        Cart::session(Auth::user()->id)->getContent();
        $subtotal = Cart::getTotal();

        $coupons = Coupons::where('minimum_cart', '<=', $subtotal)
                ->where('expired_at', '>=', now())
                ->whereRaw('current_usage < limit_usage')
                ->get();

        return View::make('admin.pos.modal.modal-add-coupon')->with([
            'coupons'      => $coupons,
        ]);
    }

     // Modal add Customer
     public function modalAddCustomer()
     {
         return View::make('admin.pos.modal.modal-add-customer');
     }

     // Modal edit QTY Cart
     public function modalEditQtyCart($key)
    {
        // Ambil item dari cart berdasarkan key
        $cartItem = Cart::session(Auth::user()->id)->get($key);

        if (!$cartItem) {
            return response()->json(['failed' => 'Cart item not found!'], 404);
        }

        return View::make('admin.pos.modal.modal-edit-qty-cart')->with([
            'key'      => $key,
            'quantity' => $cartItem->quantity, // Passing quantity ke view
        ]);
    }

     // Modal Search
    public function modalSearchProduct()
    {
        return View::make('admin.pos.modal.modal-search-product');
    }

    // Modal My Order
    public function modalMyOrder()
    {
        $today = Carbon::today();
        $getOrderPaid = Order::wherePaymentStatus('Paid')->whereDate('created_at', $today)->orderBy('id', 'desc')->get();
        $getOrderOpenBill = Order::wherePaymentStatus('Unpaid')->whereDate('created_at', $today)->orderBy('id', 'desc')->get();
        $getCacheOnhold = CacheOnholdControl::select(['key','name'])->whereDate('created_at', $today)->orderBy('id', 'desc')->get();

        return View::make('admin.pos.modal.modal-my-order')->with([
            'order_paids'      => $getOrderPaid,
            'order_open_bills'      => $getOrderOpenBill,
            'onhold_orders'    => $getCacheOnhold,
        ]);
    }

    // Modal Add Cart
    public function modalAddCart($productId)
    {
        $productById = Product::with('addons')->findOrFail($productId);
        $addons = $productById->addons;

        $parentAddons = $addons->where('parent_id', null);
        $childAddons = Addon::where('parent_id', '!=', null)->get();

        $structuredAddons = [];
        foreach ($parentAddons as $parentAddon) {
            // Tambahkan data parent addon ke array hasil
            $structuredAddons[$parentAddon->id] = [
                'addon' => $parentAddon,
                'children' => []
            ];
        }

        foreach ($childAddons as $childAddon) {
            if (isset($structuredAddons[$childAddon->parent_id])) {
                $structuredAddons[$childAddon->parent_id]['children'][] = $childAddon;
            }
        }

        $formattedAddons = [];

        foreach ($structuredAddons as $structuredAddon) {
            $formattedAddons[] = [
                'addon' => $structuredAddon['addon'],
                'children' => $structuredAddon['children']
            ];
        }

        return View::make('admin.pos.modal.modal-add-cart')->with([
            'product'     => $productById,
            'addons'      => $formattedAddons
        ]);
    }

    // Add Ongkir
    // public function modalAddOngkir()
    // {
    //     return View::make('pos.modal-add-ongkir');
    // }

    // ========================================================================================
    // End Modal View
    // ========================================================================================


    // ========================================================================================
    // Other Function
    // ========================================================================================

    // Get Data Tag
    public function getTag()
    {
        $allTag = Tag::has('products')->get();
        return response()->json($allTag, 200);
    }

    public function getProduct($idTag)
    {
        $getProductByTags = Product::whereHas('productTag', function ($query) use ($idTag) {
            $query->where('tag_id', $idTag);
        })->get();
        return response()->json($getProductByTags, 200);
    }

    public function deleteItem($id){
        if (Auth::check()) {
            Cart::session(Auth::user()->id)->remove($id);
        }
        $user = 'guest';
        Cart::session($user)->remove($id);
        return redirect()->back()->with('success', 'Item deleted successfully!');
    }

    public function addToCart(Request $request){
        try {
            if ($request->product_id == null) {
                return redirect()->back()->with('failed', 'Please Select The Product!');
            }

            $product = Product::findOrFail($request->product_id);

            // Ambil addons dari request
            $addons = $request->addons ?? [];

            // Perhitungan harga diskon
            $priceForPercent = $product->selling_price ?? 0;
            $priceAfterDiscount = $priceForPercent;

            if ($product->is_discount) {
                if ($product->price_discount && $product->price_discount > 0) {
                    $priceAfterDiscount = $product->price_discount;
                } elseif ($product->percent_discount && $product->percent_discount > 0 && $product->percent_discount <= 100) {
                    $discount_price = $priceForPercent * ($product->percent_discount / 100);
                    $priceAfterDiscount = $priceForPercent - $discount_price;
                }
            }

            // Hitung total harga addons
            $totalAddonPrice = array_reduce($addons, function($carry, $addon) {
                return $carry + $addon['price'];
            }, 0);

            // Tambahkan harga addons ke harga produk
            $totalPrice = $priceAfterDiscount + $totalAddonPrice;

            // Siapkan atribut detail produk
            $productDetailAttributes = array(
                'product' => $product,
                'addons'  => $addons,
            );

            $itemIdentifier = md5(json_encode($productDetailAttributes));

            $cartContent = Cart::session(Auth::user()->id)->getContent();

            // Cek apakah item yang akan ditambahkan sudah ada di keranjang
            $existingItem = $cartContent->first(function ($item, $key) use ($productDetailAttributes) {
                $attributes = $item->attributes;

                // Periksa apakah produk dan addons sama dengan yang ada dalam keranjang
                if ($attributes['product']['id'] === $productDetailAttributes['product']['id'] &&
                    $attributes['addons'] == $productDetailAttributes['addons']) {
                    return true;
                }

                return false;
            });

            if ($existingItem !== null) {
                // Jika item sudah ada, tambahkan jumlahnya
                Cart::session(Auth::user()->id)->update($existingItem->id, [
                    'quantity' => $request->quantity,
                    'attributes' => $existingItem->attributes->toArray(),
                ]);
            } else {
                // Jika item belum ada, tambahkan ke keranjang
                Cart::session(Auth::user()->id)->add(array(
                    'id'              => $itemIdentifier,
                    'name'            => $product->name,
                    'price'           => $totalPrice,
                    'quantity'        => $request->quantity,
                    'attributes'      => $productDetailAttributes,
                    'associatedModel' => Product::class
                ));
            }

            $other_setting = OtherSetting::select(['pb01', 'layanan'])->first();
            $subtotal      = (Cart::getTotal() ?? '0');
            $service       = $subtotal * ($other_setting->layanan / 100);
            $tax           = (($subtotal + $service) * $other_setting->pb01 / 100);
            $totalPayment  = ($subtotal + $service) + $tax;

            return response()->json([
                'success'   => 'Product '.$product->name.' Berhasil masuk cart!',
                'data'      => Cart::session(Auth::user()->id)->getContent()->toArray(),
                'service'   => $service,
                'tax'       => $tax,
                'subtotal'  => $subtotal,
                'total'     => $totalPayment,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['failed' => 'Product '.$product->name.' gagal masuk cart!'. $th->getMessage()], 500);
        }
    }

    public function updateCartQuantity(Request $request)
    {
        try {
            $cartItemKey = $request->key;
            $newQuantity = $request->quantity;

            // Cek apakah key dan quantity diberikan
            if ($cartItemKey == null || $newQuantity == null) {
                return response()->json(['failed' => 'Please provide a valid cart item key and quantity!'], 400);
            }

            // Cek apakah item dengan key tersebut ada di dalam cart
            $cartItem = Cart::session(Auth::user()->id)->get($cartItemKey);

            if ($cartItem) {
                // Update quantity dengan mengatur secara absolut ke nilai baru
                Cart::session(Auth::user()->id)->update($cartItemKey, [
                    'quantity' => [
                        'relative' => false,
                        'value' => $newQuantity
                    ],
                ]);

                $other_setting = OtherSetting::select(['pb01', 'layanan'])->first();
                $subtotal      = (Cart::getTotal() ?? '0');
                $service       = $subtotal * ($other_setting->layanan / 100);
                $tax           = (($subtotal + $service) * $other_setting->pb01 / 100);
                $totalPayment  = ($subtotal + $service) + $tax;

                $canDelete = Auth::user()->can('delete-product-in-cart');

                return response()->json([
                    'success'   => 'Cart item updated successfully!',
                    'data'      => Cart::session(Auth::user()->id)->getContent()->toArray(),
                    'service'   => $service,
                    'tax'       => $tax,
                    'subtotal'  => $subtotal,
                    'total'     => $totalPayment,
                    'canDelete' => $canDelete,
                ], 200);
            } else {
                return response()->json(['failed' => 'Cart item not found!'], 404);
            }
        } catch (\Throwable $th) {
            return response()->json(['failed' => 'Failed to update cart item! ' . $th->getMessage()], 500);
        }
    }

    // Void Cart
    public function voidCart()
    {
        Cart::session(Auth::user()->id)->clear();
        return redirect()->back()->with('success', 'Cart berhasil dibersihkan!');
    }

    public function getDataCustomers(Request $request)
    {
        $customers = Customer::select(['id', 'name'])->get();
        return response()->json($customers);
    }

    // ====================================================
    // Update Cart By Coupon
    public function updateCartByCoupon(Request $request)
    {
        Cart::session(Auth::user()->id)->getContent();
        $coupon         = Coupons::findOrFail($request->coupon_id);
        $coupon_type    = $coupon->type;
        $subtotal       = Cart::getTotal();
        $other_setting  = OtherSetting::get()->first();
        $service        = $other_setting->layanan / 100;
        $biaya_layanan  = 0;

        // Calculate discount amount based on coupon type
        if ($coupon_type == 'Percentage Discount') {
            $coupon_amount = $subtotal * $coupon->discount_value / 100;

            // Apply max discount value if applicable
            if ($subtotal >= $coupon->discount_threshold && $coupon_amount > $coupon->max_discount_value) {
                $coupon_amount = $coupon->max_discount_value;
            }
        } else {
            $coupon_amount = (int)$coupon->discount_value;
        }

        // Check Layanan
        if ($other_setting->layanan != 0) {
            $biaya_layanan  = ($subtotal - $coupon_amount) * $service;
            $temp_total     = $subtotal + $biaya_layanan;
        }else{
            $temp_total     = (($subtotal - $coupon_amount) ?? 0);
        }

        // Update tax & total price
        $tax    = $temp_total * ($other_setting->pb01 / 100);
        $total  = $temp_total +  ($tax);
        $info   = $coupon->name;

        return response()->json([
            'success'       => 'Coupon '.$coupon->name.' berhasil ditambahkan!',
            'coupon_type'   => $coupon_type,
            'coupon_amount' => $coupon_amount,
            'subtotal'      => $subtotal,
            'tax'           => $tax,
            'total'         => $total,
            'service'       => $biaya_layanan,
            'info'          => $info,
        ], 200);
    }

    // ====================================================
    //
    // ====================================================
    // Update Cart By Discount
    public function updateCartByDiscount(Request $request)
    {
        Cart::session(Auth::user()->id)->getContent();
        $other_setting      = OtherSetting::get()->first();
        $discount_price     = (int) str_replace('.', '', $request->discount_price);
        $discount_percent   = (int) $request->discount_percent;
        $discount_type      = $request->discount_type;
        $service_percentage = $other_setting->layanan / 100;
        $biaya_layanan      = 0;
        $subtotal           = Cart::getTotal();
        $discount_amount    = 0;

        // Hitung diskon berdasarkan jenisnya (persentase atau harga tetap)
        if ($discount_type == 'percent') {
            $discount_amount = $subtotal * $discount_percent / 100;
        } else {
            $discount_amount = $discount_price;
        }

        // Kurangi subtotal dengan diskon
        $subtotal_after_discount = $subtotal - $discount_amount;

        // Hitung biaya layanan (jika ada)
        if ($other_setting->layanan != 0) {
            $biaya_layanan = $subtotal_after_discount * $service_percentage;
        }

        // Hitung subtotal setelah biaya layanan
        $subtotal_with_service = $subtotal_after_discount + $biaya_layanan;

        // Hitung pajak (pb01)
        $tax_percentage = $other_setting->pb01 / 100;
        $tax = $subtotal_with_service * $tax_percentage;

        // Hitung total keseluruhan
        $total = $subtotal_with_service + $tax;

        return response()->json([
            'success'           => 'Discount berhasil ditambahkan!',
            'discount_price'    => $discount_price,
            'discount_percent'  => $discount_percent,
            'discount_type'     => $discount_type,
            'discount_amount'   => $discount_amount,
            'service'           => $biaya_layanan,
            'subtotal'          => $subtotal_after_discount,
            'tax'               => $tax,
            'total'             => $total,
        ], 200);
    }

    public function searchProduct(Request $request)
    {
        $products = Product::select(['id', 'name'])->get();
        return response()->json($products);
    }

     // On Hold
     public function onHoldOrder(Request $request)
     {
         try {

             // Get All Session Cart
             $session_cart = Cart::session(Auth::user()->id)->getContent()->toArray();

             // Create unique key
             $uniqueKey = uniqid();

             // Simpan data session cart ke Cache File dengan uniqeuKey
             Cache::put('onHoldCart:user:' . Auth::user()->id . ':' . $uniqueKey, $session_cart, 86400);

             $dataCache = CacheOnholdControl::create([
                 'key' => $uniqueKey,
                 'name' => ($request->name ? $request->name : 'No Name')
             ]);

             // Clear session cart
             if ($dataCache) {
                 Cart::session(Auth::user()->id)->clear();
             }

             return response()->json([
                 'code'      => 200,
                 'message'   => 'Order telah berhasil disimpan.',
             ], 200);

         } catch (\Throwable $th) {
             // Tangani kesalahan jika terjadi
             return response()->json(['error' => $th->getMessage()], 500);
         }
     }

     public function openOnholdOrder(Request $request)
     {
         try {
             $other_setting = OtherSetting::get()->first();

             Cart::session(Auth::user()->id)->clear();
             $keyCache = 'onHoldCart:user:' . Auth::user()->id . ':' . $request->key;

             if (Cache::has($keyCache)) {
                 // Get Cache by key
                 $getCache = Cache::get($keyCache);

                 // Add data to cart
                 foreach ($getCache as $cache) {
                    dd($cache['attributes']);
                     Cart::session(Auth::user()->id)->add([
                         'id' => $cache['id'],
                         'name' => $cache['name'],
                         'price' => $cache['price'],
                         'quantity' => $cache['quantity'],
                         'attributes' => $cache['attributes'],
                         'conditions' => $cache['conditions'],
                     ]);
                 }

                 // Delete Cache after add to cart
                 Cache::forget($keyCache);
                 CacheOnholdControl::where('key',$request->key)->delete();

                 // Set return data
                 $dataCart    = Cart::session(Auth::user()->id)->getContent();
                 $subtotal    = Cart::getTotal();
                 $service     = $subtotal * ($other_setting->layanan / 100);
                 $tax         = ($subtotal + $service) * ($other_setting->pb01 / 100);
                 $total_price = ($subtotal + $service) + $tax;


                 return response()->json([
                     'code'     => 200,
                     'message'  => 'Open onhold Berhasil.',
                     'data'     => $dataCart,
                     'service'  => $service,
                     'subtotal' => $subtotal,
                     'tax'      => $tax,
                     'total'    => $total_price,
                 ], 200);
             } else {
                 return null;
             }
         } catch (\Throwable $th) {
             return response()->json(['error' => $th->getMessage()], 500);
         }
     }

    //  Open Bill
    public function openBillOrder(Request $request)
    {
        try {
            $other_setting = OtherSetting::first();

            Cart::session(Auth::user()->id)->clear();

            $order = Order::where('id', $request->id)->first(); // Menggunakan first() untuk mengambil satu objek
            $orderProducts = OrderProduct::where('order_id', $order->id)->get();

            // Add data to cart
            foreach ($orderProducts as $orderProduct) {
                $products = Product::where('name', $orderProduct->name)->first();
                $orderAddOns = OrderProductAddon::where('order_product_id', $orderProduct->id)->get();

                // Calculate total price including addons
                $totalPrice = $orderProduct->selling_price;
                $addons = [];

                foreach ($orderAddOns as $addon) {
                    $totalPrice += $addon->price;
                    $addons[] = $addon;
                }

                Cart::session(Auth::user()->id)->add([
                    'id' => $orderProduct->id,
                    'name' => $orderProduct->name,
                    'price' => $totalPrice,
                    'quantity' => $orderProduct->qty,
                    'attributes' => [
                        'product' => $products,
                        'addons' => $addons,
                        'inputer' => $request->inputer,
                        'table' => $request->table,
                    ],
                ]);
            }

            // Delete Cache after add to cart
            $orders = Order::findOrFail($request->id);
            $orders->delete();

            // Set return data
            $dataCart = Cart::session(Auth::user()->id)->getContent();
            $subtotal = Cart::getTotal();
            $service = $subtotal * ($other_setting->layanan / 100);
            $tax = ($subtotal + $service) * ($other_setting->pb01 / 100);
            $total_price = ($subtotal + $service) + $tax;

            return response()->json([
                'code' => 200,
                'message' => 'Open Bill Berhasil.',
                'data' => $dataCart,
                'service' => $service,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total_price,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }



     public function deleteOnholdOrder(Request $request)
     {
         try {
             $keyCache = 'onHoldCart:user:' . Auth::user()->id . ':' . $request->key;

             // Delete Cache after add to cart
             CacheOnholdControl::where('key',$request->key)->delete();
             Cache::forget($keyCache);

             return response()->json([
                 'code'     => 200,
                 'message'  => 'Delete onhold Berhasil.',
             ], 200);
         } catch (\Throwable $th) {
             return response()->json(['error' => $th->getMessage()], 500);
         }
    }

    public function printStruk(Request $request){
        $orders = Order::where('id', $request->id)->first();
        $orderProducts = OrderProduct::where('order_id', $orders->id)->get();

        if (count($orderProducts) != 0) {
            $connector = new NetworkPrintConnector("192.168.123.120", 9100);
            $printer = new Printer($connector);

            /* Initialize */
            $printer -> initialize();

            // membuat fungsi untuk membuat 1 baris tabel, agar dapat dipanggil berkali-kali dgn mudah
            function buatBaris4Kolom($kolom1, $kolom2, $kolom3) {
                // Mengatur lebar setiap kolom (dalam satuan karakter)
                $lebar_kolom_1 = 10;
                $lebar_kolom_2 = 9;
                $lebar_kolom_3 = 19;

                // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n
                $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
                $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
                $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);

                // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
                $kolom1Array = explode("\n", $kolom1);
                $kolom2Array = explode("\n", $kolom2);
                $kolom3Array = explode("\n", $kolom3);

                // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
                $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array));

                // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
                $hasilBaris = array();

                // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris
                for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {
                    // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan,
                    $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
                    $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ");
                    $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ");

                    // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
                    $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3 ;
                }

                // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
                return implode("\n", $hasilBaris) . "\n";
            }


            // Membuat judul
            $printer->initialize();
            $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT); // Setting teks menjadi lebih besar
            $printer->setJustification(Printer::JUSTIFY_CENTER); // Setting teks menjadi rata tengah
            $printer->text("A2 Coffee & Eatery\n");
            $printer->text("\n");

            // Data transaksi
            $printer->initialize();
            $printer->text("--------------------------------\n");
            $printer->text("No Inv : ".$orders->no_invoice."\n");
            $printer->text("Waktu  : ".$orders->created_at."\n");
            $printer->text("--------------------------------\n");

            $printer->text("Customer             : ".$orders->customer_name ?? '-'."\n");
            $printer->text("Order                : ".$orders->inputer."\n");
            $printer->text("Table                : ".$orders->table."\n");
            $printer->text("Metode Pembayaran    : ".$orders->payment_method."\n");

            // Membuat tabel
            $printer->initialize(); // Reset bentuk/jenis teks
            $printer->text("--------------------------------\n");
            $printer->text(buatBaris4Kolom("Menu", "Qty", "Price"));
            $printer->text("--------------------------------\n");

            // Order Product
            foreach ($orderProducts as $key => $orderProduct) {
                $priceFormatted = 'Rp.' . number_format($orderProduct->selling_price, 0);
                $printer->text(buatBaris4Kolom($orderProduct->name, $orderProduct->qty, $priceFormatted));
                $printer->text("--------------------------------\n");
            }

            $printer->text("--------------------------------\n");

            $printer->text("Sub Total          : Rp.".number_format($orders->subtotal,0)."\n");
            $printer->text("Service            : Rp.".number_format($orders->service,0)."\n");
            $printer->text("Tax                : Rp.".number_format($orders->pb01,0)."\n");
            $printer->text("Total              : Rp.".number_format($orders->total,0)."\n");

            $printer->text("\n");

            // Pesan penutup
            $printer->initialize();
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Bill Terbayar\n");
            $printer->text("Terima kasih telah berbelanja\n");
            $printer->text("Silahkan Datang Kembali\n");

            $printer->feed(3); // mencetak 5 baris kosong agar terangkat (pemotong kertas saya memiliki jarak 5 baris dari toner)
            $printer->cut();
            $printer->close();
            return redirect()->back()->with('success','Berhasil Tercetak ');
        }else{
            return redirect()->back()->with('failed','Print Gagal ');
        }
    }

    public function printBill(Request $request){
        $orders = Order::where('id', $request->id)->first();
        $orderProducts = OrderProduct::where('order_id', $orders->id)->get();

        if (count($orderProducts) != 0) {
            $connector = new NetworkPrintConnector("192.168.123.120", 9100);
            $printer = new Printer($connector);

            /* Initialize */
            $printer -> initialize();

            // membuat fungsi untuk membuat 1 baris tabel, agar dapat dipanggil berkali-kali dgn mudah
            function buatBaris4Kolom($kolom1, $kolom2, $kolom3) {
                // Mengatur lebar setiap kolom (dalam satuan karakter)
                $lebar_kolom_1 = 10;
                $lebar_kolom_2 = 9;
                $lebar_kolom_3 = 19;

                // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n
                $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
                $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
                $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);

                // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
                $kolom1Array = explode("\n", $kolom1);
                $kolom2Array = explode("\n", $kolom2);
                $kolom3Array = explode("\n", $kolom3);

                // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
                $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array));

                // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
                $hasilBaris = array();

                // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris
                for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {
                    // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan,
                    $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
                    $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ");
                    $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ");

                    // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
                    $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3 ;
                }

                // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
                return implode("\n", $hasilBaris) . "\n";
            }


            // Membuat judul
            $printer->initialize();
            $printer->selectPrintMode(Printer::MODE_DOUBLE_HEIGHT); // Setting teks menjadi lebih besar
            $printer->setJustification(Printer::JUSTIFY_CENTER); // Setting teks menjadi rata tengah
            $printer->text("A2 Coffee & Eatery\n");
            $printer->text("\n");

            // Data transaksi
            $printer->initialize();
            $printer->text("--------------------------------\n");
            $printer->text("No Inv : ".$orders->no_invoice."\n");
            $printer->text("Waktu  : ".$orders->created_at."\n");
            $printer->text("--------------------------------\n");

            $printer->text("Customer : ".$orders->customer_name."\n");
            $printer->text("Order    : ".$orders->inputer."\n");
            $printer->text("Table    : ".$orders->table."\n");

            // Membuat tabel
            $printer->initialize(); // Reset bentuk/jenis teks
            $printer->text("--------------------------------\n");
            $printer->text(buatBaris4Kolom("Menu", "Qty", "Price"));
            $printer->text("--------------------------------\n");

            // Order Product
            foreach ($orderProducts as $key => $orderProduct) {
                $priceFormatted = 'Rp.' . number_format($orderProduct->selling_price, 0);
                $printer->text(buatBaris4Kolom($orderProduct->name, $orderProduct->qty, $priceFormatted));
                $printer->text("--------------------------------\n");
            }

            $printer->text("--------------------------------\n");

            $printer->text("Sub Total          : Rp.".number_format($orders->subtotal,0)."\n");
            $printer->text("Service            : Rp.".number_format($orders->service,0)."\n");
            $printer->text("Tax                : Rp.".number_format($orders->pb01,0)."\n");
            $printer->text("Total              : Rp.".number_format($orders->total,0)."\n");

            $printer->text("\n");

            // Pesan penutup
            $printer->initialize();
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Bill Belum Terbayar\n");
            $printer->text("Terima kasih telah berbelanja\n");
            $printer->text("Silahkan Datang Kembali\n");

            $printer->feed(3); // mencetak 5 baris kosong agar terangkat (pemotong kertas saya memiliki jarak 5 baris dari toner)
            $printer->cut();
            $printer->close();
            return redirect()->back()->with('success','Berhasil Tercetak ');
        }else{
            return redirect()->back()->with('success','Print Gagal ');
        }
    }
    // public function printStruk($id){
    //     $data['current_time'] = Carbon::now()->format('Y-m-d H:i:s');

    //     $orders = Order::findOrFail($id);
    //     $data['other_setting'] = OtherSetting::get()->first();

    //     $data['orders'] = $orders;
    //     return PDF::loadview('admin.pos.print.pdf', $data)->stream('order-' . $orders->id . '.pdf');
    // }

    // public function printBill($id){
    //     $data['current_time'] = Carbon::now()->format('Y-m-d H:i:s');

    //     $orders = Order::findOrFail($id);
    //     $data['other_setting'] = OtherSetting::get()->first();

    //     $data['orders'] = $orders;
    //     return PDF::loadview('admin.pos.print.print-bill', $data)->stream('order-' . $orders->id . '.pdf');
    // }

    public function orderPesanan(Request $request){
        $data ['page_title'] = 'Order Pesanan';
        $data['account_users'] = User::get();
        $data['coupons'] = Coupons::get();

        $data['order_products'] = OrderProduct::orderBy('updated_at', 'ASC')->get();
        $data ['other_setting'] = OtherSetting::get()->first();

        if (!$request->has('start_date') || $request->start_date === null) {
            $orders = Order::whereDate('created_at', Carbon::today())
                ->orderBy('no_invoice', 'desc')
                ->get();
        } else {
            $date = $request->start_date;

            $orders = Order::whereDate('created_at', $date)
                ->orderBy('no_invoice', 'desc')
                ->get();
        }

        $data['orders'] = $orders;

        foreach ($orders as $order) {
            $order->elapsed_time = $this->calculateElapsedTime($order->created_at);
        }

        return view('admin.pesanan.index',$data);
    }

    public function calculateElapsedTime($createdAt)
    {
        $now = Carbon::now();
        $created = Carbon::parse($createdAt);
        $elapsedTime = $created->diffForHumans($now);

        return $elapsedTime;
    }

    public function updatePayment(Request $request, $id) {
        try {
            $order = Order::findOrFail($id);
            $order->payment_status  = 'Paid';
            $order->status_input    = 'cloud';
            $order->payment_method  = $request->payment_method;
            $order->cash = $request->cash ?? 0;
            $other_setting  = OtherSetting::get()->first();
            $service        = $other_setting->layanan / 100;
            $biaya_layanan  = 0;
            $kembalian      = 0;
            $subtotal       = $order->subtotal;

            // Coupon
            if ($request->coupon_id) {
                $coupon         = Coupons::findOrFail($request->coupon_id);
                $coupon_type    = $coupon->type;
                $coupon_amount  = 0;
                $temp_total     = 0;

                // Simpan data kupon di tabel OrderCoupon
                OrderCoupon::create([
                    'order_id'           => $order->id,
                    'name'               => $coupon->name,
                    'code'               => $coupon->code,
                    'type'               => $coupon->type,
                    'discount_value'     => $coupon->discount_value,
                    'discount_threshold' => ($coupon_type == 'Percentage Discount') ? $coupon->discount_threshold : null,
                    'max_discount_value' => ($coupon_type == 'Percentage Discount') ? $coupon->max_discount_value : null,
                    'status_input'       => 'cloud',
                ]);

                $coupon->current_usage += 1;
                $coupon->save();

                // Hitung jumlah diskon berdasarkan tipe kupon
                if ($coupon_type == 'Percentage Discount') {
                    $coupon_amount = $subtotal * $coupon->discount_value / 100;

                    // Terapkan maksimal nilai diskon jika ada
                    if ($subtotal >= $coupon->discount_threshold && $coupon_amount > $coupon->max_discount_value) {
                        $coupon_amount = $coupon->max_discount_value;
                    }
                    $order->percent_discount = (int) $coupon->discount_value;
                } else {
                    $coupon_amount  = (int) $coupon->discount_value;
                    $order->price_discount   = $coupon_amount;
                }

                // Periksa biaya layanan
                if ($other_setting->layanan != 0) {
                    $biaya_layanan  = ($subtotal - $coupon_amount) * $service;
                    $temp_total     = ($subtotal - $coupon_amount) + $biaya_layanan;
                } else {
                    $temp_total     = $subtotal - $coupon_amount;
                }

                dd($temp_total);

                // Hitung pajak & total harga
                $taxPriceByCoupon   = $temp_total * ($other_setting->pb01 / 100);
                $totalPriceByCoupon = $temp_total + $taxPriceByCoupon;

                // Set data di Order
                $order->is_coupon   = true;
                $order->service     = $biaya_layanan;
                $order->pb01        = $taxPriceByCoupon;
                $order->total       = $totalPriceByCoupon;
            }

            // Hitung kembalian jika metode pembayaran adalah Cash
            if ($request->payment_method == 'Cash' && $request->cash != null) {
                $kembalian = $request->cash - $order->total;
                $order->kembalian = $kembalian;
            } else {
                $order->kembalian = 0;
            }

            $order->save();



            $table = Table::where('name', $order->table)->first(); // Assuming 'table_name' is the correct field

            if ($table) {
                $table->status_position = 'none';
                $table->save();
            }

            if ($request->cash) {
                return redirect()->back()->with('success','Uang Yang Di kembalikan '. $kembalian);
            }else{
                return redirect()->back()->with('success', 'Update Payment');
            }
        } catch (\Throwable $th) {
            return response()->json(['failed' => true, 'message' => $th->getMessage()]);
        }
    }

    public function returnOrder(Request $request, $id) {
        try {
            // Validate the incoming request
            $request->validate([
                'key' => 'required|string'
            ]);

            // Retrieve the key from the request
            $key = $request->input('key');

            // Check if the key exists in the database
            $validKey = KeyVoid::where('key', $key)->first();

            if (!$validKey) {
                return redirect()->back()->with('failed', 'Kunci tidak valid atau tidak tersedia.');
            }

            // Proceed with the return process
            $order = Order::findOrFail($id);
            $order->payment_status = 'Unpaid';
            $order->payment_method = 'Return';
            $order->save();

            // Optionally update order products
            // $order_products = OrderProduct::where('order_id', $id)->get();
            // foreach ($order_products as $order_product) {
            //     $products = Product::where('name', $order_product->name)->get();
            //     foreach ($products as $product) {
            //         $product->current_stock += $order_product->qty;
            //         $product->save();
            //     }
            // }

            // Delete the used key from the database
            $validKey->delete();

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Orderan Berhasil di Return.');
        } catch (\Throwable $th) {
            return response()->json(['failed' => true, 'message' => $th->getMessage()]);
        }
    }
}
