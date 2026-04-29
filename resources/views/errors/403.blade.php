<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso negado — Gestão360</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { background: #f8f5ff; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .icon-box { width: 90px; height: 90px; border-radius: 50%; background: #ede9fe; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; }
    </style>
</head>
<body>
    <div class="text-center px-3">
        <div class="icon-box">
            <i class="fas fa-lock" style="font-size:2.5rem;color:#7212E7;"></i>
        </div>
        <h1 class="fw-bold mb-2" style="color:#7212E7;">Acesso negado</h1>
        <p class="text-muted mb-4">
            Você não tem permissão para acessar esta página.<br>
            Caso acredite que isso é um erro, fale com o administrador.
        </p>
        <a href="{{ url('/') }}" class="btn text-white px-4" style="background:#7212E7;">
            <i class="fas fa-home me-2"></i>Voltar ao início
        </a>
    </div>
</body>
</html>
