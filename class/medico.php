<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['Nombre'])) {
    // Si no está autenticado, redirigir al formulario de inicio de sesión
    header("Location: ../login/recursos/login1.php");
    exit();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
	<title>Datos Médico</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<style>
	body {
		width: 100%;
		float: left;
		background-image: url(../imagenes/fondo.jpg);
		background-repeat: no-repeat;
		background-size: cover;
		padding-top: 100px;
	}
</style>
<body>
	
	<?php
		require_once("constantes.php");
		include_once("class.medico.php");
		
		$cn = conectar();
		$v = new medico($cn);
		
		if(isset($_GET['d'])){
			$dato = base64_decode($_GET['d']);
		//	echo $dato;exit;
			$tmp = explode("/", $dato);
			$op = $tmp[0];
			$id = $tmp[1];
			
			if($op == "del"){
				echo $v->delete_medico($id);
			}elseif($op == "det"){
				echo $v->get_detail_medico($id);
			}elseif($op == "new"){
				echo $v->get_form();
			}elseif($op == "act"){
				echo $v->get_form($id);
			}
			
       // PARTE III	
		}else{
			   
		
		      
			if(isset($_POST['Guardar']) && $_POST['op']=="new"){
				$v->save_medico();
			}elseif(isset($_POST['Guardar']) && $_POST['op']=="update"){
				$v->update_medico();
			}else{
				echo $v->get_list();
			}	
		}
		
	//*******************
		function conectar(){
			//echo "<br> CONEXION A LA BASE DE DATOS<br>";
			$c = new mysqli(SERVER,USER,PASS,BD);
			
			if($c->connect_errno) {
				die("Error de conexión: " . $c->mysqli_connect_errno() . ", " . $c->connect_error());
			}else{
				//echo "La conexión tuvo éxito .......<br><br>";
			}
			
			$c->set_charset("utf8");
			return $c;
		}
	//********************	

		
	?>	
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>