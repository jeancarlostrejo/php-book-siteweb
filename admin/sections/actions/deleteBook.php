<?php
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
