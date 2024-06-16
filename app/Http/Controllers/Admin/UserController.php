<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $cacheKey = 'users_index_' . md5(json_encode($request->all()));
        
        $users = Cache::remember($cacheKey, 60, function () use ($request) {
            $query = User::query();

            if ($request->has('name')) {
                $name = $request->input('name');
                $query->where('name', 'like', "%{$name}%");
            }

            return $query->where('is_admin', false)->latest()->paginate(10);
        });

        return UserResource::collection($users);
    }

    public function store(UserRequest $request)
    {
        try {
            $data = $request->validated();

            $data['password'] = Hash::make($data['password']);

            if ($request->hasFile('picture')) {
                $path = $request->file('picture')->store('pictures', 'public');
                $data['picture'] = $path;
            }

            $user = User::create($data);

            Cache::flush();
            
            return response()->json(['success' => 'User created successfully', 'user' => new UserResource($user)], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating the user', 'message' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $cacheKey = 'user_show_' . $id;

            $user = Cache::remember($cacheKey, 60, function () use ($id) {
                return User::with(['tasks', 'groups'])->findOrFail($id);
            });

            return new UserResource($user);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    public function update(UserRequest $request, string $id)
    {
        try {
            $user = User::findOrFail($id);

            $data = $request->validated();

            if (empty($data['password'])) {
                $data['password'] = $user->password;
            } else {
                $data['password'] = Hash::make($data['password']);
            }

            if ($request->hasFile('picture')) {
                $path = $request->picture->store('pictures', 'public');
                $data['picture'] = $path;
            }

            $user->update($data);

            Cache::flush();

            return response()->json(['success' => 'User updated successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the user', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            Cache::flush();

            return response()->json(['success' => 'User deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        }
    }
}
