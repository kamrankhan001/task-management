<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken(Str::random(20))->plainTextToken;

                $message = $user->is_admin ? 'Admin login successful' : 'User login successful';

                return response()->json([
                    'success' => $message,
                    'token' => $token,
                    'user' => $user,
                ]);
            }

            return response()->json(['error' => 'Invalid credentials'], 401);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function resetPassword(Request $request, $id)
    {
        try {
            $rules = [
                'oldPassword' => 'required|string|current_password',
                'password' => 'required|string|confirmed|min:8',
            ];

            $messages = [
                'oldPassword.required' => 'Please enter your current password.',
                'oldPassword.string' => 'Current password must be a text string.',
                'oldPassword.current_password' => 'Incorrect current password. Please try again.',
                'password.required' => 'Please enter a new password.',
                'password.string' => 'New password must be a text string.',
                'password.confirmed' => 'New passwords do not match. Please re-enter.',
                'password.min' => 'New password must be at least :min characters long.',
            ];

            $request->validate($rules, $messages);

            $user = User::findOrFail($id);

            if (!Hash::check($request->oldPassword, $user->password)) {
                return response()->json(['error' => 'Invalid old password'], 422);
            }

            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json(['success' => 'Password reset successfully'], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['success' => 'Logout successful'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to revoke token'], 500);
        }
    }
}
