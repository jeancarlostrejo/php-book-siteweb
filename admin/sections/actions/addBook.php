<?php

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
