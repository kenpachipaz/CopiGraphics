<?php 
  /*Validando*/
  session_start();
    if(!isset($_SESSION['admin'])){
    header("Location:index.php?validate=no");
    exit;
  }else if($_SESSION['admin']!="ADMIN"){
    header("Location:index2.php");
    exit;
  }
?>
<html>
    <head>
    	<link rel="stylesheet" type="text/css" href="dist/css/bootstrap.min.css">
      <link rel="stylesheet" type="text/css" href="css/myStyle.css">
		<meta charset="UTF-8">
		<script src="js/jquery.js"></script>
    <script>
      function showSection(section){
        switch(section){
            case 0:
              document.getElementById('serv').style.display= 'block';

              document.getElementById('up').style.display= 'none';
              document.getElementById('sal').style.display= 'none';
            break;
            case 1:
              document.getElementById('up').style.display= 'block';

              document.getElementById('serv').style.display= 'none';
              document.getElementById('sal').style.display= 'none';
            break;
            case 2:
              document.getElementById('sal').style.display= 'block';

              document.getElementById('up').style.display= 'none';
              document.getElementById('serv').style.display= 'none';
            break;
        }
      }
    </script>
		<title>Panel de Administración</title>
    </head>
	<body>
		<center>
			<h2 id="titulo">Panel de Administración</h2>
		</center>

		<div class="col-md-12">
				<div class="col-md-12">
					<div class="row">
              <div class="col-md-4 col-md-offset-4">
                  <div class="container">
                      <ul class="nav nav-pills">
                        <li role="presentation" ><a href="#" onclick="showSection(0)">Servicios</a></li>
                        <li role="presentation"><a href="#" onclick="showSection(1)">Actualizar</a></li>
                        <li role="presentation"><a href="#" onclick="showSection(2)">Ventas</a></li>
                        <li role="presentation"><a href="controller/signIn.php?logout=true">Salir</a></li>
                      </ul>  
                  </div>
              </div>  <!-- Div donde se encuentran el menu  --> 
          </div>			
	     	</div> <!-- fin del div que contiene los botones -->
		</div>
    <section class="mainContent">
        <iframe id="serv" src="controller/services.php">
          Actualiza tu navegador para poder ver esta información.
        </iframe>
        <iframe id="up" src="controller/update.php" style="display:none;">
          Actualiza tu navegador para poder ver esta información.
        </iframe>
        <iframe id="sal" src="controller/sales.php" style="display:none;">
          Actualiza tu navegador para poder ver esta información.
        </iframe>
    </section>
  </body>
</html>