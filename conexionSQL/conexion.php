<?php
$conexion = mysqli_connect("localhost", "root", "" );
$conexion->set_charset("utf8mb4");                            
$db = mysqli_select_db( $conexion, "fabian_db" );

?>