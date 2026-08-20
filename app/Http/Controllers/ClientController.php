<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Nullable;
use Yajra\DataTables\Facades\DataTables;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function data()
    {
        $clients = Client::query();
        return datatables()->of($clients)->addIndexColumn()->addColumn('aksi', function($client) {
            return view('clients.partials.aksi', compact('client'))->render();
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }

    public function index()
    {
        $clients = Client::latest()->get();
        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,)
    {
        $validated = $request->validate([
            'client_name' => 'required',
            'client_address' => 'nullable',
            'client_phone' => 'required',
            'client_email' => 'nullable',
            'client_poc' => 'required',
            'project_name' => 'required',
            'project_description' => 'nullable',
            'project_link' => 'nullable',
            'project_start_date' => 'required',
            'project_end_date' => 'nullable',
            'project_repo' => 'nullable',
            'project_developer' => 'required',
            'project_developer_phone' => 'required',
            'project_status' => 'required',
        ]);

        Client::create($validated);
        return response()->json(['success' => true, 'message' =>'Data Klien berhasil ditambahkan.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $client->load('request.createdBy','request.assignedTo');

        return view('clients.detail', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'client_name' => 'required',
            'client_address' => 'nullable',
            'client_phone' => 'required',
            'client_email' => 'nullable',
            'client_poc' => 'required',
            'project_name' => 'required',
            'project_description' => 'nullable',
            'project_link' => 'nullable',
            'project_start_date' => 'required',
            'project_end_date' => 'nullable',
            'project_repo' => 'nullable',
            'project_developer' => 'required',
            'project_developer_phone' => 'required',
            'project_status' => 'required',
        ]);

        $client->update($validated);

        return response()->json([ 'success' => true, 'message' => 'Data klien berhasil diupdate.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return response()->json(['success'=> true, 'message'=>'Data klien berhasil dihapus.']);
    }
}