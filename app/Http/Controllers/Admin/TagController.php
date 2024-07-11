<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Tag\AddTagRequest;
use App\Http\Requests\Admin\Tag\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class TagController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:tag-list', ['only' => ['index', 'getTags']]);
        $this->middleware('permission:tag-create', ['only' => ['getModalAdd','store']]);
        $this->middleware('permission:tag-edit', ['only' => ['getModalEdit','update']]);
        $this->middleware('permission:tag-delete', ['only' => ['getModalDelete','destroy']]);
    }

    public function index()
    {
        $data['page_title'] = 'Tag List';
        return view('admin.tag.index', $data);
    }

    public function getTags(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Tag::query())
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" class="btn btn-sm btn-warning tags-edit-table" data-bs-target="#tabs-'.$row->id.'-edit-tag">Edit</button>';
                $btn = $btn . ' <button type="button" class="btn btn-sm btn-danger tags-delete-table"  data-bs-target="#tabs-'.$row->id.'-delete-tag">Delete</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }

    public function getModalAdd()
    {
        return View::make('admin.tag.modal-add');
    }

    public function store(AddTagRequest $request)
    {
        $dataTag = $request->validated();
        try {
            $tag = new Tag();
            $tag->name      = $dataTag['name'];
            $tag->slug      = Str::slug($dataTag['name']);
            $tag->position  = $dataTag['position'];
            $tag->status    = $dataTag['status'];

            $tag->save();

            $request->session()->flash('success', "Create data tag successfully!");
            return redirect(route('tags.index'));
        } catch (\Throwable $th) {
            $request->session()->flash('failed', "Failed to create data tag!");
            return redirect(route('tags.index'));
        }
    }

    public function getModalEdit($tagId)
    {
        $tag = Tag::findOrFail($tagId);
        return View::make('admin.tag.modal-edit')->with('tag', $tag);
    }


    public function update(UpdateTagRequest $request, $tagId)
    {
        $dataTag = $request->validated();
        try {
            $tag = Tag::find($tagId);

            // Check if tag doesn't exists
            if (!$tag) {
                $request->session()->flash('failed', "Tag not found!");
                return redirect()->back();
            }

            $tag->name      = $dataTag['name'];
            $tag->slug      = Str::slug($dataTag['name']);
            $tag->position  = $dataTag['position'];
            $tag->status    = $dataTag['status'];

            $tag->save();

            $request->session()->flash('success', "Update data tag successfully!");
            return redirect(route('tags.index'));
        } catch (\Throwable $th) {
            $request->session()->flash('failed', "Failed to update data tag!");
            return redirect(route('tags.index'));
        }
    }

    public function getModalDelete($tagId)
    {
        $tag = Tag::findOrFail($tagId);
        return View::make('admin.tag.modal-delete')->with('tag', $tag);
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
