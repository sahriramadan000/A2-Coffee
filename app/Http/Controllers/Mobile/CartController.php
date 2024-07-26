<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\OtherSetting;
use App\Models\Table;
use Illuminate\Http\Request;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class CartController extends Controller
{
    public function index(){
        $sessionId = 'guest';
        $otherSetting = OtherSetting::get()->first();

        $data['subTotal'] = \Cart::session($sessionId)->getTotal();
        $data['layanan'] = $data['subTotal'] * $otherSetting->layanan /100;
        $data['ppn'] = ($data['subTotal'] + $data['layanan']) * $otherSetting->pb01 /100 ;
        $data['total'] = $data['subTotal'] + $data['layanan'] + $data['ppn'];

        // dd($data['total']);
        $data['dataCarts'] = Cart::session($sessionId)->getContent();

        return view('mobile.cart.index',$data);
    }

    public function deleteItem($id){
        $sessionId = 'guest';
        Cart::session($sessionId)->remove($id);
        return redirect()->back()->with('success', 'Item deleted successfully!');
    }
}
