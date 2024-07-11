<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Attendance\AddAttendanceRequest;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
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
            return DataTables::of(Attendance::query())
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<button type="button" class="btn btn-sm btn-warning attendance-edit-table" data-bs-target="#tabs-' . $row->id . '-edit-attendance">Edit</button>';
                    $btn = $btn . ' <button type="button" class="btn btn-sm btn-danger attendance-delete-table" data-bs-target="#tabs-' . $row->id . '-delete-attendance">Delete</button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        try {
            $attendance = new Attendance();
            $attendance->user_id    = Auth::id();
            $attendance->date       = Carbon::today()->toDateString();
            $attendance->check_in   = $request->check_in;
            $attendance->status     = $this->determineStatus($request->check_in);

            $attendance->save();

            return response()->json([
                'code' => 200,
                'message' => 'Check In successful',
                'data' => $attendance
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => 'Failed to create attendance data',
                'data' => []
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $attendance = Attendance::find($id);

            if (!$attendance) {
                return response()->json([
                    'code' => 404,
                    'message' => 'Attendance not found',
                    'data' => []
                ], 404);
            }

            $attendance->check_out = $request->check_out;
            $attendance->save();

            return response()->json([
                'code' => 200,
                'message' => 'Check Out successful',
                'data' => $attendance
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 500,
                'message' => 'Failed to update attendance data',
                'data' => []
            ], 500);
        }
    }

    public function checkAbsensi()
    {
        try {
            // Menggunakan Carbon untuk mendapatkan tanggal hari ini
            $today = Carbon::today();

            // Mencari data kehadiran berdasarkan tanggal hari ini dan ID pengguna yang sedang login
            $attendance = Attendance::whereDate('date', $today)->where('user_id', Auth::user()->id)->first();

            if (!$attendance) {
                $response = [
                    'code'    => 404,
                    'message' => 'User not found!',
                    'data'    => []
                ];
                return response()->json($response, 200);
            }

            $response = [
                'code'    => 200,
                'message' => 'Get data attendance successfully!',
                'data'    => $attendance
            ];
            return response()->json($response, 200);
        } catch (\Throwable $th) {
            $response = [
                'code'    => 500,
                'message' => 'Internal server error',
                'data'    => []
            ];
            return response()->json($response, 500);
        }
    }

    private function determineStatus($checkInTime)
    {
        $standardCheckInTime = Carbon::createFromTime(9, 0, 0);
        $checkIn = Carbon::parse($checkInTime);

        return $checkIn->lessThanOrEqualTo($standardCheckInTime) ? 'on_time' : 'late';
    }
}
