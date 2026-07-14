<?php

namespace App\Http\Controllers;

use App\Models\ApprovalGate;
use App\Models\AuditTrail;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index()
    {
        $approvals = ApprovalGate::latest()->get();
        return view('admin.approvals', compact('approvals'));
    }

    public function approve(Request $request, $id)
    {
        $gate = ApprovalGate::findOrFail($id);
        $gate->update([
            'status'      => 'approved',
            'approved_by' => $request->input('approved_by', 'admin'),
            'approved_at' => now(),
        ]);

        AuditTrail::log($gate->operation_name, 'approve', 'success', 'Approved by ' . $gate->approved_by);

        return redirect()->route('admin.approvals')->with('success', "Operation '{$gate->operation_name}' approved.");
    }

    public function reject(Request $request, $id)
    {
        $gate = ApprovalGate::findOrFail($id);
        $gate->update([
            'status' => 'rejected',
            'reason' => $request->input('reason', 'Rejected by admin'),
        ]);

        AuditTrail::log($gate->operation_name, 'reject', 'failed', $gate->reason);

        return redirect()->route('admin.approvals')->with('error', "Operation '{$gate->operation_name}' rejected.");
    }

    public function store(Request $request)
    {
        $request->validate(['operation_name' => 'required']);

        ApprovalGate::create([
            'operation_name' => $request->operation_name,
            'status'         => 'pending',
            'requested_by'   => $request->input('requested_by', 'system'),
        ]);

        return redirect()->route('admin.approvals')->with('success', 'Approval request created.');
    }
}
