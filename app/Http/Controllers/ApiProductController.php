<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

        // Generate a new token and mark it as one-time use
        $token = $user->createToken('one-time-token')->plainTextToken;

        // Define the token expiration time (in seconds)
        $expirationSeconds = 5 * 60; // 5 minutes converted to seconds
        $expirationTime = now()->addSeconds($expirationSeconds);

        // Store the token in the database with expiration time
        $user->tokens()->where('name', 'one-time-token')->delete(); // Cleanup any old tokens
        $user->tokens()->create([
            'name' => 'one-time-token',
            'token' => hash('sha256', $token), // Securely store a hashed version
            'expires_at' => $expirationTime
        ]);

        return response()->json([
            'message' => 'Token generated successfully',
            'token' => $token, // This is the actual Bearer token
            'expires_in' => $expirationSeconds . ' s',
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
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        return Product::create($request->all());
    }

    public function show(Product $product)
    {
        return $product;
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->all());
        return $product;
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
