<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    public function store(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'type' => 'required|string|in:complaint,suggestion',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $complaint = Complaint::create($request->all());

        return response()->json(['message' => 'Your message has been sent successfully!', 'data' => $complaint], 201);
    }

    public function index()
    {
        $complaints = Complaint::all();
        return response()->json($complaints);
    }
}