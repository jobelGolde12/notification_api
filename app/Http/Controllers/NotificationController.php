<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // GET ALL NOTIFICATIONS
    public function index()
    {
        return response()->json(Notification::orderBy('created_at', 'desc')->get());
    }

    // GET ONE NOTIFICATION
    public function show($id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        return response()->json($notification);
    }

    // CREATE NOTIFICATION
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|integer',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'nullable|string',
        ]);

        $notification = Notification::create($validated);

        return response()->json([
            'message' => 'Notification created successfully',
            'data' => $notification
        ], 201);
    }

    // UPDATE NOTIFICATION
    public function update(Request $request, $id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'message' => 'sometimes|string',
            'type' => 'sometimes|string',
            'is_read' => 'sometimes|boolean',
        ]);

        $notification->update($validated);

        return response()->json([
            'message' => 'Notification updated successfully',
            'data' => $notification
        ]);
    }

    // DELETE NOTIFICATION
    public function destroy($id)
    {
    $notification = Notification::find($id);

        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404);
        }

        $notification->delete();

        return response()->json(['message' => 'Notification deleted successfully']);
    }
}
