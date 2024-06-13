function deleteBook(id) {
  Swal.fire({
    title: "¡Eliminar el registro¡",
    text: "¿Quieres eliminar el registro?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "¡Sí, borrar!",
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: "../sections/actions/deleteBookAjax.php",
        method: "POST",
        data: { id },
        success: function (response) {
            let data = JSON.parse(response)
            if(!data.exito){
                Swal.fire({
                    title: "Error",
                    text: data.error,
                    icon: "error",
                });
            } else {
                window.location.reload();
            }  
        },
        error: function (error) {
          console.log({error})
        }
      });
    }
  });
}
