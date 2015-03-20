<?php 
	class DBConnect{
		private static  $_DB='copy';
	 	private static  $_HOST='127.0.0.1';
	 	protected static $_USER='root';
	 	protected static  $_PASSWORD='paz13';
	 	private $conn;
	 	private $stmt;
	 	private $rows;

	 	private function openConnection(){
	 		$this->conn= new PDO('mysql:host='.self::$_HOST.';dbname='.self::$_DB.'', self::$_USER, self::$_PASSWORD);
	 		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 		}

	 	private function closeConnection(){
	 		$this->conn->close();
	 	}

	 	/*Función obtener servicio con su precio*/
	 	public function getServiceWithPrice($v){
	 		try{
	 			$this->openConnection();
	 			
	 			if($v==0)
	 				$query="SELECT idproducto, descripcion, precio FROM producto";
	 			else
	 				$query="SELECT idproducto, descripcion, precio FROM producto WHERE existencia > 10";

	 			$this->stmt = $this->conn->prepare($query);
			  	$this->stmt->execute();
			  	$con=0;
			  	$A=array();

			  	while($this->rows=$this->stmt->fetch()){
			  		$A[$con]=$this->rows[0].'%'.$this->rows[1].'%'.$this->rows[2].'%';
			  		$con++;
			  	}

			  	return $A;
	 		}catch(PDOException $e){
	 			return 'error'.$e->getMessage();
	 		}
	 			$this->closeConnection();
		 }
		 /*Función obtener id y nombre empleados*/
		 public function getEmployes(){
		 	try{
	 			$this->openConnection();
	 			
	 			$this->stmt = $this->conn->prepare("SELECT * FROM empleados");
			  	$this->stmt->execute();
			  	$con=0;
			  	$A=array();

			  	while($this->rows=$this->stmt->fetch()){
			  		$A[$con]=$this->rows[0].'%'.$this->rows[1].'%';
			  		$con++;
			  	}

			  	return $A;
	 		}catch(PDOException $e){
	 			return false;
	 		}
	 			$this->closeConnection();
		 }
		/*Función logear administrador*/
		public function validateLogin($user, $passwd){
			try{
	 			$this->openConnection();
	 			$this->stmt = $this->conn->prepare("CALL ingresaUsuario(:user, :passwd)");
			  	$this->stmt->bindParam(':user', $user, PDO::PARAM_STR);
			    $this->stmt->bindParam(':passwd', $passwd, PDO::PARAM_STR);
			    $this->stmt->execute();
    
		   		if($this->rows=$this->stmt->fetch())
		     		 return true;
		   		else
		   			return false;

	 		}catch(PDOException $e){
	 			return false;
	 		}
	 			$this->closeConnection();
		}
		/*Función para agregar la venta a la BD*/
		public function addSale($idEmploye, $name, $address, $rfc, $numberPhone, 
							    $email, $typeClient, $state, $idProd, $desc, 
							    $date, $quantity, $discount, $ade, $total, $import){
			try{
	 			$this->openConnection();
	 			$this->stmt = $this->conn->prepare("CALL ingresaVenta(:idEmploye, :name, :address,
	 																  :rfc, :numberPhone, :email, 
	 																  :state, :idProd, :desc,
	 																  :date, :quantity, :discount,
	 																  :ade, :total, :typeClient)");
			  	
			  	
			   /* echo  'CALL ingresaVenta('.$idEmploye.','.$name.', '.$address.', '.$rfc.', '.$numberPhone.','.
			    						  $email.', '.$state.', '.$idProd.', '.$desc.', '.$date.', '.$quantity.', '.
			    						  $discount.', '.$ade.', '.$total.', '.$typeClient.')';
*/
				$this->stmt->execute(array(  ':idEmploye' =>$idEmploye,
											 ':name' =>$name,
											 ':address' =>$address,
											 ':rfc' =>$rfc,
											 ':numberPhone' =>$numberPhone,
											 ':email' =>$email,
											 ':state' =>$state,
											 ':idProd' =>$idProd,
											 ':desc' =>$desc,
											 ':date' =>$date,
											 ':quantity' =>$quantity,
											 ':discount' =>$discount,
											 ':ade' =>$ade,
											 ':total' =>$total,
											 ':typeClient' =>$typeClient));
			//	echo 'EXECUTE()</br>';
			    //$this->stmt->execute();
			    /*
			    if($this->stmt->execute())
			    	return true;
			    else 
			    	return false;
    */
		   		

	 		}catch(PDOException $e){
	 			echo $e->getMessage().' '.$e->getCode().' '.$e->getLine();
	 		}
	 			//$this->closeConnection();


		}
		/*Función para obtener los servicios por cobrar y pendientes*/
		public function getRequestedService(){
			try{
	 			$this->openConnection();
	 			$this->stmt = $this->conn->prepare("CALL seleccionarServ()");
			    $this->stmt->execute();
		 
		   		$con=0;
			  	$A=array();
			  	while($this->rows=$this->stmt->fetch()){
			  		$A[$con]=$this->rows[0].'%'.$this->rows[1].'%'.$this->rows[2].'%'.$this->rows[3].'%'.$this->rows[4].'%'.$this->rows[5].'%'.$this->rows[6];
			  		$con++;
			  	}
			  	return $A;
	 		}catch(PDOException $e){
	 			return false;
	 		}
	 			$this->closeConnection();
		}
		/*Función para desplegar los datos del serivicio*/
		public function showDataService($id){
			try{
	 			$this->openConnection();
	 			$this->stmt = $this->conn->prepare("SELECT * FROM producto WHERE idproducto=:id");
	 			$this->stmt->bindParam(':id', $id, PDO::PARAM_STR);
			    $this->stmt->execute();

		  		return $this->stmt->fetch(PDO::FETCH_ASSOC);

	 		}catch(PDOException $e){
	 			return false;
	 		}
	 			$this->closeConnection();
		}
		/*Función para actualizar el servicio*/
		public function updateService($id, $desc, $price, $unit, $stock){
			try{
	 			$this->openConnection();
	 			$this->stmt = $this->conn->prepare("UPDATE producto SET descripcion=:desc, precio=:price, unidad=:unit,".
	 											   " existencia=:stock WHERE idproducto=:id");
	 			$this->stmt->bindParam(':id', $id, PDO::PARAM_STR);
	 			$this->stmt->bindParam(':desc', $desc, PDO::PARAM_STR);
	 			$this->stmt->bindParam(':price', $price, PDO::PARAM_STR);
	 			$this->stmt->bindParam(':unit', $unit, PDO::PARAM_STR);
	 			$this->stmt->bindParam(':stock', $stock, PDO::PARAM_STR);
			    
			    if($this->stmt->execute()) return true;
			    else return false;
			   // echo $id.' '.$desc.' '.$price.' '.$unit.' '.$stock;

	 		}catch(PDOException $e){
	 			echo false;
	 		}
	 			$this->closeConnection();
		}
		/*Función para dar una venta por concluída*/
		public function completeSale($id, $date){
			try{
	 			$this->openConnection();
	 			$this->stmt = $this->conn->prepare("UPDATE ventas SET idservicio=4 WHERE idcliente=:id AND fecha=:date");
	 			$this->stmt->bindParam(':id', $id, PDO::PARAM_STR);
	 			$this->stmt->bindParam(':date', $date, PDO::PARAM_STR);

			    
			    if($this->stmt->execute()) return true;
			    else return false;

	 		}catch(PDOException $e){
	 			echo false;
	 		}
	 			$this->closeConnection();
		}
		/*Función para sacar la venta total*/
		public function totalSale($id, $date){
			try{
	 			$this->openConnection();
	 			$this->stmt = $this->conn->prepare("SELECT SUM(total) FROM ventas WHERE idcliente=:id AND fecha=:date");
	 			$this->stmt->bindParam(':id', $id, PDO::PARAM_STR);
	 			$this->stmt->bindParam(':date', $date, PDO::PARAM_STR);	 			
	 			$this->stmt->execute();
			   
			    if($this->rows=$this->stmt->fetch())
		     		 return $this->rows[0];
		   		else
		   			return false;

	 		}catch(PDOException $e){
	 			echo false;
	 		}
	 			$this->closeConnection();
		}
		/*Función para ver los sevicios de la venta de un cliente*/
		public function seeCustomerSale($id, $date){
			try{
	 			$this->openConnection();
	 			$this->stmt = $this->conn->prepare("CALL seeCustomerSale(:id, :date)");
	 			$this->stmt->bindParam(':id', $id, PDO::PARAM_STR);
	 			$this->stmt->bindParam(':date', $date, PDO::PARAM_STR);	 			
	 			$this->stmt->execute();
			   
			    $con=0;
			  	$A=array();
			  	while($this->rows=$this->stmt->fetch()){
			  		$A[$con]=$this->rows[0].'%'.$this->rows[1].'%'.$this->rows[2].'%'.$this->rows[3].'%'.$this->rows[4].'%'.$this->rows[5].'%'.$this->rows[6];
			  		$con++;
			  	}
			  	return $A;

	 		}catch(PDOException $e){
	 			echo false;
	 		}
	 			$this->closeConnection();
		}
		/*Función para obtener todas las fechas de ventas*/
		public function getDates(){
			try{
	 			$this->openConnection();
	 			$this->stmt = $this->conn->prepare("SELECT DISTINCT fecha FROM ventas");		
	 			$this->stmt->execute();
			   
			    $con=0;
			  	$A=array();
			  	while($this->rows=$this->stmt->fetch()){
			  		$A[$con]=$this->rows[0];
			  		$con++;
			  	}
			  	return $A;

	 		}catch(PDOException $e){
	 			echo false;
	 		}
	 			$this->closeConnection();
		}
		/*Función para obtener los clientes*/
		public function getCustomers(){
			try{
	 			$this->openConnection();
	 			$this->stmt = $this->conn->prepare("CALL getCustomers()");		
	 			$this->stmt->execute();
			   
			    $con=0;
			  	$A=array();
			  	while($this->rows=$this->stmt->fetch()){
			  		$A[$con]=$this->rows[0].'%'.$this->rows[1];
			  		$con++;
			  	}
			  	return $A;

	 		}catch(PDOException $e){
	 			echo false;
	 		}
	 			$this->closeConnection();
		}
		/*Función para obtener las ventas por una determinda fecha*/
		public function getDateSales($date){
			try{
	 			$this->openConnection();
	 			$this->stmt = $this->conn->prepare("CALL getDateSales(:date)");
	 			$this->stmt->bindParam(':date', $date, PDO::PARAM_STR);	 			
	 			$this->stmt->execute();
			   
			    $con=0;
			  	$A=array();
			  	while($this->rows=$this->stmt->fetch()){
			  		$A[$con]=$this->rows[0].'%'.$this->rows[1].'%'.$this->rows[2].'%'.$this->rows[3].'%'.$this->rows[4].'%'.$this->rows[5];
			  		$con++;
			  	}
			  	return $A;

	 		}catch(PDOException $e){
	 			return false;
	 		}
	 			$this->closeConnection();
		}
		/*Función para obtener el producto más vendido y la venta total por una determinada fecha*/
		public function bestSellerAndTotalSaleDate($date, $v){
			try{
	 			$this->openConnection();
	 			$this->stmt = $this->conn->prepare("CALL bestSellerAndTotalSaleDate(:date, :v)");
	 			$this->stmt->bindParam(':date', $date, PDO::PARAM_STR);	 
	 			$this->stmt->bindParam(':v', $v, PDO::PARAM_INT);			
	 			$this->stmt->execute();

			  	if($this->rows=$this->stmt->fetch())
			  		return $this->rows[0];

	 		}catch(PDOException $e){
	 			return false;
	 		}
	 			$this->closeConnection();
		}
		/*Función para obtener las ventas por un cliente determinado*/
		public function getCustomerSale($id){
			try{
	 			$this->openConnection();
	 			$this->stmt = $this->conn->prepare("CALL getCustomerSale(:id)");
	 			$this->stmt->bindParam(':id', $id, PDO::PARAM_INT);	 			
	 			$this->stmt->execute();
			   
			    $con=0;
			  	$A=array();
			  	while($this->rows=$this->stmt->fetch()){
			  		$A[$con]=$this->rows[0].'%'.$this->rows[1].'%'.$this->rows[2].'%'.$this->rows[3].'%'.$this->rows[4].'%'.$this->rows[5].'%'.$this->rows[6];
			  		$con++;
			  	}
			  	return $A;

	 		}catch(PDOException $e){
	 			return false;
	 		}
	 			$this->closeConnection();
		}
		/*Función para obtener el producto más vendido y la venta total por una determinada fecha*/
		public function bestSellerAndTotalSaleCus($id, $v){
			try{
	 			$this->openConnection();
	 			$this->stmt = $this->conn->prepare("CALL bestSellerAndTotalSaleCus(:id, :v)");
	 			$this->stmt->bindParam(':id', $id, PDO::PARAM_INT);	 
	 			$this->stmt->bindParam(':v', $v, PDO::PARAM_INT);			
	 			$this->stmt->execute();

			  	if($this->rows=$this->stmt->fetch())
			  		return $this->rows[0];

	 		}catch(PDOException $e){
	 			return false;
	 		}
	 			$this->closeConnection();
		}
	}