<?php

namespace App\Exports;

use App\Models\MitraForm;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MitraExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return MitraForm::all();
    }

    public function view(): View
    {
        return view('mitra_form.mitra_form_export', [
            'mitraForm' => MitraForm::orderBy('id', 'desc')->get()
        ]);
    }
}
