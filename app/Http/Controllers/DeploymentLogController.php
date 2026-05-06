<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeploymentLog;

class DeploymentLogController extends Controller
{
    // GET ALL
    public function index()
    {
        return response()->json(DeploymentLog::latest()->get());
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'deployment_name' => 'required',
            'environment' => 'required',
            'status' => 'required',
        ]);

        $log = DeploymentLog::create($request->all());

        return response()->json([
            'message' => 'Deployment log created successfully',
            'data' => $log
        ]);
    }

    // SHOW
    public function show($id)
    {
        return DeploymentLog::findOrFail($id);
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $log = DeploymentLog::findOrFail($id);
        $log->update($request->all());

        return response()->json([
            'message' => 'Updated successfully',
            'data' => $log
        ]);
    }

    // DELETE
    public function destroy($id)
    {
        DeploymentLog::destroy($id);

        return response()->json([
            'message' => 'Deleted successfully'
        ]);
    }
}
