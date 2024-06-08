<?php require_once "./templates/header.php";
// echo "<pre>";
// var_dump($_SERVER);
// echo "</pre>";
?>

<div class="card">
  <div class="card-header">
    <h1 class="card-title">Bienvenido <?=$userName?></h1>
  </div>
  <div class="card-body">
    <p class="card-text">Aquí puedes administrar el sistema de registro de libros</p>
    <a href="./sections/books.php" class="btn btn-primary">¡Vamos allá!</a>
  </div>
</div>
<?php require_once "./templates/footer.php";?>