<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\User;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'created_by',
        'assigned_to',
        'request',
        'request_start_date',
        'request_deadline_date',
        'priority',
        'status',
        'completed_at',
        'file',
    ];

    protected $casts = [
        'request_start_date' => 'date',
        'request_deadline_date' => 'date',
        'completed_at' => 'datetime',
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

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu',
            'in_progress' => 'Diproses',
            'testing' => 'Pengujian',
            'completed' => 'Selesai',
            'canceled' => 'Dibatalkan',
            default => $this->status,
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
            'urgent' => 'Mendesak',
            default => $this->priority,
        };
    }
}
