<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\User;

class Request extends Model
{
    protected $fillable = [
        'client_id',
        'created_by',
        'assigned_to',
        'request',
        'request_start_date',
        'request_deadline_date',
        'priority',
        'status',
        'file',
    ];

    protected $casts = [
        'request_start_date' => 'date',
        'request_deadline_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
