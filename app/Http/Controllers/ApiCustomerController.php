<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ApiCustomerController extends Controller
{
    // Method to retrieve products (example of using the token for authentication)
    public function index()
    {
        try {
            $customers = Customer::orderBy('name', 'asc')->get();
            return response()->json($customers, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve Customer'], 500);
        }
    }


    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name'           => 'required|string',
                'email'          => 'required|string',
                'phone'          => 'required|numeric|min:0',
                'gender'         => 'required',
                'address'        => 'required',
            ]);

            $code = Customer::latest()->first();
            if ($code) {
                $code = $code->code;
                $code = substr($code, 4);
                $code = intval($code) + 1;
                $code = 'CUST' . str_pad($code, 5, '0', STR_PAD_LEFT);
            } else {
                $code = 'CUST00001';
            }

            $customer = new Customer();
            $customer->code               = $code;
            $customer->name               = $validatedData['name'];
            $customer->email              = $validatedData['email'];
            $customer->phone              = $validatedData['phone'];
            $customer->gender             = $validatedData['gender'];
            $customer->address            = $validatedData['address'];

            $customer->save();

            return response()->json([
                'message' => 'CUstomer created successfully',
                'data' => $customer,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error'     => 'Failed to create Customer',
                'messages'  => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Customer $product)
    {
        return $product;
    }

    public function update(Request $request, $productId)
    {
        try {
            $validatedData = $request->validate([
                'name'           => 'required|string',
                'email'          => 'required|string',
                'phone'          => 'required|numeric|min:0',
                'gender'         => 'required',
                'address'        => 'required',
            ]);

            $code = Customer::latest()->first();
            if ($code) {
                $code = $code->code;
                $code = substr($code, 4);
                $code = intval($code) + 1;
                $code = 'CUST' . str_pad($code, 5, '0', STR_PAD_LEFT);
            } else {
                $code = 'CUST00001';
            }

            $customer                     = Customer::find($productId);
            $customer->code               = $code;
            $customer->name               = $validatedData['name'];
            $customer->email              = $validatedData['email'];
            $customer->phone              = $validatedData['phone'];
            $customer->gender             = $validatedData['gender'];
            $customer->address            = $validatedData['address'];

            $customer->save();

            return response()->json([
                'message' => 'Customer Updated Successfully',
                'data' => $customer,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error'     => 'Failed to Update Customer',
                'messages'  => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request, $productId)
    {
        try {
            $product = Customer::findOrFail($productId);
            $product->delete();

            return response()->json([
                'message' => 'Customer deleted successfully',
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Customer not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete Customer',
                'messages'  => $e->getMessage(),
            ], 500);
        }
    }
}
