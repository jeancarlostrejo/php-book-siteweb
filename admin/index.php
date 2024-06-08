<?php

session_start();
if (isset($_POST["send"])) {
    if (empty($_POST["user"]) || empty($_POST["password"])) {
        $error = "Todos los campos son obligatorios";
        $_SESSION["error"] = $error;
        $_SESSION["user"] = $_POST["user"];
        header("Location: index.php");
        die;
    } else {

        if ($_POST["user"] === "ferre" && $_POST["password"] === "123456789") {
            $_SESSION["logged"] = "ok";
            $_SESSION["username"] = $_POST["user"];

            header("Location: home.php");
            exit;
        }
    }
}

if (isset($_SESSION["error"])) {
    $error = $_SESSION["error"];
    $user = $_SESSION["user"];
    unset($_SESSION["error"]);
    unset($_SESSION["user"]);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
</head>
<body>
    <!-- Login 10 - Bootstrap Brain Component -->
    <section class="bg-light py-3 py-md-5 py-xl-8">
        <div class="container">
            <div class="row justify-content-center">
                <div
                    class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
                    <div class="card border border-light-subtle rounded-4">
                        <div class="card-body p-3 p-md-4 p-xl-5">
                            <form action="" method="POST">
                                <h4 class="text-center mb-4">Iniciar sesión</h4>

                                <?php if (isset($error)): ?>
                                <div class="alert alert-danger alert-dismissible fade show text-center"
                                    role="alert">
                                    <p><?=$error;?></p>
                                    <button type="button" class="btn-close"
                                        data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                <?php endif;?>
                                <div class="row gy-3 overflow-hidden">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="text"
                                                class="form-control" name="user"
                                                id="user" placeholder="Usuario"
                                                value="<?=$user ?? '';?>">
                                            <label for="user"
                                                class="form-label text-secondary">Usuario</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="password"
                                                class="form-control"
                                                name="password" id="password"
                                                placeholder="Password">
                                            <label for="password"
                                                class="form-label text-secondary ">Contraseña</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button
                                                class="btn btn-primary btn-lg"
                                                type="submit"
                                                name="send">Acceder</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div
                        class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-center mt-4">
                        <a href="#!"
                            class="link-secondary text-decoration-none">Create
                            new account</a>
                        <a href="#!"
                            class="link-secondary text-decoration-none">Forgot
                            password</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="../js/bootstrap.bundle.js"></script>
</body>
</html>