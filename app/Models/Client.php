<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Request;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'client_address',
        'client_phone',
        'client_email',
        'client_poc',
        'project_name',
        'project_description',
        'project_link',
        'project_start_date',
        'project_end_date',
        'project_repo',
        'project_developer',
        'project_developer_phone',
        'project_status',
    ];

    public function request()
    {
        return $this->hasMany(\App\Models\Request::class)
        ->orderByRaw("CASE priority WHEN 'urgent' THEN 1 WHEN 'high' THEN 2 WHEN 'medium' THEN 3 WHEN 'low' THEN 4 ELSE 5 END");
    }
}
