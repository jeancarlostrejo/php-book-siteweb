<?php

session_start();

if (!isset($_SESSION["logged"])) {
    header("Location: ../login.php");
} else {
    if ($_SESSION["logged"] == "ok") {
        $userName = $_SESSION["username"];
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
</head>
<body>
    <?php
if (isset($_SERVER['HTTPS'])) {
    $protocolo = "https://";
} else {
    $protocolo = "http://";
}

$url = $protocolo . $_SERVER['HTTP_HOST'];
?>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse"
                    id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page"
                                href="#">Administración</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                                href="<?=$url . '/admin/home.php';?>">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"  href="<?=$url . '/admin/sections/books.php';?>">Libros</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?=$url;?>">Ver sitio
                                web</a>
                        </li>
                    </ul>
                    <div class="m-2">
                        <p>Hola, <?=$userName?></p>
                    </div>
                    <div >
                        <a class="btn btn-primary"  href="<?=$url . '/admin/sections/logout.php';?>">Cerrar sesión</a>
                    </div>
                </div>
        </nav>
    </header>
    <div class="container">
        <div class="row">