<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Tourism Booking System') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background:
                linear-gradient(rgba(0, 45, 90, 0.55), rgba(0, 30, 70, 0.75)),
                url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=1600&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            font-family: Arial, sans-serif;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }

        .auth-card {
            width: 100%;
            max-width: 460px;
            background: rgba(255, 255, 255, 0.96);
            border-radius: 22px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
            padding: 35px;
        }

        .brand-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0d6efd;
            color: white;
            font-size: 30px;
            margin: 0 auto 15px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 14px;
        }

        .btn-main {
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            background: #0d6efd;
            border: none;
            color: white;
        }

        .btn-main:hover {
            background: #0b5ed7;
            color: white;
        }

        .auth-link {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 600;
        }

        .auth-link:hover {
            text-decoration: underline;
        }

        .small-muted {
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="auth-wrapper">
        {{ $slot }}
    </div>
</body>
</html>
