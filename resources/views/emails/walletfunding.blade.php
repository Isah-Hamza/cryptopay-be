<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <img src="/favicon.png" alt="logo">
    <h2 style="margin-top: 20px;">Hello, {{ $data['name'] }}</h2>
    <p>
        @if($data['type'] == 1)
            We wish to inform you that your transaction of ${{ $data['amount'] }} to fund your wallet have been submitted. Our verification engine is currently
            processing the transaction. We'll update you on the status of the transaction soon and you wallet and profit balances will reflect accordingly.
        @else
            We wish to inform you that we have received your request to withdraw a sum of ${{ $data['amount'] }} from your wallet. Our payment engine is currently
            processing your request and will update you accordingly on the status of your transaction. We also advice you to keep an eye on your wallet 
            page to catch updates as well.
        @endif
        <br />
        <br />
        <br />
        <b>Thank You.</b>
    </p>
</body>
</html>