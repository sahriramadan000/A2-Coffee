<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Membership;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class MembershipController extends Controller
{
    public function index()
    {
        $data['page_title'] = 'Membership List';
        return view('admin.membership.index', $data);
    }

    public function getMembership(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Membership::query())
                ->addIndexColumn()
                ->addColumn('customer', function($row){
                    return $row->customer->name;
                })
                ->addColumn('reminder', function ($row) {
                    $endDate = \Carbon\Carbon::parse($row->end_date);
                    $now = \Carbon\Carbon::now();
                    $daysLeft = $now->diffInDays($endDate, false); // false untuk mendapatkan nilai negatif jika sudah lewat

                    // Mengatur pesan reminder
                    if ($daysLeft <= 0) {
                        return '<span class="text-danger">Expired</span>'; // Jika sudah expired
                    } elseif ($daysLeft <= 7) {
                        return '<span class="text-warning">Expire in '.$daysLeft.' days</span>'; // Jika kurang dari 7 hari
                    } else {
                        return '<span class="text-success">Active</span>'; // Jika masih aktif
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<button type="button" class="btn btn-sm btn-warning memberships-edit-table" data-bs-target="#tabs-'.$row->id.'-edit-membership">Edit</button>';
                    $btn .= ' <button type="button" class="btn btn-sm btn-danger memberships-delete-table" data-bs-target="#tabs-'.$row->id.'-delete-membership">Delete</button>';
                    return $btn;
                })
                ->rawColumns(['customer', 'reminder', 'action']) // Pastikan kolom yang dapat diparsing HTML
                ->make(true);
        }
    }


    public function getModalAdd()
    {
        $customer = Customer::orderBy('name','asc')->get();
        return View::make('admin.membership.modal-add')->with([
            'customers'      => $customer,
        ]);
    }

    public function store(Request $request)
    {
        // $dataTag = $request->validated();
        try {
            $membership = new Membership();
            $membership->customer_id        = $request->customer_id;
            $membership->membership_type    = $request->membership_type;
            $membership->start_date         = $request->start_date;
            $membership->end_date           = $request->end_date;
            $membership->status             = $request->status;

            $membership->save();

            $request->session()->flash('success', "Create data membership successfully!");
            return redirect(route('memberships.index'));
        } catch (\Throwable $th) {
            $request->session()->flash('failed', "Failed to create data membership!");
            return redirect(route('memberships.index'));
        }
    }

    public function getModalEdit($id)
    {
        $membership = Membership::findOrFail($id);
        $customers = Customer::orderBy('name','asc')->get();

        return View::make('admin.membership.modal-edit')->with(
        [
            'membership' => $membership,
            'customers' => $customers,
        ]);
    }


    public function update(Request $request, $tagId)
    {
        $dataTag = $request->validated();
        try {
            $membership = Membership::find($tagId);
            $membership->customer_id        = $request->customer_id;
            $membership->membership_type    = $request->membership_type;
            $membership->start_date         = $request->start_date;
            $membership->end_date           = $request->end_date;
            $membership->status             = $request->status;

            $membership->save();

            $request->session()->flash('success', "Update data membership successfully!");
            return redirect(route('memberships.index'));
        } catch (\Throwable $th) {
            $request->session()->flash('failed', "Failed to update data membership!");
            return redirect(route('memberships.index'));
        }
    }

    public function getModalDelete($tagId)
    {
        $tag = Tag::findOrFail($tagId);
        return View::make('admin.membership.modal-delete')->with('tag', $tag);
    }

    public function destroy(Request $request, $tagId)
    {
        try {
            $tag = Tag::findOrFail($tagId);
            $tag->delete();

            $request->session()->flash('success', "Delete data tag successfully!");
        } catch (ModelNotFoundException $e) {
            $request->session()->flash('failed', "Tag not found!");
        } catch (QueryException $e) {
            $request->session()->flash('failed', "Failed to delete data tag!");
        }

        return redirect(route('tags.index'));
    }
}
