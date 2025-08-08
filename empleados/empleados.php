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
    <title>Empleados</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>
<body>
    <?php include '../templates/layout.html' ?>
    <h1 class="mt-4">Empleados</h1>
    <div class="btn-agregar-producto">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#miModal">Agregar Empleado</button>
    </div>
    <div>
        <table id="empleados" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>DNI</th>
                    <th>Puesto</th>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar Empleado</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            <div class="modal-body">
                <div>
                    <form>
                        <div>
                            <input type="hidden" id="id" name="id">
                            <div class="mb-3">
                                <label for="nombre">Nombre</label>
                                <input type="text" id="nombre" name="nombre" placeholder="Ingrese un Nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="apellido">Apellido</label>
                                <input type="text" id="apellido" name="apellido" placeholder="Ingrese un apellido" required>
                            </div>
                            <div class="mb-3">
                                <label for="dni">DNI</label>
                                <input type="number" id="dni" name="dni" placeholder="Ingrese un DNI" required>
                            </div>
                            <div class="mb-3">
                                <label for="puesto">Puesto</label>
                                <select id="puesto" name="puesto" class="form-control" required></select>
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
            $('#empleados').DataTable({
                dom: 'Bfrtip',
                language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
                },
                ordering: false,
                info: false,
                responsive: true,
                ajax:{
                    url:'../ajax/empleados.ajax.php',
                    dataSrc: ''
                },
                columns:[
                    {data : 'id'},
                    {data : 'nombre'},
                    {data : 'apellido'},
                    {data : 'dni'},
                    {data : 'puesto'},
                    {
                        data : 'null',
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

            $('#miModal').on('shown.bs.modal',function(){
                cargarPuestos();
            })

            function cargarPuestos(){
                $.ajax({
                    url: '../ajax/puesto.ajax.php',
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        let opciones = '<option value="">Seleccione un puesto</option>';
                        data.forEach(function(puesto){
                            opciones += `<option value="${puesto.id_puesto}">${puesto.puesto}</option>`;
                        });
                        $('#puesto').html(opciones);
                    },
                    error: function() {
                        console.error('Error al cargar los puestos:', error);
                        alert('Error al cargar los puestos.');
                    }
                });
            }




            $('.btn-agregar-producto').on('click',function(){
                accion = "registrar";
            })

            $('#empleados tbody').on('click','.btneditar',function(){
                var tabla = $('#empleados').DataTable();
                var data =tabla.row($(this).parents('tr')).data()
                accion = "modificar";

                $("#id").val(data["id"]);
                $("#nombre").val(data["nombre"]);
                $("#apellido").val(data["apellido"]);
                $("#dni").val(data["dni"]);
                $("#puesto").val(data["puesto"]);
            })

            $('#empleados tbody').on('click','.btneliminar',function(){
                var tabla = $('#empleados').DataTable();
                var data =tabla.row($(this).parents('tr')).data()
                var id = data ['id'];

                var datos = new FormData();
                datos.append('id',id)
                datos.append('accion','eliminar');

                Swal.fire({
                    title: "Confirmacion?",
                    text: "Estas seguro que deseas eliminar a este empleado!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si!",
                    cancelButtonText: "No, cancelar!"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "../ajax/empleados.ajax.php",
                            method: "POST",
                            data:datos,
                            cache:false,
                            contentType: false,
                            processData: false,
                            success:function(respuesta){
                                console.log(respuesta);
                                $('#empleados').DataTable().ajax.reload();
                            }
                        })
                    }else{

                    }
                });
            })
            //Guardar la informacion desde la ventana modal
            $('#btnguardar').on('click',function(){

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                    if (result.isConfirmed) {
                        var nombre = $("#nombre").val(),
                            apellido = $("#apellido").val(),
                            dni = $("#dni").val(),
                            puesto = $("#puesto").val(),
                            id = $("#id").val()
                        var datos = new FormData();
                        datos.append('nombre',nombre)
                        datos.append('apellido',apellido);
                        datos.append('dni',dni);
                        datos.append('puesto',puesto);
                        datos.append('id',id);
                        datos.append('accion',accion);

                        if (nombre === '' || apellido === '' || dni === '') {
                            alert('Por favor, completa todos los campos.');
                            return;
                        }

                        $.ajax({
                            url: "../ajax/empleados.ajax.php",
                            method: "POST",
                            data:datos,
                            cache:false,
                            contentType: false,
                            processData: false,
                            success:function(respuesta){
                                console.log(respuesta);
                                document.activeElement.blur();
                                $("#miModal").modal('hide');
                                $('#empleados').DataTable().ajax.reload();
                                $("#nombre").val(""),
                                $("#apellido").val(""),
                                $("#dni").val("");
                                $("#puesto").val("");
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