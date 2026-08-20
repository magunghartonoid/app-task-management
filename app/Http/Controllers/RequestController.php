<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Request;
use App\Models\User;
use Illuminate\Http\Request as HttpRequest;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function data()
    {
        $requests = Request::with(['client', 'createdBy', 'assignedTo'])
        ->orderByRaw("CASE priority WHEN 'urgent' THEN 1 WHEN 'high' THEN 2 WHEN 'medium' THEN 3 WHEN 'low' THEN 4 ELSE 5 END");
        return datatables()->of($requests)
            ->addIndexColumn()
            ->addColumn('client_name', function ($request) {
                return $request->client->client_name ?? '-';
            })
            ->addColumn('created_by_name', function ($request) {
                return $request->createdBy->name ?? '-';
            })
            ->addColumn('assigned_to_name', function ($request) {
                return $request->assignedTo->name ?? '-';
            })
            ->addColumn('lampiran', function ($request) {
                if ($request->file) {
                    return '<a href="' . asset('storage/' . $request->file) . '" target="_blank" class="btn btn-sm btn-info">
                                <i class="fas fa-file"></i> Lihat
                            </a>';
                }
                return '-';
            })
            ->addColumn('aksi', function ($request) {
                return view('requests.partials.aksi', compact('request'))->render();
            })
            ->editColumn('status', function ($request) {
                 switch ($request->status) {
                    case 'pending':
                        return '<span class="badge bg-secondary">Pending</span>';
                    case 'in_progress':
                        return '<span class="badge" style="background-color:#0d6efd;color:#fff;">In Progress</span>';
                    case 'testing':
                        return '<span class="badge bg-warning">Testing</span>';
                    case 'completed':
                        return '<span class="badge bg-success">Completed</span>';
                    case 'canceled':
                        return '<span class="badge bg-danger">Canceled</span>';
                        default: return $request->status;
                }
            })
            ->editColumn('priority', function ($request) {
                switch ($request->priority) {
                    case 'urgent':
                        return '<span class="badge bg-danger">Urgent</span>';
                    case 'high':
                        return '<span class="badge" style="background-color:#fd7e14;color:#fff;">High</span>';
                    case 'medium':
                        return '<span class="badge bg-warning">Medium</span>';
                    case 'low':
                        return '<span class="badge bg-info">Low</span>';
                        default: return $request->priority;
                }
            })
            ->rawColumns(['priority', 'status', 'lampiran', 'aksi'])
            ->make(true);
    }

    public function index()
    {
        $requests = Request::with(['client', 'createdBy', 'assignedTo'])->get();
        return view('requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        $users = User::all();
        return view('requests.create', compact('clients', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(HttpRequest $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'created_by' => 'required|exists:users,id',
            'assigned_to' => 'required|exists:users,id',
            'request' => 'required',
            'request_start_date' => 'required|date',
            'request_deadline_date' => 'nullable|date',
            'priority' => 'required',
            'status' => 'required',
            'file' => 'nullable|mimes:jpg,jpeg,png,webp,pdf,docx,xlsx,zip,rar|max:20480',
        ]);

        if ($request->hasFile('file')) {
            $validated['file'] = $request->file('file')->store('requests', 'public');
        }
        Request::create($validated);
        return response()->json([
            'success' => true,
            'message' => 'Data Request berhasil ditambahkan.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        return view('requests.detail', compact('request'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $clients = Client::all();
        $users = User::all();

        return view('requests.edit', compact('request', 'clients', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(HttpRequest $httpRequest, Request $request)
    {
        $validated = $httpRequest->validate([
            'client_id' => 'required|exists:clients,id',
            'created_by' => 'required|exists:users,id',
            'assigned_to' => 'required|exists:users,id',
            'request' => 'required',
            'request_start_date' => 'required|date',
            'request_deadline_date' => 'nullable|date',
            'priority' => 'required',
            'status' => 'required',
            'file' => 'nullable|mimes:jpg,jpeg,png,webp,pdf,docx,xlsx,zip,rar|max:20480',
        ]);

        if ($httpRequest->hasFile('file')) {
            $validated['file'] = $httpRequest->file('file')->store('requests', 'public');
        }

        $request->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data Request berhasil diupdate.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $request->delete();

        return response()->json(['success' => true, 'message' => 'Data request berhasil dihapus.']);
    }
}
