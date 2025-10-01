<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/bootstrap.min.css">
</head>
<body>
    <div class="d-flex">
        <div class="sidebar vh-100 p-3 bg-dark">
            <h4 class="text-white">Administración</h4>
            <hr class="text-white">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo BASE_URL; ?>admin/categorias">Categorías</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo BASE_URL; ?>admin/productos">Productos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo BASE_URL; ?>admin/usuarios">Usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo BASE_URL; ?>admin/pedidos">Pedidos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo BASE_URL; ?>admin/perfil">Perfil</a>
                </li>
            </ul>
        </div>
        <div class="content p-4">