<?php

namespace App\DataTables;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Facades\DataTables;

class ExpensesDataTable
{
    public function query(): Builder
    {
        return Expense::query()->latest('purchased_at')->latest('id');
    }

    public function json()
    {
        return DataTables::eloquent($this->query())
            ->editColumn('purchased_at', fn (Expense $expense) => $expense->purchased_at->format('d M Y'))
            ->editColumn('unit_price', fn (Expense $expense) => '₹'.number_format($expense->unit_price, 0))
            ->editColumn('total_amount', fn (Expense $expense) => '<strong>₹'.number_format($expense->total_amount, 0).'</strong>')
            ->addColumn('purchased_by_badge', fn (Expense $expense) => '<span class="admin-badge admin-badge--'.e($expense->purchased_by).'">'.e($expense->purchased_by_label).'</span>')
            ->addColumn('action', function (Expense $expense) {
                $editUrl = route('admin.expenses.edit', $expense);
                $deleteUrl = route('admin.expenses.destroy', $expense);

                return '
                    <div class="admin-row-actions">
                        <a href="'.$editUrl.'" class="admin-icon-btn" title="Edit expense" aria-label="Edit expense">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                        </a>
                        <button type="button" class="admin-icon-btn admin-icon-btn--danger js-expense-delete" data-url="'.$deleteUrl.'" data-title="'.e($expense->title).'" title="Delete expense" aria-label="Delete expense">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                        </button>
                    </div>';
            })
            ->rawColumns(['total_amount', 'purchased_by_badge', 'action'])
            ->toJson();
    }
}
