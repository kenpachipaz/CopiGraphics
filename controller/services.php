<!DOCTYPE html>
<?php 
	session_start();
    if(!isset($_SESSION['admin'])){
	    header("Location:index.php?validate=no");
	    exit;
	}else if($_SESSION['admin']!="ADMIN"){
	    header("Location:index2.php");
	    exit;
	 }
	 /*Enviar email*/
	 if(isset($_POST['email'])){
	 	$to=$_POST['email'];
	 	//$to="shinigami_paz@hotmai.com";
	 	$headers = "MIME-Version: 1.0\r\n"; 
		$headers .= "Content-type: text/html; charset=UTF-8\r\n"; 
		$headers .= "From: Copi-Graphics <copigraphics@gmail.com>\r\n"; 
		$headers .= "Cc: fannyrocks_07@hotmail.com\r\n"; 

		$subject="Copi-Graphics: Conclusión de pedido";

		$message="¡Saludos!<br>";
		$message.="Este correo es para notificarle que su pedido a concluído conn éxito.<br>";
		$message.="Lo esperamos en nuestras instalaciones para hacer entrega de este.<br>";
		$message.="Sin más por agregar me despido de usted.<br>";
		$message.="Gracias por su preferncia. Copi-Graphics.<br><br><br>";
		$message.="<b>ESTE CORREO ES AUTOMÁTICO, POR LO QUE NO ES NECESARIO UNA RESPUESTA<b>";

		if(mail($to, $subject, $message, $headers)) echo true;
		else false;

		exit;
	 }
	require_once('db_connect.php');
	$Connection= new DBConnect();

	 /*Enviar respuesta si se ha completad o no la venta*/
	if(isset($_POST['id']) && isset($_POST['date'])){
	 	if($Connection->completeSale($_POST['id'], $_POST['date'])) echo true;
	 	else echo false;

	 	exit;
	 }
?>
<html>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" type="text/css" href="../css/myStyle.css">
		<script type="text/javascript" src="../js/jquery.js"></script>
		<script type="text/javascript">
		/*Funciones para el cambio de la img*/
			function hover(element, value) {
				switch(value){
					case 0:
						element.setAttribute('src', '../img/completeHover.png');
					break;
					case 1:
						element.setAttribute('src', '../img/emailHover.png');
					break;
					case 2:
						element.setAttribute('src', '../img/seeSaleHover.png');
					break;
				}
			}
			function unhover(element, value) {
			    switch(value){
					case 0:
						element.setAttribute('src', '../img/completeOut.png');
					break;
					case 1:
						element.setAttribute('src', '../img/emailOut.png');
					break;
					case 2:
						element.setAttribute('src', '../img/seeSaleOut.png');
					break;
				}
			}
			/*Función para enviar email AJAX*/
			function sendEmail(email){
				 var parametros = {
                     "email" : email
                 };
                    $.ajax({
                       data:  parametros,
                       url:   'services.php',
                       type:  'post',
                       success:  function (response) {
                      		if(response)
                      			alert("Correo de notificación enviado.");
                      		else
                      			alert("El correo no pudo ser enviado,");
                       }
                 });
			}
			/*Función para completar la venta*/
			function completeSale(id, date){
				 var parametros = {
                     "id" : id,
                     "date" : date
                 };
                    $.ajax({
                       data:  parametros,
                       url:   'services.php',
                       type:  'post',
                       success:  function (response) {
                      		if(response){
                      			alert("La venta ha sido completada.");
                      			window.location.reload()
                      		}else{
                      			alert("No se pudo completar la venta.");
                      		}                      			
                       }
                 });
			}
			/*Función que envía los parámetros para visualizar la venta del cliente*/
			function seeCustomerSale(id, date, name){
                 window.location.href="see_customer_sale.php?id="+id+"&date="+date+"&name="+name;
			}
		</script>
		<style type="text/css">
			.titles{
				background: #4DB6AC;
				text-align: center;
				color: white;
			}
			table{
				border-radius: 10px;
				box-shadow: 5px -5px 7px 7px #78909C;
			}
			tr, th, td{
				border-radius: 5px;
				padding: 5px 10px 5px 10px;
			}
			#uno{
				color: #4456AD;
			}
			img{

			}
		</style>
	</head>
	<body>
		<h3 id="uno">Servcios pendientes y por cobrar</h3>
		<table border="1">	
			<tr class="titles">
				<th>ID</th>
				<th>Nombre</th>
				<th>Correo</th>
				<th>Fecha</th>
				<th>Adeudo</th>
				<th>Estado</th>
				<th>Atendio</th>
				<th>Opciones</th>
			</tr>
		<?php 
			$A=$Connection->getRequestedService();
			for($i=0; $i < count($A); $i++){
				$token=explode('%', $A[$i]);
				switch($token[5]){
			   		case 1:
			   			$estado="Pendiente";
			   			$style="background: #FFF176;";
			   			break;
			   		case 2:
			   			$estado="Por cobrar";
			   			$style="background: #E57373;";
			   			break;
			   	}
		?>
				<tr style="<?php echo $style;?>">	
					<td><?php echo $token[0]; ?></td>
					<td><?php echo $token[1]; ?></td>
					<td><?php echo $token[2]; ?></td>
					<td><?php echo $token[3]; ?></td>
					<td><?php echo $token[4]; ?></td>
					<td><?php echo $estado; ?></td>
					<td><?php echo $token[6]; ?></td>
					<td>
						<img src="../img/completeOut.png" onmouseover="hover(this, 0);" onmouseout="unhover(this, 0);" onclick="completeSale('<?php echo $token[0]; ?>','<?php echo $token[3]; ?>');" title="Completar trabajo">
			 			<img src="../img/emailOut.png" onmouseover="hover(this, 1);" onmouseout="unhover(this, 1);" onclick="sendEmail('<?php echo $token[2]; ?>');" title="Enviar notificación">
						<a href="#"><img src="../img/seeSaleOut.png" onmouseover="hover(this, 2);" onmouseout="unhover(this, 2);" onclick="seeCustomerSale('<?php echo $token[0]; ?>', '<?php echo $token[3]; ?>', '<?php echo $token[1]; ?>');" title="Ver venta"></a>		  				
					</td>
				</tr>	
		<?php }
		?>
		</table>
	</body>
</html>