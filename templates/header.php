<?php

session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitio Web</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarColor01"
                aria-controls="navbarColor01" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor01">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="./index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./books.php">Libros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./about.php">Nosotros</a>
                    </li>

                    <?php if (isset($_SESSION["logged"]) && ($_SESSION["logged"] === "ok")): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../admin/home.php">Administrar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../admin/sections/logout.php">Cerrar sesión</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../admin/login.php">Login</a>
                        </li>
                    <?php endif;?>

                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <br>
        <div class="row">
