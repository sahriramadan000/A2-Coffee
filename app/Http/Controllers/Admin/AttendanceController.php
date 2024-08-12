<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Attendance\AddAttendanceRequest;
use App\Models\Attendance;
use App\Models\OtherSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;

class AttendanceController extends Controller
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
        $data['page_title'] = 'Attendance List';
        return view('admin.attendance.index', $data);
    }

    public function getAttendances(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::user()->hasRole('super-admin') || Auth::user()->hasRole('cashier')) {
                $attendances = Attendance::with('user')->select('attendances.*');
            } else {
                $attendances = Attendance::with('user')->where('user_id', Auth::id())->select('attendances.*');
            }
        
            return DataTables::of($attendances)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return $row->user->username; // Access the related user's name
                })
                ->addColumn('action', function ($row) {
                    $btn = '<button type="button" class="btn btn-sm btn-warning attendance-edit-table" data-bs-target="#tabs-' . $row->id . '-edit-attendance">Edit</button>';
                    $btn .= ' <button type="button" class="btn btn-sm btn-danger attendance-delete-table" data-bs-target="#tabs-' . $row->id . '-delete-attendance">Delete</button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        
    }

    public function getModalAdd()
    {
        return View::make('admin.attendance.modal-add');
    }

    public function store(Request $request)
    {
        try {
            // Mendapatkan semua pengguna dengan kolom yang diperlukan
            $users = User::select('id', 'password', 'fullname')->get();

            // Mencari pengguna yang cocok dengan password yang di-hash
            $getUser = null;
            foreach ($users as $user) {
                if (Hash::check($request->password, $user->password)) {
                    $getUser = $user;
                    break;
                }
            }

            if (!$getUser) {
                return response()->json([
                    'code' => 500,
                    'message' => 'Akun tidak ditemukan!',
                    'data' => []
                ], 200);
            }

            // Cek apakah user sudah check-in hari ini
            $existingAttendance = Attendance::where('user_id', $getUser->id)
                ->whereDate('date', Carbon::today())
                ->first();

            if ($existingAttendance) {
                if ($existingAttendance->check_out) {
                    // Jika sudah check-out, kembalikan pesan bahwa absensi sudah dilakukan
                    return response()->json([
                        'code' => 200,
                        'message' => 'Anda telah melakukan absensi hari ini, ' . $getUser->fullname,
                        'data' => $existingAttendance
                    ], 200);
                } else {
                    // Jika belum check-out, lakukan check-out
                    $existingAttendance->check_out = $request->check_in;
                    $existingAttendance->save();

                    return response()->json([
                        'code' => 200,
                        'message' => 'Check Out successful, ' . $getUser->fullname,
                        'data' => $existingAttendance
                    ], 200);
                }
            } else {
                // Jika belum check-in, buat data check-in baru
                $attendance = new Attendance();
                $attendance->user_id = $getUser->id;
                $attendance->date = Carbon::today()->toDateString();
                $attendance->check_in = $request->check_in;
                $attendance->status = $this->determineStatus($request->check_in);

                $attendance->save();

                return response()->json([
                    'code' => 200,
                    'message' => 'Check In successful, ' . $getUser->fullname,
                    'data' => $attendance
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => 'Failed to create or update attendance data',
                'data' => []
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
