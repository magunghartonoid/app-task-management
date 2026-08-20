<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RequestsExport implements FromView, ShouldAutoSize
{
    protected Collection $requests;

    protected array $header;

    public function __construct(Collection $requests, array $header)
    {
        $this->requests = $requests;
        $this->header = $header;
    }

    public function view(): View
    {
        return view('reports.excel', array_merge($this->header, [
            'requests' => $this->requests,
        ]));
    }
}
