<!DOCTYPE html>
<html>
<head>
    <title>Notification Email</title>
</head>
<body>
    <h1>{{ $details['title'] }}</h1>
    <p>{{ $details['body'] }}</p>
    <p>Thank you,</p>
    <p>{{ config('app.name') }}</p>
</body>
</html>
