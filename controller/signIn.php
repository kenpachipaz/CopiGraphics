<?php 
	session_start();
	require_once('db_connect.php');
	$Connection= new DBConnect();

	if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['usuario'])){
		if($Connection->validateLogin($_POST['email'], $_POST['password'])){
			if($_POST['usuario']=="venta"){
				$_SESSION['admin']="VENTA";
				header("Location:../index2.php");
			}else{
				$_SESSION['admin']="ADMIN";
				header("Location:../panel.php");
			}
		}else{
			header("Location:../index.php?validate=no");
		}
	}
	if(isset($_GET['logout'])){
		session_destroy();
		header("Location:../index.php");
	}

?>