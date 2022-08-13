<?php

namespace App\Http\Controllers;

use App\Models\Core\Bank;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function refund_request(Request $request)
    {
        $info = Bank::query()->find($request->get('bank_id'));

        if ($info->autorize_refund == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function status_request(Request $request)
    {
        //dd($request->all());
        $bank = Bank::where('name', 'LIKE', '%' . $request->get('bank_name') . "%")->first();
        //dd($bank);

        return $bank->open == 0 ? 'false' : 'true';
    }

    public function inter()
    {
        $generator = Factory::create('fr_FR');
        return [
            'ficp' => $generator->boolean(10),
            'fcc' => $generator->boolean(10)
        ];
    }
}
