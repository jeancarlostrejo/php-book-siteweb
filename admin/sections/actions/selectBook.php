<?php

if (!empty($bookId)) {

    $stm = $pdo->prepare("SELECT * FROM books WHERE id = ?");

    $stm->bindParam(1, $bookId);
    $stm->execute();

    $book = $stm->fetch();
}
