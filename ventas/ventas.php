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
    <title>Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>
<body>
    <?php include '../templates/layout.html' ?>
    <h1 class="mt-4">Crear Venta</h1>
    <div>
        <div class="mb-3">
            <label for="empleado">Vendedor</label>
            <select name="empleado" id="empleado" class="form-control" required></select>
        </div>
        <div class="mb-3">
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="fecha" placeholder="Ingrese una fecha" required>
        </div>
        <div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#miModal">Agregar Productos</button>
        </div>
    </div>
    <!-- MODAL -->
    <div class="modal fade" id="miModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar Productos</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            <div class="modal-body">
                <div>
                    <table id="productos" class="display" style="width:100%">
                        <thead>
                            <th>Codigo</th>
                            <th>Descripcion</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Opciones</th>
                        </thead>
                    </table>
                </div>
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <div>
        <table id="ventas" class="display" style="width:100%">
            <thead>
                <th>Articulo</th>
                <th>Cantidad</th>
                <th>Precio Venta</th>
                <th>Subtotal</th>
                <th>Opciones</th>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <div>
        <button type="button" id="guardar" class="btn btn-primary">Guardar</button>
        <button type="button" class="btn btn-secondary">Cerrar</button>
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
        $(document).ready(function (){
            $.ajax({
                url: '../ajax/vendedores.ajax.php',
                method: 'GET',
                dataType: 'json',
                success: function(data){
                    const select = $('#empleado');
                    select.append('<option value="">Seleccione un vendedor</option>');
                    data.forEach(function (empleado) {
                        select.append(`<option value="${empleado.id_empleado}">${empleado.nombre} ${empleado.apellido} </option>`);
                    });
                }
            })

            var tablaproductos = $('#productos').DataTable({
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
                    { data: 'cantidad' },
                    { data: 'precio',
                      render:function(data,type,row){
                        return parseInt(data).toLocaleString('es-CL');
                      }
                    },
                    {
                        data:null,
                        render:function(data,type,row){
                            return `<button class="btn btn-principal btnagregar">
                            <i class="fa-solid fa-plus"></i>                            
                            </button>`
                        }
                    }
                ]
            })

            let tablaventas = $('#ventas').DataTable({
                ordering: false,
                info: false,
                searching: false,
                columns:[
                    { data: 'nombre' },
                    {
                        data: 'cantidad',
                        render: function(data, type, row){
                            return `<input type="number" class="form-control cantidad-input" value="${data}" min="1" style="width:80px;">`;
                        }
                    },

                    {
                        data: 'precio',
                        render: function(data){
                            return parseInt(data).toLocaleString('es-CL');
                        }
                    },
                    {
                        data: 'subtotal',
                        render: function(data){
                            return parseInt(data).toLocaleString('es-CL');
                        }
                    },
                    {
                        data:null,
                        render:function(data,type,row){
                            return `<button class="btn btn-danger btneliminar">
                            <i class="fa-solid fa-trash"></i>                            
                            </button>`
                        }
                    }
                ]
            });

            $('#productos tbody').on('click','.btnagregar',function(){
                //Obtener datos de la fila cliqueada
                let producto = tablaproductos.row($(this).parents('tr')).data();
                let encontrado = false;
                //Buscar si el producto ya existe
                tablaventas.rows().every(function(){
                    let rowData = this.data();
                    if(rowData.nombre === producto.nombre){
                        rowData.cantidad++;
                        rowData.subtotal = rowData.cantidad * rowData.precio;
                        this.data(rowData).draw(false);
                        encontrado = true;
                    }
                })

                //Si no existe lo agregamos
                if(!encontrado){
                    tablaventas.row.add({
                        nombre: producto.nombre,
                        cantidad: 1,
                        precio: producto.precio,
                        subtotal: producto.precio
                    }).draw();
                }
            })

            $('#ventas tbody').on('click','.btneliminar',function(){
                tablaventas.row($(this).parents('tr')).remove().draw();
            })

            $('#ventas tbody').on('input','.cantidad-input',function(){
                let fila = $(this).closest('tr');
                let rowData = tablaventas.row(fila).data();

                let nuevaCantidad = parseInt($(this).val()) || 1;
                rowData.cantidad = nuevaCantidad;
                rowData.subtotal = rowData.precio * nuevaCantidad;

                tablaventas.row(fila).data(rowData).draw(false);
            })


        })
    </script>
</body>
</html>