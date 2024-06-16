<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupRequest;
use App\Http\Resources\GroupResource;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use App\Models\Group;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $cacheKey = 'groups_index_' . md5(json_encode($request->all()));

        $groups = Cache::remember($cacheKey, 60, function () use ($request) {
            $query = Group::query();

            if ($request->has('name')) {
                $name = $request->input('name');
                $query->where('name', 'like', "%{$name}%");
            }

            return $query->with('users')->latest()->paginate(10);
        });

        return GroupResource::collection($groups);
    }

    public function store(GroupRequest $request)
    {
        try {
            $data = $request->validated();

            $group = Group::create([
                'name' => $data['name'],
                'description' => $data['description'],
            ]);

            if (!empty($data['users'])) {
                $group->users()->attach($data['users']);
            }

            // Update cache
            Cache::flush();

            return response()->json(['success' => 'Group created successfully', 'group' => new GroupResource($group->load('users'))], 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 422);
        }
    }

    public function show(string $id)
    {
        try {
            $cacheKey = 'group_show_' . $id;

            $group = Cache::remember($cacheKey, 60, function () use ($id) {
                return Group::with('users')->findOrFail($id);
            });

            return new GroupResource($group);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Group not found'], 404);
        }
    }

    public function update(GroupRequest $request, string $id)
    {
        try {
            $group = Group::findOrFail($id);

            $data = $request->validated();

            $group->update([
                'name' => $data['name'],
                'description' => $data['description'],
            ]);

            if (isset($data['users'])) {
                $group->users()->sync($data['users']);
            }

            // Update cache
            Cache::flush();

            return response()->json(['success' => 'Group updated successfully', 'group' => new GroupResource($group->load('users'))], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Group not found'], 404);
        }
    }

    public function destroy(string $id)
    {
        try {
            $group = Group::findOrFail($id);

            $group->users()->detach();

            $group->delete();

            // Update cache
            Cache::flush();

            return response()->json(['success' => 'Group deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Group not found'], 404);
        }
    }
}
