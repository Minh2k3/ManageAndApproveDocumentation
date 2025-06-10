<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Pusher\Pusher;

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

    public function sendNotificationToAllAdmins(Request $request)
    {
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

        try {
            $admins = User::where('role_id', 1)->get(); // Assuming role_id 1 is for admins
            foreach ($admins as $admin) {
                Notification::create([
                    'notification_category_id' => $request->notification_category_id,
                    'from_user_id' => $request->from_user_id,
                    'receiver_id' => $admin->id,
                    'title' => $request->title,
                    'content' => $request->content,
                    'data' => $request->data ? json_encode($request->data) : null,
                    'is_read' => false,
                    'created_at' => now(),
                ]);

                $data['notification'] = $notification;
                $pusher->trigger('user.' . $admin->id, 'new-notification', $data);
            }

            return response()->json([
                'message' => 'Notification sent to all admins successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error sending notification: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function sendNotificationToAllUsers(Request $request)
    {
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

        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'data' => $request->data ? json_encode($request->data) : null,
        ];

        try {
            $users = User::all(); // Fetch all users

            foreach ($users as $user) {
                Notification::create([
                    'notification_category_id' => $request->notification_category_id,
                    'from_user_id' => $request->from_user_id,
                    'receiver_id' => $user->id,
                    'title' => $request->title,
                    'content' => $request->content,
                    'data' => $request->data ? json_encode($request->data) : null,
                    'is_read' => false,
                    'created_at' => now(),
                ]);

                $pusher->trigger('user.' . $user->id, 'new-notification', $data);
            }

            return response()->json([
                'message' => 'Notification sent to all users successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error sending notification: ' . $e->getMessage(),
            ], 500);
        }
    }
}
