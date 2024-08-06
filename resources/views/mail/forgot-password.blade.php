<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            border-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            font-size: 12px;
            color: #aaa;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Permintaan Reset Sandi</h1>
        <p>Halo {{ $first_name }},</p>
        <p>Kami mendapatkan permintaan untuk reset kata sandi. Klik tombol dibawah untuk mengganti kata sandi anda:</p>
        <a href="{{ $reset_link }}" class="button">Reset Password</a>
        <p>Jika anda tidak meminta untuk reset kata sandi. Abaikan pesan ini.</p>
        <p>Tautan akan kadaluarsa dalam 60 menit kedepan.</p>
        <div class="footer">
            <p>Terima kasih,<br>Casiwa Team</p>
        </div>
    </div>
</body>
</html>