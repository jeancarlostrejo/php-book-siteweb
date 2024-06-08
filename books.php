<?php

require_once "./templates/header.php";
require_once "./admin/config/conection.php";

$stm = $pdo->prepare("SELECT * FROM books");
$stm->execute();
$books = $stm->fetchAll();

?>


<?php if(empty($books)): ?>
    <div class="container">
        <h2>No hay libros</h2>
    </div>
<?php else : ?>
    <?php foreach ($books as $book): ?>
        <div class="col-md-3 mb-2">
            <div class="card">
                <img class="card-img-top" src="/images/<?=$book["image"];?>" height="200" alt="Title" />
                <div class="card-body">
                    <h4 class="card-title"><?=$book["name"];?></h4>
                    <a href="#" class="btn btn-primary">Ver más</a>
                </div>
            </div>
        </div>
    <?php endforeach;?>

<?php endif ?>
<?php require_once "./templates/footer.php";?>