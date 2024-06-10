<?php

if (!empty($msgSucces)): ?>
    <script>
        Swal.fire({
            text: "<?=$msgSucces;?>",
            icon: "success"
        });
    </script>
<?php endif;?>

<?php

if (!empty($errors)) {
    $errorsMsg = "";
    foreach ($errors as $errors) {
        $errorsMsg .= $errors . "<br>";
    }
    ?>
    <script>
        Swal.fire({
            html: "<?=$errorsMsg;?>",
            icon: "error"
        });
    </script>
<?php

}?>