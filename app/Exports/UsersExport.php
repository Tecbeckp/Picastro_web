<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{

    protected $subscription;

    public function __construct($subscription)
    {
        $this->subscription = $subscription;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::select('email')->where('subscription', $this->subscription)->get();
    }

    public function headings(): array
    {
        return [
            'Email'
        ];
    }
}
