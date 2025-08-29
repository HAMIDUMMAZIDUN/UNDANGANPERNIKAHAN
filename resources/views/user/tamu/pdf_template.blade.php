<!DOCTYPE html>
<html>
<head>
    <title>QR Code</title>
    <style>
        body { font-family: sans-serif; text-align: center; }
    </style>
</head>
<body>
    <h1>QR Code untuk {{ $guest->name }}</h1>
    <div style="margin: 20px;">
        {!! $qrCodeSvg !!}
    </div>
</body>
</html>