<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            $notification = Notification::create([
                'notification_category_id' => $request->notification_category_id,
                'from_user_id' => $request->from_user_id,
                'receiver_id' => $request->receiver_id,
                'title' => $request->title,
                'content' => $request->content,
                'data' => $request->data ? json_encode($request->data) : null,
                'is_read' => false,
                'created_at' => now(),
            ]);

            $options = [
                    'cluster' => env('PUSHER_APP_CLUSTER'),
                    'useTLS' => true
                ];
            $pusher = new \Pusher\Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $options
            );

            $data['notification'] = $notification;
            $pusher->trigger('user.' . $notification['receiver_id'], 'new-notification', $data);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating notification: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'message' => 'Notification created successfully',
            'notification' => $notification,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        //
    }

    public function getAllNotificationsByUserId($user_id)
    {
        $notifications = Notification::where('receiver_id', $user_id)
            ->with(['sender:id,name,avatar'])
            ->with(['category:id,name'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead($notification_id)
    {
        $notification = Notification::find($notification_id);
        if ($notification) {
            $notification->update(['is_read' => true]);
            return response()->json([
                'message' => 'Notification marked as read',
            ]);
        } else {
            return response()->json([
                'message' => 'Notification not found',
            ], 404);
        }
    }

    public function markAllAsRead($user_id)
    {
        Notification::where('receiver_id', $user_id)->update(['is_read' => true]);
        return response()->json([
            'message' => 'All notifications marked as read',
        ]);
    }
}
