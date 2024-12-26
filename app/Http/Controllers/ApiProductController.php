<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ApiProductController extends Controller
{
    // Method to generate a one-time-use Bearer token
    public function generateToken(Request $request) 
    {
        $email = $request->header('email');
        $password = $request->header('password');

        // Check if email exists in the users table
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
                'status_code' => 401
            ], 401);
        }

        $user->tokens()->where('name', 'one-time-token')->delete(); // Cleanup any old tokens

        // Generate a new token and mark it as one-time use
        $token = $user->createToken('one-time-token');

        // Define the token expiration time (3 minutes)
        $expirationTime = Carbon::now()->addMinutes(5);

        $token->accessToken->expires_at = $expirationTime;
        $token->accessToken->save();

        return response()->json([
            'message' => 'Token generated successfully',
            'token' => $token->plainTextToken, // This is the actual Bearer token
            'expires_in' => $expirationTime->diffInSeconds(Carbon::now()) . ' s',
            'status_code' => 200
        ], 200);
    }

    // Method to retrieve products (example of using the token for authentication)
    public function index()
    {
        try {
            $products = Product::orderBy('name', 'asc')->get();
            return response()->json($products, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve products'], 500);
        }
    }


    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name'              => 'required|string',
                'category'          => 'required|string',
                'cost_price'        => 'required|numeric|min:0',
                'selling_price'     => 'required|numeric|min:0',
                'status'            => 'required',
            ]);

            $code = Product::latest()->first();
            if ($code) {
                $code = $code->code;
                $code = substr($code, 4);
                $code = intval($code) + 1;
                $code = 'PROD' . str_pad($code, 5, '0', STR_PAD_LEFT);
            } else {
                $code = 'PROD00001';
            }

            $product = new Product();
            $product->code             = $code;
            $product->name             = $validatedData['name'];
            $product->slug             = Str::slug($validatedData['name']);
            $product->category         = $validatedData['category'];
            $product->cost_price       = (int) str_replace('.', '', $validatedData['cost_price']);
            $product->selling_price    = (int) str_replace('.', '', $validatedData['selling_price']);
            $product->is_discount      = true;
            $product->percent_discount = 0;
            $product->price_discount   = 0;
            $product->status           = $validatedData['status'];
            $product->save();

            return response()->json([
                'message' => 'Product created successfully',
                'data' => $product,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error'     => 'Failed to create Product',
                'messages'  => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function update(Request $request, $productId)
    {
        try {
            $validatedData = $request->validate([
                'name'              => 'required|string',
                'category'          => 'required|string',
                'cost_price'        => 'required|numeric|min:0',
                'selling_price'     => 'required|numeric|min:0',
                'status'            => 'required',
            ]);

            $code = Product::latest()->first();
            if ($code) {
                $code = $code->code;
                $code = substr($code, 4);
                $code = intval($code) + 1;
                $code = 'PROD' . str_pad($code, 5, '0', STR_PAD_LEFT);
            } else {
                $code = 'PROD00001';
            }

            $product                   = Product::find($productId);
            $product->code             = $code;
            $product->name             = $validatedData['name'];
            $product->slug             = Str::slug($validatedData['name']);
            $product->category         = $validatedData['category'];
            $product->cost_price       = (int) str_replace('.', '', $validatedData['cost_price']);
            $product->selling_price    = (int) str_replace('.', '', $validatedData['selling_price']);
            $product->is_discount      = true;
            $product->percent_discount = 0;
            $product->price_discount   = 0;
            $product->status           = $validatedData['status'];
            $product->save();

            return response()->json([
                'message' => 'Product Updated Successfully',
                'data' => $product,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error'     => 'Failed to Update Product',
                'messages'  => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request, $productId)
    {
        try {
            $product = Product::findOrFail($productId);
            $product->delete();

            return response()->json([
                'message' => 'Product deleted successfully',
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Product not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete Product',
                'messages'  => $e->getMessage(),
            ], 500);
        }
    }
}
