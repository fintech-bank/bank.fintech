@component('mail::message')
Information Relative à la commande ** OpenBankCommand ** en date du {{ now()->format("d/m/Y à H:i") }}

    @component('mail::table')
        | Bank          | Ouvert / Fermé |
        | ------------- |:--------------:|
        @foreach($banks as $bank)
            | {{ $bank->name }}| {{ $bank->open == 1 ? 'Ouvert' : 'Fermé' }}       |
        @endforeach
    @endcomponent
@endcomponent

