<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ContactMessagesDataTable;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(Request $request, ContactMessagesDataTable $dataTable): View|JsonResponse
    {
        if ($request->ajax()) {
            return $dataTable->json();
        }

        $stats = [
            'total' => ContactMessage::count(),
            'unread' => ContactMessage::unread()->count(),
            'this_week' => ContactMessage::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        return view('admin.messages.index', compact('stats'));
    }

    public function show(ContactMessage $contactMessage): View
    {
        $contactMessage->markAsRead();
        $contactMessage->load('user');

        return view('admin.messages.show', compact('contactMessage'));
    }

    public function destroy(ContactMessage $contactMessage): JsonResponse
    {
        $contactMessage->delete();

        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully.',
            'redirect' => route('admin.messages.index'),
        ]);
    }
}
