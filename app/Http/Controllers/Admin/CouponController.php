<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Coupon\AddCouponRequest;
use App\Http\Requests\Admin\Coupon\UpdateCouponRequest;
use App\Models\Coupons;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class CouponController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:coupon-list', ['only' => ['index', 'getCoupons']]);
        $this->middleware('permission:coupon-create', ['only' => ['getModalAdd','store']]);
        $this->middleware('permission:coupon-edit', ['only' => ['getModalEdit','update']]);
        $this->middleware('permission:coupon-delete', ['only' => ['getModalDelete','destroy']]);
    }

    public function index()
    {
        $data['page_title'] = 'Coupon List';
        return view('admin.coupon.index', $data);
    }

    public function getCoupons(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Coupons::query())
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" class="btn btn-sm btn-warning coupons-edit-table" data-bs-target="#tabs-'.$row->id.'-edit-coupon">Edit</button>';
                $btn = $btn . ' <button type="button" class="btn btn-sm btn-danger coupons-delete-table"  data-bs-target="#tabs-'.$row->id.'-delete-coupon">Delete</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }

    public function getModalAdd()
    {
        $code = $this->generateCode();
        return View::make('admin.coupon.modal-add')->with([
            'code' => $code,
        ]);
    }

    public function generateCode()
    {
        $dateTimeNow = Carbon::now()->timestamp;
        $uuid = Str::uuid()->toString();
        $uuid = substr($uuid, 0, 5);
        $code = 'CPN' . $dateTimeNow . $uuid;
        return $code;
    }

    public function store(AddCouponRequest $request)
    {
        $dataCoupon = $request->validated();
        try {
            $coupon = new Coupons();
            $coupon->code               = $dataCoupon['code'];
            $coupon->name               = $dataCoupon['name'];
            $coupon->type               = $dataCoupon['type'];
            $coupon->discount_value     = (int) str_replace('.', '', $dataCoupon['discount_value']);
            $coupon->minimum_cart       = (int) str_replace('.', '', $dataCoupon['minimum_cart']);
            $coupon->discount_threshold = isset($dataCoupon['discount_threshold']) ? (int)str_replace('.', '', $dataCoupon['discount_threshold']) : null;
            $coupon->max_discount_value = isset($dataCoupon['max_discount_value']) ? (int)str_replace('.', '', $dataCoupon['max_discount_value']) : null;
            $coupon->expired_at         = $dataCoupon['expired_at'];
            $coupon->limit_usage        = $dataCoupon['limit_usage'];
            $coupon->status             = $dataCoupon['status'];

            $coupon->save();

            $request->session()->flash('success', "Create data coupon successfully!");
            return redirect(route('coupons.index'));
        } catch (\Throwable $th) {
            $request->session()->flash('failed', "Failed to create data coupon!");
            return redirect(route('coupons.index'));
        }
    }

    public function getModalEdit($couponId)
    {
        $coupon = Coupons::findOrFail($couponId);
        return View::make('admin.coupon.modal-edit')->with(
        [
            'coupon' => $coupon,
        ]);
    }


    public function update(UpdateCouponRequest $request, $couponId)
    {
        $dataCoupon = $request->validated();
        try {
            $coupon = Coupons::find($couponId);

            // Check if coupon doesn't exists
            if (!$coupon) {
                $request->session()->flash('failed', "Coupon not found!");
                return redirect()->back();
            }

            $coupon->code               = $coupon->code;
            $coupon->name               = $dataCoupon['name'];
            $coupon->type               = $dataCoupon['type'];
            $coupon->discount_value     = (int) str_replace('.', '', $dataCoupon['discount_value']);
            $coupon->minimum_cart       = (int) str_replace('.', '', $dataCoupon['minimum_cart']);
            $coupon->discount_threshold = isset($dataCoupon['discount_threshold']) ? (int)str_replace('.', '', $dataCoupon['discount_threshold']) : null;
            $coupon->max_discount_value = isset($dataCoupon['max_discount_value']) ? (int)str_replace('.', '', $dataCoupon['max_discount_value']) : null;
            $coupon->expired_at         = $dataCoupon['expired_at'];
            $coupon->limit_usage        = $dataCoupon['limit_usage'];
            $coupon->status             = $dataCoupon['status'];

            $coupon->save();

            $request->session()->flash('success', "Update data coupon successfully!");
            return redirect(route('coupons.index'));
        } catch (\Throwable $th) {
            $request->session()->flash('failed', "Failed to update data coupon!");
            return redirect(route('coupons.index'));
        }
    }

    public function getModalDelete($couponId)
    {
        $coupon = Coupons::findOrFail($couponId);
        return View::make('admin.coupon.modal-delete')->with('coupon', $coupon);
    }

    public function destroy(Request $request, $couponId)
    {
        try {
            $coupon = Coupons::findOrFail($couponId);
            $coupon->delete();

            $request->session()->flash('success', "Delete data coupon successfully!");
        } catch (ModelNotFoundException $e) {
            $request->session()->flash('failed', "Coupon not found!");
        } catch (QueryException $e) {
            $request->session()->flash('failed', "Failed to delete data coupon!");
        }

        return redirect(route('coupons.index'));
    }
}
