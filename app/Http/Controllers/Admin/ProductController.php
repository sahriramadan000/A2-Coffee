<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\AddProductRequests;
use App\Http\Requests\Admin\Product\UpdateProductRequests;
use App\Models\Addon;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:product-list', ['only' => ['index', 'getProducts']]);
        $this->middleware('permission:product-create', ['only' => ['getModalAdd','store']]);
        $this->middleware('permission:product-edit', ['only' => ['getModalEdit','update']]);
        $this->middleware('permission:product-delete', ['only' => ['getModalDelete','destroy']]);
    }

    public function index()
    {
        $data['page_title'] = 'Product List';
        return view('admin.product.index', $data);
    }

    public function getProducts(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Product::query())
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" class="btn btn-sm btn-warning products-edit-table" data-bs-target="#tabs-'.$row->id.'-edit-product">Edit</button>';
                $btn = $btn . ' <button type="button" class="btn btn-sm btn-danger products-delete-table"  data-bs-target="#tabs-'.$row->id.'-delete-product">Delete</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }

    public function getModalAdd()
    {
        $code   = $this->generateCode();
        $tags   = Tag::select(['id', 'name'])->get();
        $addons = Addon::select(['id', 'name'])->whereNull('parent_id')->get();

        return View::make('admin.product.modal-add')->with([
            'code'      => $code,
            'tags'      => $tags,
            'addons'    => $addons,
        ]);
    }

    public function generateCode()
    {
        $code = Product::latest()->first();
        if ($code) {
            $code = $code->code;
            $code = substr($code, 4);
            $code = intval($code) + 1;
            $code = 'PROD' . str_pad($code, 5, '0', STR_PAD_LEFT);
        } else {
            $code = 'PROD00001';
        }
        return $code;
    }

    public function store(AddProductRequests $request)
    {
        $dataProduct = $request->validated();
        DB::beginTransaction();

        try {
            $product = new Product();
            $product->code             = $dataProduct['code'];
            $product->name             = $dataProduct['name'];
            $product->slug             = Str::slug($dataProduct['name']);
            $product->category         = $dataProduct['category'];
            $product->description      = $dataProduct['description'];
            $product->cost_price       = (int) str_replace('.', '', $dataProduct['cost_price']);
            $product->selling_price    = (int) str_replace('.', '', $dataProduct['selling_price']);
            $product->is_discount      = $dataProduct['is_discount'];
            $product->percent_discount = $dataProduct['percent_discount'];
            $product->price_discount   = (int) str_replace('.', '', $dataProduct['price_discount']);
            $product->stock_per_day    = $dataProduct['stock_per_day'];
            $product->minimum_stock    = $dataProduct['minimum_stock'];
            $product->status           = $dataProduct['status'];


            if ($request->hasFile('picture')) {
                $image = $request->file('picture');
                $imageName = uniqid() . '' . time() . '.webp';

                // Resize and compres image
                $resizedImage = Image::make($image)
                    ->resize(90, 90, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->encode('webp', 80); // Kompresi kualitas 80%

                // Save iamge after resize, compres, and change format to webp format
                $resizedImage->save(public_path('images/products/' . $imageName));
                $product->picture = $imageName;
            }

            $product->save();

            if ($request->has('tag_id')) {
                $product->tags()->sync($dataProduct['tag_id']);
            } else {
                $product->tags()->detach();
            }

            if ($request->has('addon_id')) {
                $product->addons()->sync($dataProduct['addon_id']);
            } else {
                $product->addons()->detach();
            }

            DB::commit();

            $request->session()->flash('success', "Create data product successfully!");
            return redirect(route('products.index'));
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
            $request->session()->flash('failed', "Failed to create data product!");
            return redirect(route('products.index'));
        }
    }

    public function getModalEdit($productId)
    {
        $product = Product::findOrFail($productId);
        $tags = Tag::select(['id', 'name'])->get();
        $addons = Addon::select(['id', 'name'])->whereNull('parent_id')->get();

        return View::make('admin.product.modal-edit')->with(
        [
            'product' => $product,
            'tags' => $tags,
            'addons' => $addons,
        ]);
    }


    public function update(UpdateProductRequests $request, $productId)
    {
        $dataProduct = $request->validated();
        DB::beginTransaction();

        try {
            $product = Product::find($productId);

            // Check if product doesn't exists
            if (!$product) {
                $request->session()->flash('failed', "Product not found!");
                return redirect()->back();
            }

            $product->code             = $dataProduct['code'];
            $product->name             = $dataProduct['name'];
            $product->slug             = Str::slug($dataProduct['name']);
            $product->category         = $dataProduct['category'];
            $product->description      = $dataProduct['description'];
            $product->cost_price       = (int) str_replace('.', '', $dataProduct['cost_price']);
            $product->selling_price    = (int) str_replace('.', '', $dataProduct['selling_price']);
            $product->is_discount      = $dataProduct['is_discount'];
            $product->percent_discount = $dataProduct['percent_discount'];
            $product->price_discount   = (int) str_replace('.', '', $dataProduct['price_discount']);
            $product->stock_per_day    = $dataProduct['stock_per_day'];
            $product->minimum_stock    = $dataProduct['minimum_stock'];
            $product->status           = $dataProduct['status'];

            if ($request->hasFile('picture')) {
                $image = $request->file('picture');
                $imageName = uniqid() . '' . time() . '.webp';

                // Resize and compres image
                $resizedImage = Image::make($image)
                    ->resize(90, 90, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->encode('webp', 80); // Kompresi kualitas 80%

                // Save iamge after resize, compres, and change format to webp format
                $resizedImage->save(public_path('images/products/' . $imageName));
                $product->picture = $imageName;
            }

            $product->save();

            if ($request->has('tag_id')) {
                $product->tags()->sync($dataProduct['tag_id']);
            } else {
                $product->tags()->detach();
            }

            if ($request->has('addon_id')) {
                $product->addons()->sync($dataProduct['addon_id']);
            } else {
                $product->addons()->detach();
            }

            DB::commit();
            $request->session()->flash('success', "Update data product successfully!");
            return redirect(route('products.index'));
        } catch (\Throwable $th) {
            DB::rollBack();
            $request->session()->flash('failed', "Failed to update data product!");
            return redirect(route('products.index'));
        }
    }

    public function getModalDelete($productId)
    {
        $product = Product::findOrFail($productId);
        return View::make('admin.product.modal-delete')->with('product', $product);
    }

    public function destroy(Request $request, $productId)
    {
        DB::beginTransaction();

        try {
            $product = Product::findOrFail($productId);
            $product->delete();

            $request->session()->flash('success', "Delete data product successfully!");

            DB::commit();
        } catch (ModelNotFoundException $e) {
            $request->session()->flash('failed', "Product not found!");
            DB::rollBack();
        } catch (QueryException $e) {
            $request->session()->flash('failed', "Failed to delete data product!");
            DB::rollBack();
        }

        return redirect(route('products.index'));
    }
}
