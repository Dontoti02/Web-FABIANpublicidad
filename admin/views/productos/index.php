<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>admin/assets/admin-style.css" rel="stylesheet">
</head>
<body>
    <div class="sidebar p-3">
        <!-- Logo Section -->
        <div class="text-center mb-4">
            <img src="<?php echo BASE_URL; ?>assets/logo.png" alt="FABIAN Logo" class="logo-admin mb-2">
            <h6 class="text-white mb-0 fw-bold">FABIAN</h6>
            <small class="text-white-50">Publicidad</small>
        </div>
        
        <hr class="text-white-50">
        
        <div class="d-flex align-items-center mb-4">
            <i class="fas fa-user-shield text-white me-3" style="font-size: 1.5rem;"></i>
            <div>
                <h6 class="text-white mb-0"><?php echo $_SESSION['nombre']; ?></h6>
                <small class="text-white-50"><?php echo ucfirst($_SESSION['tipo']); ?></small>
            </div>
        </div>
        
        <hr class="text-white-50">
        
        <h5 class="text-white mb-3">
            <i class="fas fa-cogs me-2"></i>Panel de Control
        </h5>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white" href="<?php echo BASE_URL; ?>admin">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?php echo BASE_URL; ?>admin/categorias">
                    <i class="fas fa-th-large me-2"></i>Categorías
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white active" href="<?php echo BASE_URL; ?>admin/productos">
                    <i class="fas fa-box me-2"></i>Productos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?php echo BASE_URL; ?>admin/usuarios">
                    <i class="fas fa-users me-2"></i>Usuarios
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?php echo BASE_URL; ?>admin/pedidos">
                    <i class="fas fa-shopping-cart me-2"></i>Pedidos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo (strpos($_SERVER['REQUEST_URI'], 'configuracion') !== false) ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/configuracion">
                    <i class="fas fa-cog me-2"></i>Configuración
                </a>
            </li>
        </ul>
        
        <div class="position-absolute bottom-0 start-0 w-100 p-3">
            <hr class="text-white-50">
            <a href="<?php echo BASE_URL; ?>admin/login/logout" class="btn btn-outline-light w-100">
                <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
            </a>
        </div>
    </div>

    <div class="content p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="mb-0"><?php echo $title; ?></h1>
                <p class="text-muted mb-0">Gestiona los productos de la tienda</p>
            </div>
            <div>
                <button class="btn btn-danger me-2" id="eliminarSeleccionados" style="display: none;">
                    <i class="fas fa-trash me-2"></i>Eliminar Seleccionados
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productoModal">
                    <i class="fas fa-plus me-2"></i>Nuevo Producto
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Listado de Productos
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAll">
                                                <label class="form-check-label" for="selectAll">
                                                    <small>Todo</small>
                                                </label>
                                            </div>
                                        </th>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Precio</th>
                                        <th>Stock</th>
                                        <th>Categoría</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="productosTable">
                                    <!-- Los productos se cargarán aquí dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nuevo Producto -->
    <div class="modal fade" id="productoModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i>Nuevo Producto
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="productoForm" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre del Producto</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="precio" class="form-label">Precio</label>
                                    <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock" class="form-label">Stock</label>
                                    <input type="number" class="form-control" id="stock" name="stock" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="id_categoria" class="form-label">Categoría</label>
                                    <select class="form-control" id="id_categoria" name="id_categoria" required>
                                        <option value="">Seleccionar Categoría</option>
                                        <?php if(isset($categorias) && !empty($categorias)): ?>
                                            <?php foreach($categorias as $categoria): ?>
                                                <option value="<?php echo $categoria['id_categoria']; ?>">
                                                    <?php echo $categoria['nombre']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="imagen" class="form-label">Imagen del Producto</label>
                            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            listarProductos();

            // Reset modal when it's closed
            $('#productoModal').on('hidden.bs.modal', function() {
                $('#productoForm')[0].reset();
                $('#productoModal .modal-title').html('<i class="fas fa-plus me-2"></i>Nuevo Producto');
                $('#productoForm button[type="submit"]').html('<i class="fas fa-save me-2"></i>Guardar');
            });

            $('#productoForm').on('submit', function(e) {
                e.preventDefault();
                
                var formData = new FormData(this);
                var isEdit = $('#id').val() !== '';
                var url = isEdit ? '<?php echo BASE_URL; ?>admin/productos/actualizar' : '<?php echo BASE_URL; ?>admin/productos/registrar';
                
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.type === 'success') {
                            $('#productoModal').modal('hide');
                            $('#productoForm')[0].reset();
                            // Reset modal title and button for next use
                            $('#productoModal .modal-title').html('<i class="fas fa-plus me-2"></i>Nuevo Producto');
                            $('#productoForm button[type="submit"]').html('<i class="fas fa-save me-2"></i>Guardar');
                            listarProductos();
                            showAlert(response.msg, 'success');
                        } else {
                            showAlert(response.msg, 'danger');
                        }
                    },
                    error: function() {
                        showAlert('Error de conexión', 'danger');
                    }
                });
            });
        });

        function listarProductos() {
            $.ajax({
                url: '<?php echo BASE_URL; ?>admin/productos/listar',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    let html = '';
                    data.forEach(producto => {
                        const estado = producto.estado == 1 ? 
                            '<span class="badge bg-success">Activo</span>' : 
                            '<span class="badge bg-danger">Inactivo</span>';
                        
                        html += `
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input producto-checkbox" type="checkbox" value="${producto.id_producto}" id="producto_${producto.id_producto}">
                                    </div>
                                </td>
                                <td>${producto.id_producto}</td>
                                <td>${producto.nombre}</td>
                                <td>${producto.descripcion || 'Sin descripción'}</td>
                                <td>S/ ${parseFloat(producto.precio).toFixed(2)}</td>
                                <td>${producto.stock}</td>
                                <td>${producto.categoria || 'Sin categoría'}</td>
                                <td>${estado}</td>
                                <td>
                                    <button class="btn btn-sm btn-warning me-1" onclick="editarProducto(${producto.id_producto})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="eliminarProducto(${producto.id_producto})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#productosTable').html(html);
                    
                    // Reinicializar eventos después de cargar la tabla
                    initializeCheckboxEvents();
                }
            });
        }

        function eliminarProducto(id) {
            if (confirm('¿Estás seguro de eliminar este producto?')) {
                $.ajax({
                    url: `<?php echo BASE_URL; ?>admin/productos/eliminar/${id}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.type === 'success') {
                            listarProductos();
                            showAlert(response.msg, 'success');
                        } else {
                            showAlert(response.msg, 'danger');
                        }
                    }
                });
            }
        }

        function editarProducto(id) {
            $.ajax({
                url: `<?php echo BASE_URL; ?>admin/productos/editar/${id}`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if (data) {
                        // Populate form with product data
                        $('#id').val(data.id_producto);
                        $('#nombre').val(data.nombre);
                        $('#descripcion').val(data.descripcion);
                        $('#precio').val(data.precio);
                        $('#stock').val(data.stock);
                        $('#id_categoria').val(data.id_categoria);
                        
                        // Change modal title and button
                        $('#productoModal .modal-title').html('<i class="fas fa-edit me-2"></i>Editar Producto');
                        $('#productoForm button[type="submit"]').html('<i class="fas fa-save me-2"></i>Actualizar');
                        
                        // Show modal
                        $('#productoModal').modal('show');
                    } else {
                        showAlert('Error al cargar los datos del producto', 'danger');
                    }
                },
                error: function() {
                    showAlert('Error de conexión', 'danger');
                }
            });
        }

        function initializeCheckboxEvents() {
            // Evento para seleccionar/deseleccionar todos
            $('#selectAll').off('change').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('.producto-checkbox').prop('checked', isChecked);
                toggleEliminarButton();
            });

            // Evento para checkboxes individuales
            $(document).off('change', '.producto-checkbox').on('change', '.producto-checkbox', function() {
                const totalCheckboxes = $('.producto-checkbox').length;
                const checkedCheckboxes = $('.producto-checkbox:checked').length;
                
                // Actualizar el estado del checkbox "Seleccionar todo"
                $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
                $('#selectAll').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
                
                toggleEliminarButton();
            });

            // Evento para el botón de eliminar seleccionados
            $('#eliminarSeleccionados').off('click').on('click', function() {
                eliminarSeleccionados();
            });
        }

        function toggleEliminarButton() {
            const checkedCheckboxes = $('.producto-checkbox:checked').length;
            if (checkedCheckboxes > 0) {
                $('#eliminarSeleccionados').show();
                $('#eliminarSeleccionados').html(`<i class="fas fa-trash me-2"></i>Eliminar Seleccionados (${checkedCheckboxes})`);
            } else {
                $('#eliminarSeleccionados').hide();
            }
        }

        function eliminarSeleccionados() {
            const selectedIds = [];
            $('.producto-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                showAlert('No hay productos seleccionados', 'warning');
                return;
            }

            const confirmMessage = selectedIds.length === 1 
                ? '¿Estás seguro de eliminar este producto?' 
                : `¿Estás seguro de eliminar ${selectedIds.length} productos?`;

            if (confirm(confirmMessage)) {
                $.ajax({
                    url: '<?php echo BASE_URL; ?>admin/productos/eliminarMultiple',
                    type: 'POST',
                    data: {
                        ids: selectedIds.join(',')
                    },
                    dataType: 'json',
                    success: function(response) {
                        showAlert(response.msg, response.type);
                        if (response.type === 'success' || response.type === 'warning') {
                            listarProductos();
                            $('#selectAll').prop('checked', false);
                            $('#eliminarSeleccionados').hide();
                        }
                    },
                    error: function() {
                        showAlert('Error de conexión al eliminar productos', 'danger');
                    }
                });
            }
        }

        function showAlert(message, type) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            $('.content').prepend(alertHtml);
            
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        }
    </script>
</body>
</html>