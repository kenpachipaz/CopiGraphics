<?php
	require_once('db_connect.php');
	$Connection= new DBConnect();
	$typeClient=$_POST['tipo_cliente'];
	$idEmploye= $_POST['atendido'];

	$name=$_POST['cliente'];
	$address=$_POST['direccion'];
	$rfc= $_POST['rfc'];
	$numberPhone=$_POST['tel'];
	$email=$_POST['email'];
	
	$idProd=$_POST['servicio'];
	$date= date('Y-m-d');
	$ade=$_POST['adelanto'];
	$state=1;
	$total=$_POST['totalV'];
	$arraySale= unserialize($_POST['arrayVentas']);
	if(isset($typeClient)){
		switch($typeClient){
			case  "inmediato":
				/*for($i=0; $i < count($arraySale); $i++){
					$tokenSale=explode(",", $arraySale[$i]);
					echo 'addSale('.$idEmploye.', '.$typeClient.', '.$typeClient.', '.$typeClient.', '.$typeClient.',
								'.$typeClient.', '.$typeClient.', 0 , '.$tokenSale[0].', '.$tokenSale[2].',
								'.$date.', '.$tokenSale[1].', '.$tokenSale[3].', '.$ade.' ,'.round($total, 2).')'.'<br/>';
					//CALL ingresaVenta(1, 'inmediato', 'inmediato', 'inmediato', 'inmediato', 'inmediato', 'inmediato', 0 , 'AMP9060', 'Nada', 2015-03-11, 4, 0, 0 ,72);
				}*/
				for($i=0; $i < count($arraySale); $i++){
					$tokenSale=explode(",", $arraySale[$i]);
					$Connection->addSale($idEmploye, $typeClient, $typeClient, $typeClient, $typeClient,
									     $typeClient, $typeClient, 1 , $tokenSale[0], $tokenSale[2],
										 $date, $tokenSale[1], $tokenSale[3], $ade ,round($tokenSale[4], 2));
					//echo $i.'<br>';
				}
				header('Location:../index2.php?register=true');
				

			break;
			case "liquidado":
				for($i=0; $i < count($arraySale); $i++){
					$tokenSale=explode(",", $arraySale[$i]);
					$Connection->addSale($idEmploye, $name, $address, $rfc, $numberPhone,
									     $email, $typeClient, 2, $tokenSale[0], $tokenSale[2],
										 $date, $tokenSale[1], $tokenSale[3], 0 ,round($tokenSale[4], 2));
				}
				header('Location:../index2.php?register=true');
			break;
			case "porcobrar":
				for($i=0; $i < count($arraySale); $i++){
					$tokenSale=explode(",", $arraySale[$i]);
					$Connection->addSale($idEmploye, $name, $address, $rfc, $numberPhone,
									     $email, $typeClient, 3, $tokenSale[0], $tokenSale[2],
										 $date, $tokenSale[1], $tokenSale[3], $ade,round($tokenSale[4], 2));
				}
				header('Location:../index2.php?register=true');
			break;
		}
	}