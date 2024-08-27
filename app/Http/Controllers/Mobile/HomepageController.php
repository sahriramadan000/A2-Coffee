<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\OtherSetting;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class HomepageController extends Controller
{
    public function index(){

        $tags = Tag::orderBy('name', 'asc')->get();
        $products = Product::orderBy('name', 'asc')->get();

        $productsByTag = [];

        foreach ($tags as $tag) {
            $productsByTag[$tag->slug] = $tag->products;
        }

        $data = [
            'products' => $products,
            'tags' => $tags,
            'productsByTag' => $productsByTag,
        ];

        return view('mobile.homepage.index', $data);
    }

    public function getModalAddProduct($productId)
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
        
        return View::make('mobile.homepage.modal')->with([
            'product'     => $productById,
            'addons'      => $formattedAddons
        ]);
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

            // Gunakan session 'guest' untuk tamu
            $sessionId = 'guest';

            $cartContent = Cart::session($sessionId)->getContent();

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
                Cart::session($sessionId)->update($existingItem->id, [
                    'quantity' => $request->quantity,
                    'attributes' => $existingItem->attributes->toArray(),
                ]);
            } else {
                // Jika item belum ada, tambahkan ke keranjang
                Cart::session($sessionId)->add(array(
                    'id'              => $itemIdentifier,
                    'name'            => $product->name,
                    'price'           => $totalPrice,
                    'quantity'        => $request->quantity,
                    'attributes'      => $productDetailAttributes,
                    'associatedModel' => Product::class
                ));
            }

            return response()->json([
                'success'   => 'Product '.$product->name.' Berhasil masuk cart!',
                'data'      => Cart::session($sessionId)->getContent()->toArray(),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(['failed' => 'Product '.$product->name.' gagal masuk cart!'. $th->getMessage()], 500);
        }
    }

    public function detailCategory($category){
        $data['category'] = Tag::where('name', $category)->first();
        if (!$data['category']) {
            abort(404);
        }
        $data['products'] = Product::whereHas('tags', function ($query) use ($category) {
            $query->where('name', $category);
        })->orderBy('name', 'asc')->get();
    
        return view('mobile.homepage.detail-category', $data);
    }
    
}
