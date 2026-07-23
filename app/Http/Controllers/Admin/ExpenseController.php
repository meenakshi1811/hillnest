<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ExpensesDataTable;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(Request $request, ExpensesDataTable $dataTable): View|JsonResponse
    {
        if ($request->ajax()) {
            return $dataTable->json();
        }

        $stats = [
            'total' => Expense::sum('total_amount'),
            'meenakshi' => Expense::where('purchased_by', 'meenakshi')->sum('total_amount'),
            'sakshi' => Expense::where('purchased_by', 'sakshi')->sum('total_amount'),
            'count' => Expense::count(),
        ];

        return view('admin.expenses.index', compact('stats'));
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $data = $this->validatedExpense($request);

        Expense::create([
            ...$data,
            'total_amount' => Expense::calculateTotal($data['quantity'], $data['unit_price']),
            'user_id' => $request->user()->id,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Expense logged successfully.',
                'redirect' => route('admin.expenses.index'),
            ]);
        }

        return redirect()->route('admin.expenses.index')->with('success', 'Expense logged.');
    }

    public function edit(Expense $expense): View
    {
        return view('admin.expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense): JsonResponse|RedirectResponse
    {
        $data = $this->validatedExpense($request);

        $expense->update([
            ...$data,
            'total_amount' => Expense::calculateTotal($data['quantity'], $data['unit_price']),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Expense updated successfully.',
                'redirect' => route('admin.expenses.index'),
            ]);
        }

        return redirect()->route('admin.expenses.index')->with('success', 'Expense updated.');
    }

    public function destroy(Expense $expense): JsonResponse
    {
        $expense->delete();

        return response()->json([
            'success' => true,
            'message' => 'Expense deleted successfully.',
        ]);
    }

    private function validatedExpense(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'purchased_by' => ['required', 'in:'.implode(',', array_keys(Expense::PURCHASED_BY))],
            'purchased_at' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
