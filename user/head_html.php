<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Dashboard | E-Billing</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom Modern styles -->
    <link href="../assets/css/modern.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #0f172a;
            color: #f8f9fa;
        }
        #wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }
        #sidebar-wrapper {
            min-width: 250px;
            max-width: 250px;
            min-height: 100vh;
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s;
        }
        #page-content-wrapper {
            width: 100%;
            padding: 20px;
            transition: all 0.3s;
        }
        .card-stats {
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
            transition: transform 0.3s;
        }
        .card-stats:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
