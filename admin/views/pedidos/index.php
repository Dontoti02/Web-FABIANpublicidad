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
                <a class="nav-link text-white active" href="<?php echo BASE_URL; ?>admin/pedidos">
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
                <p class="text-muted mb-0">Gestiona los pedidos de la tienda</p>
            </div>
            <div>
                <button class="btn btn-danger me-2" id="eliminarSeleccionados" style="display: none;">
                    <i class="fas fa-trash me-2"></i>Eliminar Seleccionados
                </button>
                <button class="btn btn-success" onclick="location.reload()">
                    <i class="fas fa-sync-alt me-2"></i>Actualizar
                </button>
            </div>
        </div>

        <!-- Estadísticas de Pedidos -->
        <?php if (!empty($estadisticas)): ?>
        <div class="row mb-4">
            <?php foreach ($estadisticas as $stat): ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="mb-1"><?php echo $stat['cantidad']; ?></h3>
                            <p class="text-muted mb-1"><?php echo ucfirst($stat['estado']); ?></p>
                            <small class="text-muted">S/ <?php echo number_format($stat['total_monto'], 2); ?></small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>Listado de Pedidos
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
                                        <th>ID Pedido</th>
                                        <th>Cliente</th>
                                        <th>Total</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Método Pago</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="pedidosTable">
                                    <!-- Los pedidos se cargarán aquí dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detalle Pedido -->
    <div class="modal fade" id="detalleModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-receipt me-2"></i>Detalle del Pedido <span id="pedidoId"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Información del Cliente -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-user me-2"></i>Información del Cliente</h6>
                                </div>
                                <div class="card-body">
                                    <div id="clienteInfo">
                                        <!-- Se cargará dinámicamente -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Información del Pedido -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Información del Pedido</h6>
                                </div>
                                <div class="card-body">
                                    <div id="pedidoInfo">
                                        <!-- Se cargará dinámicamente -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Productos del Pedido -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><i class="fas fa-box me-2"></i>Productos del Pedido</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Producto</th>
                                                    <th>Categoría</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio Unit.</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody id="productosDetalle">
                                                <!-- Se cargará dinámicamente -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="imprimirPedido()">
                        <i class="fas fa-print me-2"></i>Imprimir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            listarPedidos();
        });

        function listarPedidos() {
            $.ajax({
                url: '<?php echo BASE_URL; ?>admin/pedidos/listar',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    let html = '';
                    if (data && data.length > 0) {
                        data.forEach(pedido => {
                            let badgeClass = 'secondary';
                            switch(pedido.estado) {
                                case 'completada': badgeClass = 'success'; break;
                                case 'pendiente': badgeClass = 'warning'; break;
                                case 'cancelada': badgeClass = 'danger'; break;
                            }
                            
                            html += `
                                <tr>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input pedido-checkbox" type="checkbox" value="${pedido.id_venta}" id="pedido_${pedido.id_venta}">
                                        </div>
                                    </td>
                                    <td><strong>#${pedido.id_venta}</strong></td>
                                    <td>
                                        ${pedido.cliente || 'Cliente N/A'}
                                        ${pedido.cliente_email ? '<br><small class="text-muted">' + pedido.cliente_email + '</small>' : ''}
                                    </td>
                                    <td><strong>S/ ${parseFloat(pedido.total).toFixed(2)}</strong></td>
                                    <td>${new Date(pedido.fecha).toLocaleDateString('es-ES')} ${new Date(pedido.fecha).toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'})}</td>
                                    <td>
                                        <span class="badge bg-${badgeClass}">
                                            ${pedido.estado.charAt(0).toUpperCase() + pedido.estado.slice(1)}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            ${pedido.metodo_pago ? pedido.metodo_pago.charAt(0).toUpperCase() + pedido.metodo_pago.slice(1) : 'N/A'}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary me-1" onclick="verDetalle(${pedido.id_venta})">
                                            <i class="fas fa-eye"></i> Ver
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="eliminarPedido(${pedido.id_venta})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <div class="btn-group mt-1" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                Estado
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="cambiarEstado(${pedido.id_venta}, 'pendiente')">Pendiente</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="cambiarEstado(${pedido.id_venta}, 'completada')">Completada</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="cambiarEstado(${pedido.id_venta}, 'cancelada')">Cancelada</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        });
                    } else {
                        html = `
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No hay pedidos registrados</p>
                                </td>
                            </tr>
                        `;
                    }
                    $('#pedidosTable').html(html);
                    
                    // Reinicializar eventos después de cargar la tabla
                    initializeCheckboxEvents();
                },
                error: function() {
                    $('#pedidosTable').html(`
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                                <p class="text-danger">Error al cargar los pedidos</p>
                            </td>
                        </tr>
                    `);
                }
            });
        }

        function initializeCheckboxEvents() {
            // Evento para seleccionar/deseleccionar todos
            $('#selectAll').off('change').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('.pedido-checkbox').prop('checked', isChecked);
                toggleEliminarButton();
            });

            // Evento para checkboxes individuales
            $(document).off('change', '.pedido-checkbox').on('change', '.pedido-checkbox', function() {
                const totalCheckboxes = $('.pedido-checkbox').length;
                const checkedCheckboxes = $('.pedido-checkbox:checked').length;
                
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
            const checkedCheckboxes = $('.pedido-checkbox:checked').length;
            if (checkedCheckboxes > 0) {
                $('#eliminarSeleccionados').show();
                $('#eliminarSeleccionados').html(`<i class="fas fa-trash me-2"></i>Eliminar Seleccionados (${checkedCheckboxes})`);
            } else {
                $('#eliminarSeleccionados').hide();
            }
        }

        function eliminarSeleccionados() {
            const selectedIds = [];
            $('.pedido-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                showAlert('No hay pedidos seleccionados', 'warning');
                return;
            }

            const confirmMessage = selectedIds.length === 1 
                ? '¿Estás seguro de eliminar este pedido? Esta acción eliminará también todos los productos asociados.' 
                : `¿Estás seguro de eliminar ${selectedIds.length} pedidos? Esta acción eliminará también todos los productos asociados.`;

            if (confirm(confirmMessage)) {
                $.ajax({
                    url: '<?php echo BASE_URL; ?>admin/pedidos/eliminarMultiple',
                    type: 'POST',
                    data: {
                        ids: selectedIds.join(',')
                    },
                    dataType: 'json',
                    success: function(response) {
                        showAlert(response.msg, response.type);
                        if (response.type === 'success' || response.type === 'warning') {
                            listarPedidos();
                            $('#selectAll').prop('checked', false);
                            $('#eliminarSeleccionados').hide();
                            // Recargar estadísticas
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        }
                    },
                    error: function() {
                        showAlert('Error de conexión al eliminar pedidos', 'danger');
                    }
                });
            }
        }

        function eliminarPedido(id) {
            if (confirm('¿Estás seguro de eliminar este pedido? Esta acción eliminará también todos los productos asociados.')) {
                $.ajax({
                    url: '<?php echo BASE_URL; ?>admin/pedidos/eliminar/' + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        showAlert(response.msg, response.type);
                        if (response.type === 'success') {
                            listarPedidos();
                            // Recargar estadísticas
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        }
                    },
                    error: function() {
                        showAlert('Error al eliminar el pedido', 'danger');
                    }
                });
            }
        }

        function verDetalle(id) {
            $.ajax({
                url: '<?php echo BASE_URL; ?>admin/pedidos/detalle/' + id,
                type: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    $('#pedidoId').text('#' + id);
                    $('#clienteInfo').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>');
                    $('#pedidoInfo').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>');
                    $('#productosDetalle').html('<tr><td colspan="5" class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando...</td></tr>');
                },
                success: function(response) {
                    if (response.error) {
                        showAlert(response.error, 'danger');
                        return;
                    }

                    const pedido = response.pedido;
                    const detalle = response.detalle;

                    // Información del cliente
                    let clienteHtml = `
                        <p><strong>Nombre:</strong> ${pedido.cliente || 'N/A'}</p>
                        <p><strong>Email:</strong> ${pedido.cliente_email || 'N/A'}</p>
                        <p><strong>Teléfono:</strong> ${pedido.cliente_telefono || 'N/A'}</p>
                        <p><strong>Dirección:</strong> ${pedido.cliente_direccion || 'N/A'}</p>
                    `;
                    $('#clienteInfo').html(clienteHtml);

                    // Información del pedido
                    let estadoBadge = '';
                    switch(pedido.estado) {
                        case 'completada': estadoBadge = '<span class="badge bg-success">Completada</span>'; break;
                        case 'pendiente': estadoBadge = '<span class="badge bg-warning">Pendiente</span>'; break;
                        case 'cancelada': estadoBadge = '<span class="badge bg-danger">Cancelada</span>'; break;
                        default: estadoBadge = '<span class="badge bg-secondary">Desconocido</span>';
                    }

                    let pedidoHtml = `
                        <p><strong>Fecha:</strong> ${new Date(pedido.fecha).toLocaleString('es-ES')}</p>
                        <p><strong>Estado:</strong> ${estadoBadge}</p>
                        <p><strong>Método de Pago:</strong> <span class="badge bg-info">${pedido.metodo_pago || 'N/A'}</span></p>
                        <p><strong>Total:</strong> <span class="h5 text-success">S/ ${parseFloat(pedido.total).toFixed(2)}</span></p>
                    `;
                    $('#pedidoInfo').html(pedidoHtml);

                    // Productos del pedido
                    let productosHtml = '';
                    let totalGeneral = 0;
                    
                    if (detalle && detalle.length > 0) {
                        detalle.forEach(item => {
                            totalGeneral += parseFloat(item.subtotal);
                            productosHtml += `
                                <tr>
                                    <td>
                                        <strong>${item.producto}</strong>
                                        ${item.producto_descripcion ? '<br><small class="text-muted">' + item.producto_descripcion + '</small>' : ''}
                                    </td>
                                    <td>${item.categoria || 'N/A'}</td>
                                    <td><span class="badge bg-primary">${item.cantidad}</span></td>
                                    <td>S/ ${parseFloat(item.precio_unitario).toFixed(2)}</td>
                                    <td><strong>S/ ${parseFloat(item.subtotal).toFixed(2)}</strong></td>
                                </tr>
                            `;
                        });
                        
                        productosHtml += `
                            <tr class="table-success">
                                <td colspan="4" class="text-end"><strong>TOTAL:</strong></td>
                                <td><strong>S/ ${totalGeneral.toFixed(2)}</strong></td>
                            </tr>
                        `;
                    } else {
                        productosHtml = '<tr><td colspan="5" class="text-center text-muted">No hay productos en este pedido</td></tr>';
                    }
                    
                    $('#productosDetalle').html(productosHtml);
                    $('#detalleModal').modal('show');
                },
                error: function() {
                    showAlert('Error al cargar el detalle del pedido', 'danger');
                }
            });
        }

        function cambiarEstado(id, estado) {
            if (confirm(`¿Estás seguro de cambiar el estado del pedido #${id} a "${estado}"?`)) {
                $.ajax({
                    url: '<?php echo BASE_URL; ?>admin/pedidos/actualizarEstado',
                    type: 'POST',
                    data: {
                        id: id,
                        estado: estado
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.type === 'success') {
                            showAlert(response.msg, 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 1500);
                        } else {
                            showAlert(response.msg, 'danger');
                        }
                    },
                    error: function() {
                        showAlert('Error al actualizar el estado', 'danger');
                    }
                });
            }
        }

        function imprimirPedido() {
            window.print();
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