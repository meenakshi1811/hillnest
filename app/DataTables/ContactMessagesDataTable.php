<?php

namespace App\DataTables;

use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ContactMessagesDataTable
{
    public function query(): Builder
    {
        return ContactMessage::query()->latest();
    }

    public function json()
    {
        return DataTables::eloquent($this->query())
            ->filter(function (Builder $query) {
                $search = request('search.value');

                if ($search) {
                    $query->where(function (Builder $q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('order_number', 'like', "%{$search}%")
                            ->orWhere('message', 'like', "%{$search}%");
                    });
                }
            })
            ->addColumn('sender', function (ContactMessage $contactMessage) {
                $name = '<a href="'.route('admin.messages.show', $contactMessage).'" class="admin-table__link">'.e($contactMessage->name).'</a>';
                $details = e($contactMessage->email).($contactMessage->phone ? ' · '.e($contactMessage->phone) : '');

                return '<div>'.$name.'<br><span style="color:var(--text-light);font-size:12px">'.$details.'</span></div>';
            })
            ->addColumn('message_excerpt', fn (ContactMessage $contactMessage) => e(Str::limit($contactMessage->message, 90)))
            ->editColumn('order_number', fn (ContactMessage $contactMessage) => $contactMessage->order_number ? '<code class="admin-code">'.e($contactMessage->order_number).'</code>' : '<span style="color:var(--text-light)">—</span>')
            ->addColumn('status_badge', fn (ContactMessage $contactMessage) => $contactMessage->isRead()
                ? '<span class="admin-badge admin-badge--default">Read</span>'
                : '<span class="admin-badge admin-badge--pending">New</span>')
            ->editColumn('created_at', fn (ContactMessage $contactMessage) => $contactMessage->created_at->format('d M Y, h:i A'))
            ->addColumn('action', function (ContactMessage $contactMessage) {
                $showUrl = route('admin.messages.show', $contactMessage);
                $deleteUrl = route('admin.messages.destroy', $contactMessage);

                return '
                    <div class="admin-row-actions">
                        <a href="'.$showUrl.'" class="admin-icon-btn" title="View message" aria-label="View message">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                        <button type="button" class="admin-icon-btn admin-icon-btn--danger js-message-delete" data-url="'.$deleteUrl.'" data-label="'.e($contactMessage->name).'" title="Delete message" aria-label="Delete message">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                        </button>
                    </div>';
            })
            ->rawColumns(['sender', 'order_number', 'status_badge', 'action'])
            ->toJson();
    }
}
