<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ApiUserController extends Controller
{
    public function index()
    {
        try {
            $products = User::orderBy('username', 'asc')->get();
            return response()->json($products, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve User'], 500);
        }
    }


    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'username'       => 'required|string',
                'email'          => 'required|string',
                'password'       => 'required',
                'phone'          => 'required|numeric|min:0',
            ]);


            $user = new User();
            $user->fullname = $validatedData['username'];
            $user->username = $validatedData['username'];
            $user->email = $validatedData['email'];
            $user->password = bcrypt($validatedData['password']);
            $user->phone = $validatedData['phone'];
            $user->assignRole([1]);

            $user->save();

            return response()->json([
                'message' => 'User created successfully',
                'data' => $user,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error'     => 'Failed to create User',
                'messages'  => $e->getMessage(),
            ], 500);
        }
    }

    public function show(User $user)
    {
        return $user;
    }

    public function update(Request $request, $userId)
    {
        try {
            $validatedData = $request->validate([
                'username'          => 'required|string',
                'email'             => 'required',
                'old_password'      => 'nullable',
                'new_password'      => 'nullable',
                'phone'             => 'required|numeric|min:0',
            ]);

            $user = User::find($userId);
            $user->fullname = $validatedData['username'];
            $user->username = $validatedData['username'];
            $user->email = $validatedData['email'];
            $user->phone = $validatedData['phone'];
            $user->assignRole([1]);

            // Check if old password matches
            if ($validatedData['old_password'] && $validatedData['new_password']) {
                if (Hash::check($validatedData['old_password'], $user->password)) {
                    $user->password = bcrypt($validatedData['new_password']);
                } else {
                    // Old password doesn't match, handle the error
                    $request->session()->flash('failed', "Old password doesn't match!");
                    return redirect()->back();
                }
            }

            $user->save();

            return response()->json([
                'message' => 'User Updated Successfully',
                'data' => $user,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error'     => 'Failed to Update User',
                'messages'  => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request, $userId)
    {
        try {
            $user = User::findOrFail($userId);
            $user->delete();

            return response()->json([
                'message' => 'User deleted successfully',
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'User not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete User',
                'messages'  => $e->getMessage(),
            ], 500);
        }
    }
}
