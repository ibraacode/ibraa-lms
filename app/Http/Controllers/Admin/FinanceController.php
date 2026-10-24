<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeType;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FinanceController extends Controller
{
    // ================== JENIS TAGIHAN ==================
    public function feeTypes()
    {
        $feeTypes = FeeType::latest()->paginate(10);
        return view('admin.finance.fee-types', compact('feeTypes'));
    }

        public function storeFeeType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'default_amount' => 'required|integer|min:0',
            'period' => 'required|in:one_time,weekly,monthly,yearly',
            'is_mandatory' => 'boolean',
        ]);

        FeeType::create([
            'name' => $request->name,
            'description' => $request->description,
            'default_amount' => $request->default_amount,
            'period' => $request->period, // TAMBAHAN
            'is_mandatory' => $request->boolean('is_mandatory'),
        ]);

        return back()->with('success', 'Jenis tagihan ditambahkan.');
    }

      // ================== PEMASUKAN LAIN (BOS, HIBAH) ==================
    public function otherIncomes()
    {
        $incomes = \App\Models\OtherIncome::with('recordedBy')->latest('received_at')->paginate(15);
        return view('admin.finance.other-incomes', compact('incomes'));
    }

    public function storeOtherIncome(Request $request)
    {
        $request->validate([
            'source' => 'required|string|max:100',
            'description' => 'nullable|string',
            'amount' => 'required|integer|min:1',
            'received_at' => 'required|date',
            'reference' => 'nullable|string',
        ]);

        \App\Models\OtherIncome::create([
            'source' => $request->source,
            'description' => $request->description,
            'amount' => $request->amount,
            'received_at' => $request->received_at,
            'reference' => $request->reference,
            'recorded_by' => auth()->id(),
        ]);

        AuditLog::record('OTHER_INCOME', 'Mencatat pemasukan ' . $request->source . ': Rp ' . number_format($request->amount, 0, ',', '.'));
        return back()->with('success', 'Pemasukan berhasil dicatat.');
    }

    // ================== TAGIHAN (INVOICE) ==================
    public function invoices(Request $request)
    {
        $query = Invoice::with(['student', 'feeType'])->latest();

        if ($request->filled('search')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nis', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->paginate(20)->withQueryString();
        $feeTypes = FeeType::all();

        return view('admin.finance.invoices', compact('invoices', 'feeTypes'));
    }

    public function storeInvoice(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'fee_type_id' => 'required|exists:fee_types,id',
            'amount' => 'required|integer|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:unpaid,exempt',
            'notes' => 'nullable|string',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
            'student_id' => $request->student_id,
            'fee_type_id' => $request->fee_type_id,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        AuditLog::record('CREATE_INVOICE', 'Membuat tagihan ' . $invoice->invoice_number . ' untuk siswa ID ' . $request->student_id);
        return back()->with('success', 'Tagihan berhasil dibuat.');
    }

    // ================== PEMBAYARAN ==================
    public function showInvoice(Invoice $invoice)
    {
        $invoice->load(['student', 'feeType', 'payments.recordedBy']);
        return view('admin.finance.show-invoice', compact('invoice'));
    }

    public function storePayment(Request $request, Invoice $invoice)
    {
        $request->validate([
            'amount_paid' => 'required|integer|min:1',
            'paid_at' => 'required|date',
            'method' => 'required|string|max:50',
            'reference' => 'nullable|string|max:100',
        ]);

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'amount_paid' => $request->amount_paid,
            'paid_at' => $request->paid_at,
            'method' => $request->method,
            'reference' => $request->reference,
            'recorded_by' => Auth::id(),
        ]);

        // Update status invoice
        $totalPaid = $invoice->fresh()->totalPaid();
        if ($totalPaid >= $invoice->amount) {
            $invoice->update(['status' => 'paid']);
        } elseif ($totalPaid > 0) {
            $invoice->update(['status' => 'partial']);
        }

        AuditLog::record('PAYMENT', 'Pembayaran ' . $invoice->invoice_number . ': Rp ' . number_format($request->amount_paid, 0, ',', '.'));
        return back()->with('success', 'Pembayaran berhasil dicatat.');
    }

    // ================== REKAP ==================
        public function summary()
    {
        $totalInvoiced = Invoice::where('status', '!=', 'exempt')->sum('amount');
        $totalPaidFromStudents = Payment::sum('amount_paid');
        $totalOtherIncome = \App\Models\OtherIncome::sum('amount'); // TAMBAHAN
        
        $totalRevenue = $totalPaidFromStudents + $totalOtherIncome; // Total uang masuk
        $totalOutstanding = $totalInvoiced - $totalPaidFromStudents;

        $stats = [
            'total_invoiced' => $totalInvoiced,
            'total_student_paid' => $totalPaidFromStudents,
            'total_other_income' => $totalOtherIncome,
            'total_revenue' => $totalRevenue,
            'total_outstanding' => $totalOutstanding,
            'total_exempt' => Invoice::where('status', 'exempt')->count(),
        ];

        return view('admin.finance.summary', compact('stats'));
    }
}