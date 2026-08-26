<?php

namespace App\Exports\Ads;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AdsReportExport implements FromView
{
    private $params;
    public function __construct($params)
    {
        $this->params = $params;
    }

    public function view(): View
    {
        return view('admin.ads.export', $this->params);
    }
}
