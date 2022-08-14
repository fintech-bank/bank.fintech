<!doctype html>
<html lang="fr">
<head>
    <title>{{ $title }}</title>
    <link href='//fonts.googleapis.com/css?family=Roboto+Condensed:300italic,400italic,700italic,400,300,700&amp;subset=all' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/pdf.css') }}">
</head>
<body>
    <div class="fs-5 fs-underline mb-10">Information sur le chèque</div>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td>Numéro de chèque</td>
                <td>{{ $chq->number }}</td>
            </tr>
            <tr>
                <td>Montant</td>
                <td>{{ eur($chq->amount) }}</td>
            </tr>
            <tr>
                <td>Date d'encaissement</td>
                <td>{{ $chq->date_enc->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>Payé à</td>
                <td>{{ $chq->creditor }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
