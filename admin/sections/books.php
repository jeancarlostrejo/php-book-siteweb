<?php
session_start();
require_once "../templates/header.php";
require_once "../config/conection.php";

$bookId = $_POST["id"] ?? "";
$bookName = $_POST["name"] ?? "";
$image = $_FILES["image"]["name"] ?? "";
$action = $_POST["action"] ?? "";
$errors = [];

if (isset($_POST["action"]) && $_POST["action"] != "") {

    switch ($action) {
        case 'add':
            if (empty($bookId)) {
                $errors[] = "El id es obligatorio";
            }

            if (empty($bookName)) {
                $errors[] = "El nombre es obligatorio";
            }
            if ($_FILES["image"]["error"] === UPLOAD_ERR_NO_FILE) {
                $errors[] = "La imagen es obligatoria";
            } else {
                $validMimeType = ["image/jpg", "image/jpeg", "image/png"];

                if (!in_array(mime_content_type($_FILES["image"]["tmp_name"]), $validMimeType)) {
                    $errors[] = "Formato de archivo no válido";
                }

                if ($_FILES["image"]["size"] / 1024 > 3072) {
                    $errors[] = "El archivo ha excedido el tamaño permitido";
                }
            }

            if (empty($errors)) {
                if (!file_exists("../../images")) {
                    if (!mkdir("../../images", 0777)) {
                        $errors[] = "Error al crear el directiorio";
                    }
                }

                $imageName = time() . "_" . $_FILES["image"]["name"];

                if (move_uploaded_file($_FILES["image"]["tmp_name"], "../../images/" . $imageName)) {
                    $stm = $pdo->prepare("INSERT INTO books(name, image) VALUES (?,?)");
                    $stm->bindParam(1, $bookName);
                    $stm->bindParam(2, $imageName);

                    $stm->execute();

                    if ($stm->rowCount() > 0) {
                        $_SESSION["exito"] = "Libro agregado correctamente";
                    } else {
                        $errors[] = "Error al guardar la información";
                    }
                } else {
                    $errors[] = "Error al subir la imagen";
                    $_SESSION["errors"] = $errors;
                }

                header("Location: " . $_SERVER["REQUEST_URI"]);
                exit;
            } else {
                $_SESSION["errors"] = $errors;
                header("Location: " . $_SERVER["REQUEST_URI"]);
                exit;
            }
            break;
        case "modify":
            echo "Modificar";
            break;

        case "cancel":
            echo "cancelado";
            break;
        default:

            break;
    }

} else {

    $msgSucces = $_SESSION["exito"] ?? "";
    $errors = $_SESSION["errors"] ?? "";

    unset($_SESSION["exito"]);
    unset($_SESSION["errors"]);
}

?>
<h1 class="text-center mb-4">Administrar libros</h1>
<div class="card col-md-5">
    <div class="card-header">
        Datos del libro
    </div>
    <?php if (!empty($msgSucces)): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <p><?=$msgSucces;?></p>
        <button type="button" class="btn-close" data-bs-dismiss="alert"
            aria-label="Close"></button>
    </div>

    <?php elseif (!empty($errors)): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul>
            <?php foreach ($errors as $error): ?>
            <li><?=$error;?></li>
            <?php endforeach;?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"
            aria-label="Close"></button>
    </div>
    <?php endif;?>
    <div class="card-body">
        <form action="" enctype="multipart/form-data" method="POST">
            <div>
                <div class="mb-3">
                    <label for="id" class="form-label">ID:</label>
                    <input type="text" name="id" id="id" class="form-control"
                        placeholder="ID" />
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre:</label>
                    <input type="text" name="name" id="name"
                        class="form-control" placeholder="Nombre del libro" />
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Imagen:</label>
                    <input type="file" name="image" id="image"
                        class="form-control" accept=".png, .jpg, .jpeg" />
                </div>

            </div>

            <div class="btn-group">
                <button type="submit" class="btn btn-success m-1" name="action"
                    value="add">Agregar</button>
                <button type="submit" class="btn btn-warning m-1" name="action"
                    value="modify">Modificar</button>
                <button type="submit" class="btn btn-danger m-1" name="action"
                    value="cancel">Cancelar</button>
            </div>

        </form>
    </div>
</div>



<div class="table-responsive col-sm-12 col-md-7 col-lg-7 col-xl-7">
    <h3 class="text-center text-secondary">Listado de Libros</h3>
    <table class="table table-bordered">
        <thead class="table-info">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Imagen</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr class="">
                <td scope="row">1</td>
                <td>Memorias de Idun</td>
                <td>Imagen.png</td>
                <td>
                    <div class="btn-group">
                        <button type="button"
                            class="btn btn-success m-1">Editar</button>
                        <button type="button"
                            class="btn btn-warning m-1">Eliminar</button>
                        <button type="button"
                            class="btn btn-danger m-1">Cancelar</button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>


<?php require_once "../templates/footer.php";?>