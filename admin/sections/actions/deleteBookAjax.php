<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../books.php");
    die;
}

if (!isset($_SESSION["logged"])) {
    header("Location: ../../login.php");

} else {
    if ($_SESSION["logged"] == "ok") {
        $userName = $_SESSION["username"];
    }
}

require_once "../../config/conection.php";

$bookId = $_POST["id"] ?? "";
$response = [];
if ((!empty($bookId))) {
    $stm = $pdo->prepare("SELECT * from books WHERE id = ?");

    $stm->bindParam(1, $bookId);
    $stm->execute();

    $book = $stm->fetch();
    unlink("../../../images/" . $book["image"]);

    $stm = $pdo->prepare("DELETE FROM books WHERE id = ?");

    $stm->bindParam(1, $bookId);
    $stm->execute();

    if ($stm->rowCount() > 0) {
        $_SESSION["exito"] = "Libro eliminado con exito";

        $response["exito"] = true;
        $response["message"] = "Libro eliminado con exito";

        echo json_encode($response);
    } else {
        $response["exito"] = false;
        $response["error"] = "Error al eliminar";

        echo json_encode($response);
    }
} else {

    $response["exito"] = false;
    $response["error"] = "No existe el elemento a eliminar";

    echo json_encode($response);
}
