<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sedang Perbaikan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background-color: #121212; 
            color: #e0e0e0; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            height: 100vh; 
            margin: 0; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .maintenance-card {
            background-color: #1e1e1e;
            border: 1px solid #333;
            border-radius: 15px;
            padding: 3rem;
            max-width: 600px;
            width: 90%;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .gear-icon {
            font-size: 4rem;
            color: #0d6efd;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

    <div class="maintenance-card text-center text-white">
        <div class="gear-icon">⚙️</div>
        <h1 class="fw-bold mb-3">Website Sedang Diperbarui</h1>
        
        <p class="text-muted fs-5 mb-0" style="line-height: 1.6;">
            {{ $exception->getMessage() ?: 'Sistem sedang dalam masa pemeliharaan rutin. Silakan kembali beberapa saat lagi.' }}
        </p>
    </div>

</body>
</html>