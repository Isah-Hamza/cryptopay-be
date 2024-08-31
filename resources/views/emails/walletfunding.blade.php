<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <img src="/favicon.png" alt="logo">
    <h2 style="margin-top: 20px;">Hello, {{ $data['name'] }}</h2>
    <p>We wish to inform you that your transaction of ${{ $data['amount'] }} to fund your wallet have been submitted. Our verification engine is currently
        processing the transaction. We'll update you on the status of the transaction soon and you wallet and profit balances will reflect accordingly.
        <br />
        <br />
        <br />
        <b>Thank You.</b>
</p>
</body>
</html>