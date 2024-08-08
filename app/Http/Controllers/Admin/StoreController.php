<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OtherSetting;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;

class StoreController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:attendance-list', ['only' => ['index', 'getAttendances']]);
        // $this->middleware('permission:attendance-create', ['only' => ['getModalAdd','store']]);
        // $this->middleware('permission:attendance-edit', ['only' => ['getModalEdit','update']]);
        // $this->middleware('permission:attendance-delete', ['only' => ['getModalDelete','destroy']]);
    }

    public function index()
    {
        $data['page_title'] = 'Store List';
        return view('admin.store.index', $data);
    }

    public function getAttendances(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Store::orderBy('id','desc'))
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<button type="button" class="btn btn-sm btn-warning store-edit-table" data-bs-target="#tabs-' . $row->id . '-edit-store">Edit</button>';
                    $btn = $btn . ' <button type="button" class="btn btn-sm btn-danger store-delete-table" data-bs-target="#tabs-' . $row->id . '-delete-store">Delete</button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function getModalAdd()
    {
        return View::make('admin.store.modal-add');
    }

    public function store(Request $request)
    {
        try {
            // Cek apakah user sudah check-in hari ini
            $store = Store::whereDate('open_store', Carbon::today())
                ->where('shift', $request->shift)
                ->first();
        
            if ($store) {
                if ($store->close_store) {
                    // Jika sudah check-out, kembalikan pesan bahwa absensi sudah dilakukan
                    return response()->json([
                        'code' => 200,
                        'message' => 'Anda telah melakukan Open Store hari ini untuk ' . $request->shift,
                        'data' => $store
                    ], 200);
                } else {
                    // Jika belum check-out, lakukan check-out
                    $store->close_store = $request->open_store;
                    $store->save();
        
                    return response()->json([
                        'code' => 200,
                        'message' => 'Close Store successful for ' . $request->shift,
                        'data' => $store
                    ], 200);
                }
            } else {
                // Cek apakah shift 1 sudah ditutup sebelum buka shift 2
                if ($request->shift === 'shift2') {
                    $shift1 = Store::whereDate('open_store', Carbon::today())
                        ->where('shift', 'shift1')
                        ->whereNotNull('close_store')
                        ->first();
                    
                    if (!$shift1) {
                        return response()->json([
                            'code' => 400,
                            'message' => 'Shift 1 must be closed before opening Shift 2.',
                        ], 400);
                    }
                }
        
                // Jika belum check-in, buat data check-in baru
                $store = new Store();
                $store->open_store = $request->open_store;
                $store->shift = $request->shift;
        
                $store->save();
        
                return response()->json([
                    'code' => 200,
                    'message' => 'Open Store successful for ' . $request->shift,
                    'data' => $store
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => 'Failed to create or update Store data',
                'data' => [$th->getMessage()]
            ], 500);
        }
        
    }


    private function determineStatus($checkInTime)
    {
        $other_settings = OtherSetting::orderBy('id', 'ASC')->get()->first();
        $onTime = Carbon::createFromTimeString($other_settings->time_start, 'Asia/Jakarta');
        $checkIn = Carbon::createFromFormat('Y-m-d H:i:s', $checkInTime, 'Asia/Jakarta');

        if ($checkIn->lessThanOrEqualTo($onTime)) {
            return 'on_time';
        } else {
            return 'late';
        }
    }
}
