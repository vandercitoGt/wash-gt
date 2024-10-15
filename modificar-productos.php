<?php
 session_start();
 if (!isset($_SESSION['usuario'])) {
     header("Location: index.html");
     exit();
 }
 $usuario = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : "Nombre de Usuario Predeterminado";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WASH-GT</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="style-panel-admin.css">
    <link rel="stylesheet" href="style-configuracion.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .hidden { display: none; }
        .form-container { margin: 20px 0; }
        .form-group { margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <aside>
            <header>
                <h1 class="logo">WASH-GT</h1>
            </header>
            <nav>
                <ul class="menu">
                    <li><button id="btnAgregar" class="boton-menu boton-categoria"><i class="bi bi-hand-index-thumb-fill"></i>Agregar</button></li>
                    <li><button id="btnEliminar" class="boton-menu"><i class="bi bi-x-octagon"></i>Eliminar</button></li>
                    <li><button id="btnModificar" class="boton-menu"><i class="bi bi-pencil-square"></i>Modificar</button></li>
                    <li>
                    <?php
                  
                    if(isset($_SESSION['usuario'])) {
                        $usuario = $_SESSION['usuario'];
                    } else {
                        $usuario = "Nombre de Usuario Predeterminado"; 
                    }
                    ?>
                    <button class="boton-menu boton-categoria">
                        <i class="bi bi-person-circle"></i>
                        <?php echo $usuario; ?>
                    </button>
                    </li>
                    <li><a class="boton-menu boton-categoria boton-cerrar" href="./logout.php"><i class="bi bi-x-octagon"></i>Cerrar sesión</a></li>
                    <li><a class="boton-menu" href="panel-admin.php"><i class="bi bi-arrow-return-left"></i>Regresar</a></li>
                </ul>
            </nav>
            <footer>
                <p class="texto-footer">© 2023 WASHGT</p>
            </footer>
        </aside>
        <main>
            <h2 class="titulo-principal">Modificar productos</h2>
            <div id="formAgregar" class="hidden">
                <h3>Agregar Producto</h3>
                <form id="agregarProductoForm" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="titulo" class="label-input">Título:</label>
                        <input class="input-configuracion" type="text" id="titulo" name="titulo" required>
                    </div>
                    <div class="form-group">
                        <label for="categoria" class="label-input">Categoría:</label>
                        <select class="input-configuracion" id="categoria" name="categoria" required>
                            <option value="Shampoos">Shampoos</option>
                            <option value="Microfibras">Microfibras</option>
                            <option value="Tratamientos">Tratamientos</option>
                            <!-- <option value="Otra">Otra</option> -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="precio" class="label-input">Precio:</label>
                        <input class="input-configuracion" type="number" id="precio" name="precio" required>
                    </div>
                    <div class="form-group">
                        <label for="imagen" class="label-input">Imagen:</label>
                        <input class="input-configuracion" type="file" id="imagen" name="imagen" accept=".jpg" required>
                    </div>
                    <button class="boton-agregar" type="submit">Agregar Producto</button>
                </form>
            </div>
            <div id="formEliminar" class="hidden">
                <h3>Eliminar Producto</h3>
                <form id="eliminarProductoForm">
                    <div class="form-group">
                        <label for="productos" class="label-input">Productos:</label>
                        <select class="input-configuracion" id="productos" name="productos" required></select>
                    </div>
                    <button class="boton-agregar" type="submit">Eliminar Producto</button>
                </form>
            </div>
            <div id="formModificar" class="hidden">
                <h3>Modificar Producto</h3>
                <form id="seleccionarProductoForm">
                    <div class="form-group">
                        <label for="productosModificar" class="label-input">Productos:</label>
                        <select class="input-configuracion" id="productosModificar" name="productosModificar"></select>
                    </div>
                    <button class="boton-agregar" type="submit">Seleccionar Producto</button>
                </form>
            </div>
            <div id="formModificarDetalle" class="hidden">
                <h3>Detalles del Producto</h3>
                <form id="modificarProductoForm" enctype="multipart/form-data">
                    <input class="input-configuracion" type="hidden" id="productoId" name="productoId">
                    <div class="form-group">
                        <label for="tituloModificar" class="label-input">Título:</label>
                        <input class="input-configuracion" type="text" id="tituloModificar" name="tituloModificar" required>
                    </div>
                    <div class="form-group">
                        <label for="categoriaModificar" class="label-input">Categoría:</label>
                        <select class="input-configuracion" id="categoriaModificar" name="categoriaModificar" required>
                            <option value="Shampoos">Shampoos</option>
                            <option value="Microfibras">Microfibras</option>
                            <option value="Tratamientos">Tratamientos</option>
                            <option value="Otra">Otra</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="precioModificar" class="label-input">Precio:</label>
                        <input class="input-configuracion" type="number" id="precioModificar" name="precioModificar" required>
                    </div>
                    <div class="form-group">
                        <label for="imagenModificar" class="label-input">Imagen:</label>
                        <input class="input-configuracion" type="file" id="imagenModificar" name="imagenModificar" accept=".jpg">
                    </div>
                    <button class="boton-agregar" type="submit">Modificar Producto</button>
                </form>
            </div>
        </main>
    </div>
    <script>
        document.getElementById('btnAgregar').addEventListener('click', function() {
            document.getElementById('formAgregar').classList.remove('hidden');
            document.getElementById('formEliminar').classList.add('hidden');
            document.getElementById('formModificar').classList.add('hidden');
            document.getElementById('formModificarDetalle').classList.add('hidden');
        });

        document.getElementById('btnEliminar').addEventListener('click', function() {
            document.getElementById('formAgregar').classList.add('hidden');
            document.getElementById('formEliminar').classList.remove('hidden');
            document.getElementById('formModificar').classList.add('hidden');
            document.getElementById('formModificarDetalle').classList.add('hidden');
            cargarProductos('productos');
        });

        document.getElementById('btnModificar').addEventListener('click', function() {
            document.getElementById('formAgregar').classList.add('hidden');
            document.getElementById('formEliminar').classList.add('hidden');
            document.getElementById('formModificar').classList.remove('hidden');
            document.getElementById('formModificarDetalle').classList.add('hidden');
            cargarProductos('productosModificar');
        });

        document.getElementById('seleccionarProductoForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const productoId = document.getElementById('productosModificar').value;
            if (productoId) {
                cargarProducto(productoId);
                document.getElementById('formModificarDetalle').classList.remove('hidden');
            } else {
                alert('Por favor, seleccione un producto para modificar.');
            }
        });

        document.getElementById('agregarProductoForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);

            const imagen = document.getElementById('imagen').files[0];
            if (imagen && imagen.type === 'image/jpeg') {
                formData.append('imagen', imagen);

                fetch('agregar-producto-json.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Producto agregado con éxito');
                        document.getElementById('agregarProductoForm').reset();
                        cargarProductos('productos');
                        cargarProductos('productosModificar'); // Actualiza el select de modificar
                        cargarProductos('productos'); // Actualiza el select de eliminar
                    } else {
                        alert('Error al agregar el producto: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            } else {
                alert('Por favor, selecciona una imagen en formato JPG.');
            }
        });
        document.getElementById('eliminarProductoForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const productoId = document.getElementById('productos').value;

    if (productoId) {
        fetch('eliminar-producto-json.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: productoId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Producto eliminado con éxito');
                cargarProductos('productos');
                cargarProductos('productosModificar');
            } else {
                alert('Error al eliminar el producto: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    } else {
        alert('Por favor, selecciona un producto para eliminar.');
    }
});

document.getElementById('modificarProductoForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const formData = new FormData(this);

    fetch('modificar-producto-json.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Producto modificado con éxito');
            cargarProductos('productosModificar');
            cargarProductos('productos');
        } else {
            alert('Error al modificar el producto: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
});


        function cargarProductos(selectId) {
            fetch('./shop/productos.json')
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById(selectId);
                    select.innerHTML = '';
                    data.forEach(producto => {
                        const option = document.createElement('option');
                        option.value = producto.id;
                        option.textContent = producto.titulo;
                        select.appendChild(option);
                    });
                })
                .catch(error => console.error('Error al cargar los productos:', error));
        }

        function cargarProducto(productoId) {
            fetch('./shop/productos.json')
                .then(response => response.json())
                .then(data => {
                    const producto = data.find(item => item.id === productoId);
                    if (producto) {
                        document.getElementById('productoId').value = productoId;
                        document.getElementById('tituloModificar').value = producto.titulo;
                        document.getElementById('categoriaModificar').value = producto.categoria.nombre;
                        document.getElementById('precioModificar').value = producto.precio;
                    }
                })
                .catch(error => console.error('Error al cargar el producto:', error));
        }
    </script>
    <script src="menu.js"></script>
</body>
</html>
