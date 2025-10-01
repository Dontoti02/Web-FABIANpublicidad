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
                <a class="nav-link text-white active" href="<?php echo BASE_URL; ?>admin/categorias">
                    <i class="fas fa-th-large me-2"></i>Categorías
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?php echo BASE_URL; ?>admin/productos">
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
                <p class="text-muted mb-0">Gestiona las categorías de productos</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoriaModal">
                <i class="fas fa-plus me-2"></i>Nueva Categoría
            </button>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Listado de Categorías
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Imagen</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="categoriasTable">
                                    <!-- Las categorías se cargarán aquí dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nueva/Editar Categoría -->
    <div class="modal fade" id="categoriaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">
                        <i class="fas fa-plus me-2"></i>Nueva Categoría
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="categoriaForm">
                    <input type="hidden" id="categoriaId" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre de la Categoría</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="btnGuardar">
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
        let editMode = false;

        $(document).ready(function() {
            listarCategorias();

            // Resetear modal al abrirlo para nueva categoría
            $('#categoriaModal').on('show.bs.modal', function() {
                if (!editMode) {
                    $('#modalTitle').html('<i class="fas fa-plus me-2"></i>Nueva Categoría');
                    $('#btnGuardar').html('<i class="fas fa-save me-2"></i>Guardar');
                    $('#categoriaForm')[0].reset();
                    $('#categoriaId').val('');
                }
            });

            $('#categoriaForm').on('submit', function(e) {
                e.preventDefault();
                
                const url = editMode ? 
                    '<?php echo BASE_URL; ?>admin/categorias/actualizar' : 
                    '<?php echo BASE_URL; ?>admin/categorias/registrar';
                
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.type === 'success') {
                            $('#categoriaModal').modal('hide');
                            $('#categoriaForm')[0].reset();
                            listarCategorias();
                            showAlert(response.msg, 'success');
                            editMode = false;
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

        function listarCategorias() {
            $.ajax({
                url: '<?php echo BASE_URL; ?>admin/categorias/listar',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    let html = '';
                    data.forEach(categoria => {
                        const estado = categoria.estado == 1 ? 
                            '<span class="badge bg-success">Activo</span>' : 
                            '<span class="badge bg-danger">Inactivo</span>';
                        
                        html += `
                            <tr>
                                <td>${categoria.id_categoria}</td>
                                <td>${categoria.nombre}</td>
                                <td>${categoria.imagen || 'Sin imagen'}</td>
                                <td>${estado}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary me-1" onclick="editarCategoria(${categoria.id_categoria})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="eliminarCategoria(${categoria.id_categoria})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    $('#categoriasTable').html(html);
                }
            });
        }

        function editarCategoria(id) {
            editMode = true;
            
            $.ajax({
                url: `<?php echo BASE_URL; ?>admin/categorias/editar/${id}`,
                type: 'GET',
                dataType: 'json',
                success: function(categoria) {
                    $('#categoriaId').val(categoria.id_categoria);
                    $('#nombre').val(categoria.nombre);
                    $('#modalTitle').html('<i class="fas fa-edit me-2"></i>Editar Categoría');
                    $('#btnGuardar').html('<i class="fas fa-save me-2"></i>Actualizar');
                    $('#categoriaModal').modal('show');
                },
                error: function() {
                    showAlert('Error al cargar los datos de la categoría', 'danger');
                }
            });
        }

        function eliminarCategoria(id) {
            if (confirm('¿Estás seguro de eliminar esta categoría?')) {
                $.ajax({
                    url: `<?php echo BASE_URL; ?>admin/categorias/eliminar/${id}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.type === 'success') {
                            listarCategorias();
                            showAlert(response.msg, 'success');
                        } else {
                            showAlert(response.msg, 'danger');
                        }
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