<!doctype html>
<?php 

	if(isset($_GET['validate'])){
		echo '<script>alert("Datos incorrectos. Usted no es administrador de este sistema.");</script>';
	}
?>
<html>
<head>
	<meta charset="utf-8">
	<title>Login</title>
	<link rel="stylesheet" href="css/style.css">
	<script src="js/jquery.js"></script>
	<script src="js/sha1-min.js"></script>
	<script>
			function encriptaSHA1(){
				var input_pass = document.getElementById("password");
            	input_pass.value = hex_sha1(input_pass.value);
			}
		</script>
</head>
<body>
	<div id="login">
		<h2><span class="fontawesome-lock"></span>CopyGraphics</h2>
		<form action="controller/signIn.php" method="POST">
			<fieldset>
				<p><label for="email">Usuario</label></p>
				<p><input type="text" name="email" id="email" placeholder="usuario" required></p> 

				<p><label for="password">Password</label></p>
				<p><input type="password" id="password" placeholder="password" name="password" required></p> 
				<!--<p><label for="email">Tipo de Usuario</label></p> -->
				<select id="usuario" name="usuario" class="form-control">
							<option value="venta">Sistema para ventas</option>
							<option value="admin">Administración del panel</option>
				</select>
				<br> <br>
				<p><input type="submit" onclick="encriptaSHA1()" value="Ingresar"></p>
			</fieldset>
		</form>
	</div> <!-- end login -->
</body>	
</html>