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
     * Dans la requète il faut (Customer, Agence, num_mandate)
     * @return array[]
     */
    public function transfer_doc(Request $request)
    {
        $faker = Factory::create('fr_FR');
        Storage::disk('public')->makeDirectory($request->get('num_mandate'));
        Storage::disk('public')->makeDirectory($request->get('num_mandate').'/check');

        $list_prlv_valide = [];
        $list_vir_incoming = [];
        $list_vir_outgoing = [];
        $list_chq = [];
        $list_img_cheqs = [];

        $nb_prlv = rand(0, 10);
        $nb_vir_inc = rand(0, 5);
        $nb_vir_out = rand(0, 10);
        $nb_chq = rand(0, 10);

        for ($i = 0; $i <= $nb_prlv; $i++) {
            $list_prlv_valide[$i] = [
                "uuid" => Str::uuid(),
                "creditor" => $faker->company,
                "number_mandate" => Str::random(8),
                "amount" => -$faker->randomFloat(2, 10, 10000),
            ];
        }

        for ($a = 0; $a <= $nb_vir_inc; $a++) {
            $type = ["immediat", "differed", "permanent"];
            $typer = $type[rand(0, 2)];
            $rec_start = Carbon::now()->addDays(rand(0, 80));
            $list_vir_incoming[$a] = [
                "uuid" => Str::uuid(),
                "amount" => $faker->randomFloat(2, 10, 10000),
                "reference" => Str::random(8),
                "reason" => "Virement Entrant de " . $faker->company,
                "type" => $typer,
                "transfer_date" => $typer == 'immediat' ? now() : ($typer == 'differed' ? now()->addDays(rand(1,90)) : null),
                "recurring_start" => $typer == 'permanent' ? $rec_start : 'null',
                "recurring_end" => $typer == 'permanent' ? $rec_start->addMonths(rand(6, 50)) : null,
            ];
        }

        for ($b = 0; $b <= $nb_vir_out; $b++) {
            $list_vir_outgoing[$b] = [
                "uuid" => Str::uuid(),
                "amount" => -$faker->randomFloat(2, 10, 10000),
                "reference" => Str::random(8),
                "reason" => "Virement Sortant de " . $faker->company,
                "transfer_date" => now()->addDays(rand(0, 10))
            ];
        }

        for ($c = 0; $c <= $nb_chq; $c++) {
            $list_chq[$c] = [
                "number" => random_numeric(6),
                "amount" => $faker->randomFloat(2, 5, 10000),
                "date_enc" => $faker->dateTimeBetween('-13 month', '+2 month'),
                "creditor" => $faker->boolean(35) ? $faker->name : $faker->company
            ];
        }

        foreach ($list_chq as $chq) {
            $pdf = Pdf::loadView('pdf.check', [
                'chq' => (object)$chq,
                'agence' => (object)$request->get('agence'),
                'customer' => (object)$request->get('customer'),
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

            $pdf->save(public_path('/storage/'.$request->get('num_mandate').'/check/'.$chq['number'].'.pdf'));

            $list_img_cheqs[] = [
                "number" => $chq['number'],
                "file" => config('app.url').'/storage/'.$request->get('num_mandate').'/check/'.$chq['number'].'.pdf'
            ];
        }

        return [
            "prlv" => $list_prlv_valide,
            "vir_incoming" => $list_vir_incoming,
            "vir_outgoing" => $list_vir_outgoing,
            "cheques" => $list_chq,
            "files_cheques" => $list_img_cheqs
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
