<?php

namespace App\Console\Commands;

use App\Mail\OpenBankCommandMail;
use App\Models\Core\Bank;
use App\Models\User;
use Illuminate\Console\Command;

class OpenBank extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bank:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ouvre ou ferme une banque';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $banks = Bank::all();

        foreach ($banks as $bank) {
            $bank->update([
                'open' => rand(0,1) == 0 ? 0 : 1
            ]);
        }
        $user = User::find(1);

        \Mail::to($user)->send(new OpenBankCommandMail($banks));

        return 0;
    }
}
