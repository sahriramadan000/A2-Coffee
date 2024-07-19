<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\CacheOnholdControl;
use App\Models\Coupons;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderProductAddon;
use App\Models\OtherSetting;
use App\Models\Product;
use App\Models\ProductTag;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Support\Facades\Cache;

class TransactionController extends Controller
{
    public function index(){
        $data['page_title']     = 'Transaction';
        $data['data_items']     = Cart::session(Auth::user()->id)->getContent();
        $data['products']       = Product::orderby('id', 'asc')->get();
        $data['other_setting']  = OtherSetting::get()->first();
        $service                = (int) str_replace('.', '', $data['other_setting']->layanan);
        $subtotal               = Cart::getTotal();

        $data['service']  = $service;
        $data['subtotal'] = $subtotal;
        $data['tax']      = (($data['subtotal'] + ($data['data_items']->isEmpty() ? 0 : $service)) * $data['other_setting']->pb01/100);
        $data['total']    = ($data['subtotal'] + ($data['data_items']->isEmpty() ? 0 : $service)) + $data['tax'];

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
            $service       = (int) str_replace('.', '', $other_setting->layanan);
            $subtotal      = (Cart::getTotal() ?? '0');
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
        $service = (int) str_replace('.', '', $other_setting->layanan);

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
            $biaya_layanan  = ($subtotal - $coupon_amount) + $service;
            $temp_total     = $biaya_layanan;
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
            'service'       => $service,
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
        $service            = (int) str_replace('.', '', $other_setting->layanan);
        $subtotal           = Cart::getTotal();

        if ($discount_type == 'percent') {
            $discount_amount = $subtotal * $discount_percent / 100;
        } else {
            $discount_amount = $discount_price;
        }

        // Check Layanan
        if ($other_setting->layanan != 0) {
            $biaya_layanan  = ($subtotal - $discount_amount) + $service;
            $temp_total     = $biaya_layanan;
        }else{
            $temp_total     = (($subtotal - $discount_amount) ?? 0);
        }

        $tax    = $temp_total * ($other_setting->pb01 / 100);
        $total  = $temp_total + $tax;

        return response()->json([
            'success'           => 'Discount berhasil ditambahkan!',
            'discount_price'    => $discount_price,
            'discount_percent'  => $discount_percent,
            'discount_type'     => $discount_type,
            'discount_amount'   => $discount_amount,
            'service'           => $service,
            'subtotal'          => $subtotal,
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
                 $service     = (int) str_replace('.', '', $other_setting->layanan);
                 $subtotal    = Cart::getTotal();
                 $tax         = ($subtotal + $service) * ($other_setting->pb01 / 100);
                 $total_price = ($subtotal + $service)  + $tax;


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
                $products = Product::where('name',$orderProduct->name)->first();
                $orderAddOns = OrderProductAddon::where('order_product_id',$orderProduct->id)->first();
                Cart::session(Auth::user()->id)->add([
                    'id' => $orderProduct->id,
                    'name' => $orderProduct->name,
                    'price' => $orderProduct->selling_price,
                    'quantity' => $orderProduct->qty,
                    'attributes' => [
                        'product' => $products,
                        'addons' => $orderAddOns,
                    ],
                ]);
            }

            // Delete Cache after add to cart
            // Cache::forget($keyCache);
            // CacheOnholdControl::where('key', $request->key)->delete();

            // Set return data
            $dataCart = Cart::session(Auth::user()->id)->getContent();
            $service = (int) str_replace('.', '', $other_setting->layanan);
            $subtotal = Cart::getTotal();
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
}
