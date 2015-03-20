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
	require_once('db_connect.php');
	$Connection= new DBConnect();
	$Services=$Connection->getServiceWithPrice(0);
	$dataService=array();
?>
<html>
	<head>
		<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="../css/myStyle.css">
	</head>
	<body>
		<form method="POST" action="update.php">
			<table>
				<tr>
					<td>Eliga el producto:</td>
					<td>
						<select id="servicio" name="service">
							<?php for($i=0; $i < count($Services); $i++){
										$token=explode("%", $Services[$i]);
							?>					
								<option value="<?php echo $token[0]; ?>">
									<?php echo $token[1]; ?>
								</option>
							<?php 	
								  }?>	
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<center><input type="submit" value="Ver datos"></center>
					</td>
				</tr>
			</table>
		</form>
		<?php if(isset($_POST['service'])){
				$dataService=$Connection->showDataService($_POST['service']);
			?>

					<form id="update" action="update.php" method="POST">
						<h3>Actualizar servicio</h3>
						<table>	
							<tr>
								<td>ID:</td>
								<td><input type="text" name="id" readonly="readonly" value="<?php echo $dataService['idproducto'];?>"required></td>
							</tr>
							<tr>
								<td>Descripción:</td>
								<td><input type="text" name="desc" value="<?php echo $dataService['descripcion'];?>" required></td>
							</tr>
							<tr>
								<td>Precio:</td>
								<td><input type="text" name="price" value="<?php echo $dataService['precio'];?>" required></td>
							</tr>
							<tr>
								<td>Unidad:</td>
								<td><input type="text" name="unit" value="<?php echo $dataService['unidad'];?>" required></td>
							</tr>
							<tr>
								<td>Existencias:</td>
								<td><input type="number" name="stock" value="<?php echo $dataService['existencia'];?>" required></td>
							</tr>
							<tr>
								<td colspan="2">
									<center><input type="submit" value="Actualizar"></center>
								</td>
							</tr>
						</table>	
					</form>
		<?php }
			if(isset($_POST['id']) && isset($_POST['desc']) && isset($_POST['price']) &&
			   isset($_POST['unit']) && isset($_POST['stock'])){
				if($Connection->updateService($_POST['id'], $_POST['desc'], $_POST['price'], $_POST['unit'], $_POST['stock']))
					echo '<p>El producto a sido actualizado.</p>';
				else
					echo '<p>No se pudo actualizar el producto. Intente de nuevo</p>';
			}
		?>
	</body>
</html>