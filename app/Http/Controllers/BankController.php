<?php

namespace App\Http\Controllers;

use App\Models\Core\Bank;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Faker\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BankController extends Controller
{
    public function test()
    {
        $faker = Factory::create('fr_FR');
        $chq = [
            "number" => random_numeric(6),
            "amount" => $faker->boolean(25) ? - $faker->randomFloat(2, 5, 10000) : $faker->randomFloat(2, 5, 10000),
            "date_enc" => $faker->dateTimeBetween('-13 month', '-2 days'),
            "creditor" => $faker->boolean(35) ? $faker->name : $faker->company
        ];



        try {
            $pdf = Pdf::loadView('pdf.check', [
                'chq' => $chq,
                'agence' => null,
                'customer' => null,
                'title' => "Cheque N°".$chq['number']
            ]);

            $pdf->setOptions([
                'enable-local-file-access' => true,
                'viewport-size' => '1280x1024',
                'footer-right' => '[page]/[topage]',
                'footer-font-size' => 8,
                'margin-left' => 0,
                'margin-right' => 0,
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);
            $pdf->stream();
        }catch (Exception $exception ) {
            dd($exception->getMessage());
        }
    }

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

    /**
     * @param Request $request
     * @return array[]
     */
    public function transfer_doc(Request $request)
    {
        $faker = Factory::create('fr_FR');
        $mvms = collect();
        $account = collect(["number" => random_numeric(9), "solde" => $faker->randomFloat(2, -1000)]);

        for ($i=0; $i <= rand(0,15); $i++) {
            $type_mvm = ['virement', 'prlv'];
            $mvms->push([
                'uuid' => Str::uuid(),
                'type_mvm' => $type_mvm[rand(0,1)],
                'reference' => generateReference(),
                'creditor' => $faker->company,
                'amount' => $faker->randomFloat(2, 10, 1200),
                'date_transfer' => now()->addDays(rand(0,10))
            ]);
        }

        return [
            'mvms' => $mvms->toArray(),
            'account' => $account->toArray()
        ];
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return 'OK';
    }
}
