<?php
	require_once('controller/db_connect.php');
	$Connection= new DBConnect();

	if($Connection->validateLogin('Gei', 'bddf7d2d3cc51c78a05e09f7315695e57a7b2149'))
		echo "YES";
	else
		echo "NO";

	$S= $Connection->getEmployes();
	$Services= $Connection->getServiceWithPrice();
	$ser= serialize($Services);
	echo json_encode(unserialize($ser));
	echo $Services[0].'<br>';
	print_r($S);
	$hola="Hola";
	echo '<script>'.
				'alert($hola);'.
		 '</script>';
	
	if($Connection->validateLogin('Gei', 'bddf7d2d3cc51c78a05e09f7315695e57a7b2149'))
		echo "correcto";
	else
		echo "incorrecto";
	
	
// definimos un array de valores en php
$arrayPHP=array("casa","coche","moto");
?>
<script type="text/javascript">
    // obtenemos el array de valores mediante la conversion a json del
    // array de php
    var arrayJS=<?php echo json_encode($arrayPHP);?>;
    
    // Mostramos los valores del array
    for(var i=0;i<arrayJS.length;i++)
    {
        document.write("<br>"+arrayJS[i]);
    }

  
</script>