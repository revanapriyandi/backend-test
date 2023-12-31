<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Resources\StatusResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

class StatusController extends Controller
{
    public function index()
    {
        try {
            $status = Status::with('user', 'images')
                ->latest()
                ->paginate(15);

            return new JsonResource($status, 200);
        } catch (Exception $e) {
            return new JsonResponse([
                'message' => 'Something went wrong'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'body' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            $status = auth()->user()->statuses()->create([
                'hash' => Str::random('22') . strtotime(Carbon::now()),
                'body' => $request->body
            ]);

            if ($request->hasFile('image')) {
                $status
                    ->addMedia($request->file('image'))
                    ->toMediaCollection('images');
            }

            return new JsonResource($status, 201);
        } catch (Exception $e) {
            return new JsonResponse([
                'message' => 'Something went wrong'
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $status = Status::with('user', 'images')
                ->findOrFail($id);

            return new StatusResource($status, 200);
        } catch (Exception $e) {
            if ($e instanceof ModelNotFoundException) {
                return new JsonResponse([
                    'message' => 'Status not found'
                ], 404);
            }

            return new JsonResponse([
                'message' => 'Something went wrong'
            ], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'body' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $status = Status::findOrFail($id);

        $status->update([
            'body' => $request->body
        ]);

        if ($request->hasFile('image')) {
            $status
                ->addMedia($request->file('image'))
                ->toMediaCollection('images');
        }

        return new JsonResource($status, 200);
    }

    public function destroy(string $id)
    {
        try {
            $status = Status::findOrFail($id);

            if ($status->user_id == auth()->user()->id || auth()->user()->is_admin) {
                $status->delete();

                return new JsonResponse([
                    'message' => 'Status deleted successfully'
                ], 200);
            }

            return new JsonResponse([
                'message' => 'You are not authorized to delete this status'
            ], 403);
        } catch (\Throwable $th) {
            return new JsonResponse([
                'message' => 'Status not found'
            ], 404);
        }
    }
}
