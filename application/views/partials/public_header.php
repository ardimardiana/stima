<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?> | <?= htmlspecialchars($event->nama_event, ENT_QUOTES, 'UTF-8'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .hero-section { background-color: #343a40; color: white; padding: 4rem 2rem; margin-bottom: 2rem; border-radius: 0.5rem; }
        .card-header-custom { background-color: #0d6efd; color: white; }
    </style>
</head>
<body>