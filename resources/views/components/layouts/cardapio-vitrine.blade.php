<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Cardápio')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(165deg, #faf8ff 0%, #f5f5f5 45%, #ffffff 100%);
            min-height: 100vh;
        }

        .vitrine-hero {
            background: linear-gradient(135deg, #5b0eb8 0%, #7212E7 50%, #9333ea 100%);
            color: #fff;
        }
    </style>
</head>

<body>
    @yield('content')
</body>

</html>
