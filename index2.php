<?php 
	/*Validando*/
	header('Content-Type: text/html; charset=utf-8');
	session_start();
    if(!isset($_SESSION['admin'])){
  	  header("Location:index.php?validate=no");
  	  exit;
 	}else if($_SESSION['admin']!="VENTA"){
	    header("Location:panel.php");
	    exit;
  	}
	/*Creando el objeto*/
	require_once('controller/db_connect.php');
	$Connection= new DBConnect();

	/*Crear arreglo de empleados*/
	$Employes=$Connection->getEmployes();
	/*Crear arreglo de servicios*/
	$Services=$Connection->getServiceWithPrice(1);


	/*Lanzar alerta al registrar una venta*/
	if(isset($_GET['register'])){
		echo "<h3>La venta ha sido añadida a la Base de datos. Redireccionando al sistema en 2 segundos</h3>";
		echo '<meta http-equiv="refresh" content="2; url=index2.php">';
		exit;
	}
	/*Recibe el id del servicio devuelve su precio*/
	if(isset($_POST['idServ'])){
		for($i=0; $i < count($Services); $i++){
			$token=explode("%", $Services[$i]);
			if(strcmp($token[0], $_POST['idServ'])==0)
				echo $token[2];
		}
		exit;
	}
	/*Devuelve el primer precio*/
	if(isset($_POST['price'])){
			$token=explode("%", $Services[0]);
			echo $token[2];
		exit;
	}
	/*Validar usuario*/
	if(isset($_POST['user']) && isset($_POST['passwd'])){
		if($Connection->validateLogin($_POST['user'], $_POST['passwd']))
			echo "YES";
		else
			echo "NO";
		exit;
	}

	
?>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="dist/css/bootstrap.min.css" />
	<link rel="stylesheet" href="css/main.css" />
	<link rel="stylesheet" type="text/css" href="css/mainDos.css" />
	<meta charset="UTF-8" />
	<script src="js/sha1-min.js"></script>
	<script src="js/jquery.js"></script>
	<script src="js/jquery.lightbox_me.js" type="text/javascript" charset="utf-8"></script>
    <script>
                
    		/*Función para agregar el precio autómaticamente*/
                function addPrice(idServ){
                        var parametros = {
                                "idServ" : idServ
                        };
                        $.ajax({
                                data:  parametros,
                                url:   'index2.php',
                                type:  'post',
                                success:  function (response) {
                                     document.getElementById("preU").value=response;
                                     document.getElementById("can").value="";
                                     document.getElementById("imp").value="";
                                }
                        });
                }
                /*Función para poner el primer precio*/
                function setPriceFirst(price){
                        var parametros = {
                                "price" :  price
                        };
                        $.ajax({
                                data:  parametros,
                                url:   'index2.php',
                                type:  'post',
                                success:  function (response) {
                                     document.getElementById("preU").value=response;
                                }
                        });
                }
                /*Función que valida el usuario para poder aplicar el descuento del 30%*/
                function validateDiscount(user, passwd){
                		var parametros = {
                                "user" : user,
                                "passwd" : passwd
                        };
                        $.ajax({
                                data:  parametros,
                                url:   'index2.php',
                                type:  'post',
                                success:  function (response) {
                                     if(response == "YES"){
										alert("Ahora puede aplicar el 30% de descuento.");
                                     }else{
                                     	alert("Error al identificarse. Datos incorrectos.");
                                     	$('#descuento > option[value="0"]').attr('selected', 'selected');
                                     }
                                }
                        });
					
                }
    </script>
	<title>Sistema Copy</title>
</head>
<body onload="setPriceFirst(1)">
	<a href="controller/signIn.php?logout=true"><h3 id="titulo">Modulo de Cobro COPY GRAPHICS</h3></a>
	<div class="col-md-12">
		<form method="POST" action="controller/addSale.php" onsubmit="serialize(datosDeVentas)">
			<div class="col-md-12">
				<center>
					<label>Tipo de Cliente</label>
					<select name="tipo_cliente" class="form-control" id="tipcliente">
						<option value="inmediato">Inmediato</option>
						<option value="liquidado">Liquidado</option>
						<option value="porcobrar">Por Cobrar</option>
					</select>
				</center>
				<div id="adel">
					Adelanto: <input type="number" name="adelanto" id="adelanto">
				</div>
				<div class="col-md-4">
					<h4>Datos Empleado</h4>
					<label>Atendio:</label>
					<select name="atendido" class="form-control">
					<?php for($i=0; $i < count($Employes); $i++){
								$token=explode("%", $Employes[$i]);
					?>		
								<option value="<?php echo $token[0]; ?>"><?php echo $token[1] ;?></option>		
					<?php 	
						  }?>
					</select>
				</div>
				<div class="col-md-4">
					<h4>Ventas</h4>
					<label>Servicio</label>
					<select id="servicio" name="servicio" class="form-control" onChange="addPrice($('#servicio').val());">
					<?php for($i=0; $i < count($Services); $i++){
								$token=explode("%", $Services[$i]);
					?>					
						<option value="<?php echo $token[0]; ?>">
							<?php echo $token[1]; ?>
						</option>
					<?php 	
						  }?>	
					</select>

					<label>Cantidad</label>
					<input name="can" id="can" type="text" min="1" class="form-control input-sm" required=""/>
					<label>Precio Unitario</label>
					<input name="preU" id="preU" type="text" class="form-control input-sm" required="" readonly="readonly"/>
					<label>Importe</label>
					<input name="imp" id="imp" type="text" class="form-control input-sm" required="" readonly="readonly"/>
					<label>Descripción</label>	
					<input name="des" id="des"  required="" class="form-control input-sm"/>  
					<label>Descuento</label>
					<select name="descuento" id="descuento">
						<option value="0">0 %</option>
						<option value="10">10 %</option>
						<option value="20">20 %</option>
						<option value="30">30 %</option>
					</select>   		  			

				</div>
				<div class="col-md-4" id="ocultar">
					<h4>Datos del Cliente</h4>
					<label>Cliente:</label>
					<input name="cliente" id="cliente" type="text" class="form-control input-sm" required=""/>
					<label>Direccion:</label>
					<input name="direccion" id="direccion" type="text" class="form-control input-sm" required=""/>
					<label>R.F.C </label>
					<input name="rfc" id="rfc" type="text" class="form-control input-sm" required=""/>
					<label>Teléfono: </label>
					<input name="tel" id="tel" type="text" class="form-control input-sm" required=""/>
					<label>E-mail Cliente: </label>
					<input name="email" id="email" type="email" class="form-control input-sm" required=""/>
				</div>
			</div> <!--div que contiene los 3 div con las tablas  -->
			<div class="col-md-12">
				<center>
					<input type="hidden" id="totalV" name="totalV" value="" />
					<input type="hidden" name="arrayVentas" value="" /><!-- Div oculto que contendrá el array ventas -->
					<input type="button" value="Agregar Tabla" name="boton" id="agr" class="btn btn-primary btn-embossed btn-hg" />
					<input type="submit" value="Registra Venta" name="boton" id="btn1" class="btn btn-primary btn-embossed btn-hg" /> 
				</center>
			</div> <!-- fin del div que contiene los botones -->
		</form>
	</div> <!--  DIV principall --><br><br><br><br>

	<center>
		<h4 id="total"></h4>
	</center>

	<div id="tituloTabla" style="clear: both;" class='genAuto'><p id="titulo" />
		<table id="tabla">
			<tr>
				<td>Servicio</td>
				<td>Cantidad</td>
				<td>Precio Unitario</td> 
				<td>Importe</td>
				<td>Descripción</td>
				<td>Descuento</td>
			</tr>
		</table>
	</div> 
	<!--tabla ventas -->

	<div id="contRegistros">

	</div>	

	<!-- FORMULAIO DE LOGUEO-->
	<div id="sign_up">
		<h3 id="see_id" class="sprited" >Identifíquese</h3>
		<span>Para poder aplicar el 30% de descuento necesita identficarse</span>
		<div id="sign_up_form">
			<br>
			<label><strong>Username:</strong> <input class="sprited" id="user"/></label>
			<label><strong>Password:</strong> <input class="sprited" id="pwd" type="password"/></label>
			<div id="actions">
				<a class="close form_button sprited" id="cancel" href="#">Cancelar</a>
				<a class="close form_button sprited" id="ingresar" href="#" onClick="validateDiscount($('#user').val(), hex_sha1($('#pwd').val()))">Aplicar</a>
			</div>
		</div>
	</div>
	<!-- FORMULARIO DE LOGUEO-->

</body>
<script>


//**************************Codigo ocultar div cliente***************************
document.getElementById('ocultar').style.visibility="hidden";
document.getElementById('sign_up').style.visibility="hidden";
document.getElementById('adel').style.visibility="hidden";

document.getElementById('cliente').value=0;
document.getElementById('direccion').value=0;
document.getElementById('rfc').value=0;
document.getElementById('email').value="email@email.com";
document.getElementById('tel').value=0;
document.getElementById('tel').value=0;
document.getElementById('adelanto').value=0;

$('#tipcliente').change(function(){

	var lista = document.getElementById("tipcliente");
	var indiceSeleccionado = lista.selectedIndex;
	var opcionSeleccionada = lista.options[indiceSeleccionado];
	var textoSeleccionado = opcionSeleccionada.text;

	if(textoSeleccionado=="Inmediato"){
		document.getElementById('ocultar').style.visibility="hidden";
		document.getElementById('adel').style.visibility="hidden"; 
		document.getElementById('cliente').value=0;
		document.getElementById('direccion').value=0;
		document.getElementById('rfc').value=0;
		document.getElementById('email').value="email@email.com";
		document.getElementById('tel').value=0;
		document.getElementById('adelanto').value=0;
	}else if(textoSeleccionado=="Liquidado"){
		document.getElementById('ocultar').style.visibility="visible";7
		document.getElementById('adel').style.visibility="hidden"; 
		document.getElementById('cliente').value="";
		document.getElementById('direccion').value="";
		document.getElementById('rfc').value="";
		document.getElementById('email').value="";
		document.getElementById('tel').value="";
		document.getElementById('adelanto').value=0;
	}else{
		document.getElementById('ocultar').style.visibility="visible"; 
		document.getElementById('adel').style.visibility="visible"; 
		document.getElementById('cliente').value="";
		document.getElementById('direccion').value="";
		document.getElementById('rfc').value="";
		document.getElementById('email').value="";
		document.getElementById('tel').value="";
		document.getElementById('adelanto').value="";
	}
});

//**************************Termina codigo ocultar cliente**************************


var number=0;
var datosDeVentas = new Array();
var divsId = new Array();
var texto="";
var bandera=0;
var ventaTotal=0;
var importeTotal=0;

jQuery('#can').keyup(function(){
	this.value = this.value.replace(/[^0-9\.]/g,'');
	var cantidad = $('#can').val();
	var precioxunidad = $('#preU').val();
	var resultado = cantidad * precioxunidad;
	$('#imp').val(resultado);
});

jQuery('#tel').keyup(function(){
	this.value =  this.value.replace(/[^0-9\.]/g,'');
});
jQuery('#preU').keyup(function(){
	this.value =  this.value.replace(/[^0-9\.]/g,'');
});
// funcion para relizar de forma automatica  la multiplicacion de 
// can y preU
/*jQuery('#imp').click(function(){
	var cantidad = $('#can').val();
	var precioxunidad = $('#preU').val();
	var resultado = cantidad * precioxunidad;
	$('#imp').val(resultado);

});*/

/************************************ NOTA IMPORTANTE **************************************
LOS DATOS SELECCIONADOS TIENE QUE SACARLOS DE UN ARRAY QUE LES VOY A DEJAR A CONTINUACION SE LLAMA: "datosDeVentas",
SI NO SACAN LOS DATOS DE ESE ARRAY Y POR CUALQUIER RAZON LA PAGINA SE RECARGA O PRECIONAN EN EL BOTON
DE AGREGAR (QUE PERTENECE AL FORMULARIO) NO SE VA A ENVIAR NADA POR QUE JAVASCRIPT ALAMECENA DATOS SIEMPRE Y CUANDO
NO SE RECARGE LA PAGINA, SI SE LLEGA A RECARGAR LOS DATOS SE INICIALIZAN EN 0, ASI QUE ANTES O CUANDO EL USUARIO DE CLIC
EN EL BOTON AGREGAR, SAQUEN LOS DATOS DEL ARRAY Y METANLOS A LA BASE DE DATOS, ESO SE HACE CON PHP....

EL ARRAY ALMACENA TODOS LOS DATOS Y SE MODIFICA SI EL USUARIO YA HA QUITADO O ELIMINADO UNA VENTA, ASI QUE SOLO TIENEN
QUE SACAR LOS DATOS DEL ARRAY COMO ESTAN SIN PREOCUPARSE SI SE ELIMINO O NO LA VENTA, ESO YA LO PROGRAME!

CODE BY JACOB
*************************************************************************************************/

//*******************************CODIGO GENERACION DE TABLAS************************************
$('#agr').click(function(e){
	var cantidad=document.getElementById("can").value;
	var precioUni=document.getElementById("preU").value;
	var importe=document.getElementById("imp").value;
	var descr=document.getElementById("des").value;
	var lista = document.getElementById("servicio");
	var indiceSeleccionado = lista.selectedIndex;
	var opcionSeleccionada = lista.options[indiceSeleccionado];
	var textoSeleccionado = opcionSeleccionada.text;
	var idServicio= opcionSeleccionada.value;

	var listaDescuento = document.getElementById("descuento");
	var indiceSeleccionadoDescuento = listaDescuento.selectedIndex;
	var opcionSeleccionadaDescuento = listaDescuento.options[indiceSeleccionadoDescuento];
	var textoSeleccionadoDescuento = opcionSeleccionadaDescuento.text;
	var descuento= opcionSeleccionadaDescuento.value;

	if(textoSeleccionadoDescuento=="30 %")
		importeTotal=importe-(importe*.30);
	else if(textoSeleccionadoDescuento=="20 %")
		importeTotal=importe-(importe*.20);
	else if(textoSeleccionadoDescuento=="10 %")
		importeTotal=importe-(importe*.10);
	else if(textoSeleccionadoDescuento=="0 %")
		importeTotal=importe;
	else
		importeTotal=importe;

	if(cantidad && precioUni && importe && descr !=""){

		$('#contRegistros').append("<div id="+number+"><p><table id='tabla'><tr><td>"+textoSeleccionado+"</td>"+
			"<td>"+cantidad+"</td>"+
			"<td>"+precioUni+"</td>"+
			"<td>"+importeTotal+"</td>"+
			"<td>"+descr+"</td>"+
			"<td>"+textoSeleccionadoDescuento+"</tr></table></p></div>")
		number++;

		divsId[divsId.length]=number;
		ventaTotal+=parseFloat(importeTotal);
		document.getElementById("totalV").value=ventaTotal;
		console.log(":::::::Nuevo elemento:::::::");

		datosDeVentas[datosDeVentas.length] = idServicio+","+cantidad+","+descr+","+descuento+","+importeTotal;
	//for(var i=0;i<datosDeVentas.length;i++)
	//	console.log(datosDeVentas[i]);

	//console.log(":::::::Nuevo elemento:::::::");
	$("#total").html("Total: "+ventaTotal);

}
});

$('#contRegistros').on("mouseover","div",function(){
	$(this).css("background-color","#81F7BE");
//alert("paso");

});

$('#contRegistros').on("mouseout","div",function(){
	$(this).css("background-color","#ffcdd2");
})

$('#contRegistros').on("click","div", function(){
	var valor=this.id;

	if(confirm("¿Desea eliminar esta venta?")) {
		//alert("Venta eliminada");
		$(this).remove();
		var vMenos = new Array();

		//alert(valor);
		//console.log("Dato a eliminar: "+datosDeVentas[parseInt(valor)]);
		
		vMenos=datosDeVentas[parseInt(valor)].split(",");
		console.log("Aqui datos: "+vMenos[3]);
		
		ventaTotal=ventaTotal-vMenos[3];

		$("#total").html("Total:"+ventaTotal);

		datosDeVentas.splice(parseInt(valor),1);



		for(var i=0;i<number;i++){
			if(parseInt(divsId[i])==parseInt(valor)){
				divsId.splice(parseInt(i),1);
			}
		}

		//console.log("Dato eliminado: "+datosDeVentas[parseInt(valor)])


		//console.log("Nuevos datos::::::");
		//for(var i=0;i<datosDeVentas.length;i++){
			console.log(datosDeVentas[i]);
		//}
		number--;
		newIdDiv(valor);

	} else {
		//OTRA COSA
	}

    //alert("Click en: "+valor);
});

function newIdDiv(valor){

	var p=0;
	$('div').each(function (i){
		elemento=$(this);
		if (elemento.attr('id') >= 0) {
			$(this).attr('id',p)
			p++;
		}
	});
}

//********************************Termina codigo de generación de tablas**********************


//********************************INICIA FORMULARIO DE REGISTRO*******************************
function launch() {
	$('#sign_up').lightbox_me({centered: true, onLoad: function() { $('#sign_up').find('input:first').focus()}});
}

$('#descuento').change(function(e) {

	var lista = document.getElementById("descuento");
	var indiceSeleccionado = lista.selectedIndex;
	var opcionSeleccionada = lista.options[indiceSeleccionado];
	var textoSeleccionado = opcionSeleccionada.text;

	if(textoSeleccionado=="30 %"){

		document.getElementById('sign_up').style.visibility="visible";
		$('#sign_up').lightbox_me({
			centered: true, 
			onLoad: function() { 
				$('#sign_up').find('input:first').focus()
			}
		});
		e.preventDefault();
	}
});
//*****************************TERMINA FORMULARIO DE REGISTRO***********************************//

//BOTON CANCELAR
$('#cancel').click(function(){
	$('#descuento > option[value="0"]').attr('selected', 'selected');
});

//****************************TERMINA APLICA DESCUENTO******************************************//

/*Función arrayJS--->arrayPHP*/
function serialize(arr){
  var res = 'a:'+arr.length+':{';
  for(i=0; i<arr.length; i++){
    res += 'i:'+i+';s:'+arr[i].length+':"'+arr[i]+'";';
  }
  res += '}';
   
  var adelantoIn=document.getElementById('adelanto').value;
  document.getElementById('adelanto').value=ventaTotal - adelantoIn;
  this.document.forms[0].arrayVentas.value = res;
}

</script>
</html>