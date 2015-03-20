<!DOCTYPE html>
<?php 
	require_once('db_connect.php');
	$Connection= new DBConnect();
?>
<html>
	<head>
		<meta charset="UTF-8">
		<style type="text/css">
			#headCon{
				background: #4DB6AC;
				margin: 0 auto;
				width: 50%;
				color: white;
				border-radius: 10px;
				padding: 10px 10px;
			}
			#tableSale{
				margin: 0 auto;
				width: 80%;
				border-radius: 10px;
				box-shadow: 5px -5px 7px 7px #78909C;
				margin-top: 20px;
			}
			tr, th, td{
				border-radius: 5px;
				padding: 5px 10px 5px 10px;
			}
			#titles{
				background: #0D47A1;
				color: white;
			}
			#content{
				background: #BBDEFB;
			}
			img{
				position: absolute;
			}
		</style>
	</head>
	<body>
	<a href="services.php"><img src="../img/back.png"></a>
	<?php 
		if(isset($_GET['id']) && isset($_GET['date']) && isset($_GET['name'])){
	?>
	<div id="headCon">
		<h3>Cliente: <?php echo $_GET['name']; ?></h3>
		<h4>Fecha: <?php echo $_GET['date']; ?></h4>
		<h4>Total venta: $<?php echo $Connection->totalSale($_GET['id'], $_GET['date']); ?></h4>
	</div>	
	<table id="tableSale" border="1">
		<tr id="titles">
			<th>ID Servicio</th>
			<th>Nombre</th>
			<th>Precio</th>
			<th>Cantidad</th>
			<th>Importe</th>
			<th>Descuento</th>
			<th>Adeudo</th>
		</tr>
		<?php $A=$Connection->seeCustomerSale($_GET['id'], $_GET['date']); 
			for($i=0; $i < count($A); $i++){
				$token=explode("%", $A[$i]);
		?>
				<tr id="content">
					<th><?php echo $token[0]; ?></th>
					<th><?php echo $token[1]; ?></th>
					<th><?php echo $token[2]; ?></th>
					<th><?php echo $token[3]; ?></th>
					<th><?php echo $token[4]; ?></th>
					<th><?php echo $token[5]; ?></th>
					<th><?php echo $token[6]; ?></th>
				</tr>
	
	<?php
			}
		}
	?>
	</table>
	</body>
</html>
