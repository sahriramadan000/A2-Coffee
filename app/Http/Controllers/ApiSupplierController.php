<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ApiSupplierController extends Controller
{
    // Method to retrieve products (example of using the token for authentication)
    public function index()
    {
        try {
            $products = Supplier::orderBy('fullname', 'asc')->get();
            return response()->json($products, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve Supplier'], 500);
        }
    }


    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'fullname'         => 'required|string',
                'company'          => 'required|string',
                'address'          => 'required',
                'email'            => 'required',
                'phone'            => 'required',
            ]);

            $code = Supplier::latest()->first();
            if ($code) {
                $code = $code->code;
                $code = substr($code, 4);
                $code = intval($code) + 1;
                $code = 'SUPP' . str_pad($code, 5, '0', STR_PAD_LEFT);
            } else {
                $code = 'SUPP00001';
            }

            $supplier               = new Supplier();
            $supplier->code         = $code;
            $supplier->fullname     = $validatedData['fullname'];
            $supplier->company      = $validatedData['company'];
            $supplier->address      = $validatedData['address'];
            $supplier->email        = $validatedData['email'];
            $supplier->phone        = $validatedData['phone'];

            $supplier->save();

            return response()->json([
                'message' => 'Supplier created successfully',
                'data' => $supplier,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error'     => 'Failed to create Supplier',
                'messages'  => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Supplier $product)
    {
        return $product;
    }

    public function update(Request $request, $supplierId)
    {
        try {
            $validatedData = $request->validate([
                'fullname'         => 'required|string',
                'company'          => 'required|string',
                'address'          => 'required',
                'email'            => 'required',
                'phone'            => 'required',
            ]);

            $code = Supplier::latest()->first();
            if ($code) {
                $code = $code->code;
                $code = substr($code, 4);
                $code = intval($code) + 1;
                $code = 'SUPP' . str_pad($code, 5, '0', STR_PAD_LEFT);
            } else {
                $code = 'SUPP00001';
            }

            $supplier = Supplier::find($supplierId);

            $supplier->code         = $code;
            $supplier->fullname     = $validatedData['fullname'];
            $supplier->company      = $validatedData['company'];
            $supplier->email        = $validatedData['email'];
            $supplier->phone        = $validatedData['phone'];
            $supplier->address      = $validatedData['address'];

            $supplier->save();

            return response()->json([
                'message' => 'Supplier Updated Successfully',
                'data' => $supplier,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error'     => 'Failed to Update Supplier',
                'messages'  => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request, $productId)
    {
        try {
            $product = Supplier::findOrFail($productId);
            $product->delete();

            return response()->json([
                'message' => 'Suppleir deleted successfully',
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Suppleir not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete Suppleir',
                'messages'  => $e->getMessage(),
            ], 500);
        }
    }
}
