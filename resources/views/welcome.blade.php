<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - Pencatatan Keuangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container text-center vh-100 d-flex flex-column justify-content-center align-items-center">
        <h1 class="mb-3">Selamat Datang di Aplikasi Pencatatan Keuangan</h1>
        <p class="mb-4">Kelola keuangan Anda dengan lebih mudah dan efisien</p>
        <div>
            <a href="{{route('register')}}" class="btn btn-primary me-2">Register</a>
            <a href="{{route('login')}}" class="btn btn-secondary">Login</a>
        </div>
        <div class="mt-4">
            <a href="{{route('login_admin')}}" class="btn btn-warning">Login Admin</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
