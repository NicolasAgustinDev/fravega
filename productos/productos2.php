<?php
// Verificar si la sesión está iniciada
session_start();
// Verificar si hay sesión activa
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>
<body>
    <?php include '../templates/layout.html'?>
    <h1 class="mt-4">Productos</h1>
    <div class="btn-agregar-producto">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#miModal">Agregar producto</button>
    </div>
    <div>
        <table id="productos" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Productos</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
    </div>
    <!-- MODAL -->
    <div class="modal fade" id="miModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar Productto</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            <div class="modal-body">
                <div>
                    <form>
                        <div>
                            <input type="hidden" id="id" name="id">
                            <div class="mb-3">
                                <label for="nombre">Nombre</label>
                                <input type="text" id="nombre" name="nombre" placeholder="Ingrese un producto" required>
                            </div>
                            <div class="mb-3">
                                <label for="precio">Precio</label>
                                <input type="number" id="precio" name="precio" placeholder="Ingrese un Precio" required>
                            </div>
                            <div class="mb-3">
                                <label for="cantidad">Cantidad</label>
                                <input type="number" id="cantidad" name="cantidad" placeholder="Ingrese una cantidad" required>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btnguardar">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <?php include '../templates/footer.html' ?>
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function(){
            var accion = "";
            $('#productos').DataTable({
                dom: 'Bfrtip',
                language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
                },
                ordering: false,
                info: false,
                responsive: true,
                ajax:{
                    url:'../ajax/productos.ajax.php',
                    dataSrc: ''
                },
                columns: [
                    { data: 'id' },
                    { data: 'nombre' },
                    { data: 'precio' },
                    { data: 'cantidad' },
                    {
                        data:null,
                        render:function(data,type,row){
                            return `<button class="btn btn-principal btneditar" data-bs-target="#miModal" data-bs-toggle="modal">
                            <i class="fa-solid fa-pen"></i>
                            </button>
                            <button class ="btn btn-danger btneliminar">
                            <i class="fa-solid fa-trash"></i>
                            </button>
                            `
                        }
                    }
                ]
            })
            $('.btn-agregar-producto').on('click',function(){
                accion = "registrar";
            })

            $('#productos tbody').on('click','.btneliminar',function(){
                var tabla = $('#productos').DataTable();
                var data =tabla.row($(this).parents('tr')).data()
                var id = data ['id'];
                
                var datos = new FormData();
                datos.append('id',id)
                datos.append('accion','eliminar');

                Swal.fire({
                    title: "Confirmacion?",
                    text: "Estas seguro que deseas eliminar este producto!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si",
                    cancelButtonText: "No, cancelar!"
                    }).then((result) => {
                    if (result.isConfirmed) {
                            $.ajax({
                                url: "../ajax/productos.ajax.php",
                                method: "POST",
                                data:datos,
                                cache:false,
                                contentType: false,
                                processData: false,
                                success:function(respuesta){
                                    console.log(respuesta);
                                    $('#productos').DataTable().ajax.reload();
                                }
                            })
                    }else{
                    }
                });
            })
            //Guardar la informacion desde la ventana modal
            $('#btnguardar').on('click',function(){
                Swal.fire({
                    title: "Confirmar ?",
                    text: "Estas seguro que deseas registrar el producto!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si, deseo registrar!",
                    cancelButtonText: "No, cancelar!"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        var nombre = $("#nombre").val(),
                            precio = $("#precio").val(),
                            cantidad = $("#cantidad").val(),
                            id = $("#id").val()

                        var datos = new FormData();
                        datos.append('nombre',nombre)
                        datos.append('precio',precio);
                        datos.append('cantidad',cantidad);
                        datos.append('id',id);
                        datos.append('accion',accion);

                        $.ajax({
                            url: "../ajax/productos.ajax.php",
                            method: "POST",
                            data:datos,
                            cache:false,
                            contentType: false,
                            processData: false,
                            success:function(respuesta){
                                //console.log(respuesta);
                                document.activeElement.blur();
                                $("#miModal").modal('hide');
                                $('#productos').DataTable().ajax.reload();
                                $("#nombre").val(""),
                                $("#precio").val(""),
                                $("#cantidad").val("");
                            }
                        }) 
                    }else{

                    }
                });
            })
        })


    </script>
</body>
</html>









