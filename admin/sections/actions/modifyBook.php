<?php

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
