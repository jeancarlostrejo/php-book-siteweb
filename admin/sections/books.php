<?php
session_start();
require_once "../templates/header.php";
require_once "../config/conection.php";

$bookId = $_POST["id"] ?? "";
$bookName = $_POST["name"] ?? "";
$image = $_FILES["image"]["name"] ?? "";
$action = $_POST["action"] ?? "";
$errors = [];

$stm = $pdo->prepare("SELECT * FROM books");
$stm->execute();
$result = $stm->fetchAll();

if (isset($_POST["action"]) && $_POST["action"] != "") {

    switch ($action) {
        case 'add':

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

            if (empty($bookId)) {
                $errors[] = "El id es obligatorio";
            } else {
                $stm = $pdo->prepare("SELECT * FROM books WHERE id=?");

                $stm->bindParam(1, $bookId);
                $stm->execute();

                $bookRow = $stm->fetch();
            }

            if (empty($bookName)) {
                $errors[] = "El nombre es obligatorio";
            }

            if ($_FILES["image"]["error"] !== UPLOAD_ERR_NO_FILE) {
                $validMimeType = ["image/jpg", "image/jpeg", "image/png"];

                if (!in_array(mime_content_type($_FILES["image"]["tmp_name"]), $validMimeType)) {
                    $errors[] = "Formato de archivo no válido";
                }

                if ($_FILES["image"]["size"] / 1024 > 3072) {
                    $errors[] = "El archivo ha excedido el tamaño permitido";
                }

                if (empty($errors)) {

                    $imageName = time() . "_" . $_FILES["image"]["name"];

                    unlink("../../images/" . $bookRow["image"]);
                    move_uploaded_file($_FILES["image"]["tmp_name"], "../../images/" . $imageName);

                }

            } else {
                $imageName = $bookRow["image"];
            }

            if (empty($errors)) {
                $stm = $pdo->prepare("UPDATE books SET name=?, image=? WHERE id=?");
                $stm->bindParam(1, $bookName);
                $stm->bindParam(2, $imageName);
                $stm->bindParam(3, $bookId);

                $stm->execute();

                if ($stm->rowCount() > 0) {
                    $_SESSION["exito"] = "Libro actualizado";
                } else {
                    $errors[] = "No se modificó el libro";
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

        case "select":

            if (!empty($bookId)) {

                $stm = $pdo->prepare("SELECT * FROM books WHERE id = ?");

                $stm->bindParam(1, $bookId);
                $stm->execute();

                $book = $stm->fetch();
            }

            break;

        case "cancel":
            echo "cancelado";
            break;

        case "delete":

            if ((!empty($bookId))) {

                $stm = $pdo->prepare("SELECT * from books WHERE id = ?");

                $stm->bindParam(1, $bookId);
                $stm->execute();

                $book = $stm->fetch();
                unlink("../../images/" . $book["image"]);

                $stm = $pdo->prepare("DELETE FROM books WHERE id = ?");

                $stm->bindParam(1, $bookId);
                $stm->execute();

                if ($stm->rowCount() > 0) {
                    $_SESSION["exito"] = "Libro eliminado con exito";
                    header("Location: " . $_SERVER["REQUEST_URI"]);
                    exit;
                } else {
                    $_SESSION["error"] = "Error al eliminar";
                    header("Location: " . $_SERVER["REQUEST_URI"]);
                    exit;
                }
            }

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
                    <input readonly type="text" value="<?=$book["id"] ?? "";?>" name="id" id="id" class="form-control"
                        placeholder="ID"/>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre:</label>
                    <input type="text" value="<?=$book["name"] ?? "";?>" name="name" id="name"
                        class="form-control" placeholder="Nombre del libro" required />
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Imagen:</label>
                    <br>
                    <?php if (isset($book["image"]) && $book["image"] != ""): ?>
                        <img class="img-thumbnail rounded" src="../../images/<?=htmlspecialchars($book["image"]);?>" width="75" alt="Imagen del libro <?=htmlspecialchars($book["name"]);?>">
                    <?php endif;?>

                    <input type="file" name="image" id="image"
                        class="form-control" accept=".png, .jpg, .jpeg"/>
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

            <?php if ($stm->rowCount() <= 0): ?>
            <tr>
                <td colspan="6">
                    <h3 class="text-center text-black-50 ">No hay
                        registros</h3>
                </td>
            </tr>
            <?php else: ?>
            <?php foreach ($result as $book): ?>
            <tr>
                <td><?=$book["id"];?></td>
                <td><?=htmlspecialchars($book["name"]);?></td>
                <td>
                    <img class="img-thumbnail rounded" src="../../images/<?=htmlspecialchars($book["image"]);?>" width="75" alt="Imagen del libro <?=htmlspecialchars($book["name"]);?>">

                </td>
                <td>
                    <form class="btn-group" method="POST">
                        <input type="hidden" name="id" value="<?=$book["id"];?>">

                        <input type="submit" name="action" value="select" class="btn btn-primary m-1">

                        <input type="submit" name="action" value="delete" class="btn btn-danger m-1 ">

                    </form>
                </td>
                <?php endforeach;?>
                <?php endif;?>
            </tr>
        </tbody>
    </table>
</div>


<?php require_once "../templates/footer.php";?>