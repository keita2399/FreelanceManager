<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->invoices()->with('project.client');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->latest('issue_date')->get();

        $totalAmount = $invoices->sum('amount');
        $unpaidAmount = $invoices->whereIn('status', ['送付済', '未入金期限超過'])->sum('amount');
        $paidAmount = $invoices->where('status', '入金済')->sum('amount');

        return view('invoices.index', compact('invoices', 'totalAmount', 'unpaidAmount', 'paidAmount'));
    }

    public function create(Request $request)
    {
        $projects = Auth::user()->projects()->with('client')->get();
        $selectedProject = $request->filled('project_id')
            ? $projects->find($request->project_id)
            : null;
        return view('invoices.create', compact('projects', 'selectedProject'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'invoice_number' => 'required|string|max:50|unique:invoices',
            'amount' => 'required|numeric|min:0',
            'issue_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:issue_date',
            'status' => 'required|in:未送付,送付済,入金済,未入金期限超過',
            'notes' => 'nullable|string',
        ]);

        Auth::user()->invoices()->create($validated);

        return redirect()->route('invoices.index')->with('success', '請求書を登録しました。');
    }

    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);
        $invoice->load('project.client');
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $this->authorize('update', $invoice);
        $projects = Auth::user()->projects()->with('client')->get();
        return view('invoices.edit', compact('invoice', 'projects'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $this->authorize('update', $invoice);

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'invoice_number' => 'required|string|max:50|unique:invoices,invoice_number,' . $invoice->id,
            'amount' => 'required|numeric|min:0',
            'issue_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:issue_date',
            'status' => 'required|in:未送付,送付済,入金済,未入金期限超過',
            'notes' => 'nullable|string',
        ]);

        $invoice->update($validated);

        return redirect()->route('invoices.index')->with('success', '請求書を更新しました。');
    }

    public function destroy(Invoice $invoice)
    {
        $this->authorize('delete', $invoice);
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', '請求書を削除しました。');
    }

    public function pdf(Invoice $invoice)
    {
        $this->authorize('view', $invoice);
        $invoice->load('project.client');
        $user = Auth::user();
        return view('invoices.pdf', compact('invoice', 'user'));
    }
}
