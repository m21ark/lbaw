<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Recover Password</title>
</head>

<body>
    <h1>Account Recovery</h1>

    <p>At your request, this email serves to recover your password for your <b>Nexus Network</b> account.</p>

    <p>Username: {{ $mailData['name'] }}</p>
    <p>Email: {{ $mailData['email'] }}</p>
    <p>Token: {{ $mailData['token'] }}</p>

</body>

</html>
