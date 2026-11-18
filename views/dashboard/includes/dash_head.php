<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?> – Khotwa Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        .sidebar {
            min-height: 100vh;
            width: 240px;
            background: #1e293b;
            position: fixed;
            top: 0; left: 0;
            padding-top: 1.5rem;
            z-index: 100;
        }
        .sidebar .brand {
            font-size: 1.4rem;
            font-weight: 700;
            color: #fff;
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid #334155;
        }
        .sidebar .nav-link {
            color: #94a3b8;
            padding: .6rem 1.5rem;
            border-radius: 0;
            transition: background .15s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #334155;
            color: #fff;
        }
        .sidebar .nav-link i { margin-right: .5rem; }
        .main-content { margin-left: 240px; padding: 2rem; }
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: .75rem 1.5rem;
            margin-bottom: 2rem;
            border-radius: .5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
    </style>
</head>
<body>
