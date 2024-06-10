<?php
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
            require_once "./actions/addBook.php";
            break;

        case "modify":
            require_once "./actions/modifyBook.php";
            break;

        case "select":
            require_once "./actions/selectBook.php";
            break;

        case "cancel":
            header("Location: " . $_SERVER["REQUEST_URI"]);
            exit;

            break;

        case "delete":
            require_once "./actions/deleteBook.php";
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
                        class="form-control" placeholder="Nombre del libro"  />
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
                <button type="submit" class="btn btn-success m-1" name="action" <?php echo ($action === "select") ? "disabled" : ""; ?>
                    value="add">Agregar</button>
                <button type="submit" class="btn btn-warning m-1" name="action"
                    value="modify"<?php echo ($action !== "select") ? "disabled" : ""; ?> >Modificar</button>
                <button type="submit" class="btn btn-danger m-1" name="action"
                    value="cancel" <?php echo ($action !== "select") ? "disabled" : ""; ?> >Cancelar</button>
            </div>

        </form>
    </div>
</div>

<div class="table-responsive col-sm-12 col-md-7 col-lg-7 col-xl-7">
    <h3 class="text-center text-secondary">Listado de Libros</h3>
    <table id="myTable" class="table table-bordered">
        <thead class="table-info">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Imagen</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if($stm->rowCount() > 0): ?>
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


<script>
    let table = new DataTable("#myTable", {
        lengthMenu: [3,6,9],
        language: {
            emptyTable: 'No data available in table'
        }
    });
</script>
<?php require_once "../templates/messages.php";?>
<?php require_once "../templates/footer.php";?>