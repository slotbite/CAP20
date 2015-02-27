<?php 
/******************************************************************************
 *	Nombre:	sql_server_db.php
 *                            -------------------
 *	Comienzo	: Friday, Ago 29, 2008	- S�bado, 29 de Agosto 2008
 *	copyright	: (C) 2008 
 *	email		: lermatt@hotmail.com
 *
 *	$Id: 		: sql_server_db.php, v 0.8
 *
 *
 ******************************************************************************/
 
 
class sql_db {

	/* variables de conexi�n */
	var $data_base 		= "";
	var $server 		= "";
	var $user 			= "";
	var $pass 			= "";

	/* identificador de conexi�n y consulta */
	var $conexion_id 	= 0;
	var $consulta_id 	= 0;
		
	/* String de consulta a la base de datos. */
	var $show_query 	= false;
	var $query 			= "";
	var $result			= null;
	
	
	/* n�mero de error y texto error */
	var $Errno 			= 0;
	var $Error 			= "";
	
	/* M�todo Constructor: Cada vez que creemos una variable de esta clase, se ejecutar� esta funci�n */
	function sql_db($host, $db, $usuario, $passwrd) {
		// Configuraci�n del servidor
		$this->data_base 	= $db;
		$this->server 	 	= $host;
		$this->user 	 	= $usuario;
		$this->pass 	 	= $passwrd;
		
		$this->sql_connect();
	}
	
	/** Establece la conexi�n a la base de datos. */
	function sql_connect() {
		// Conexion a la base de datos.
		//echo $this->server." - ".$this->user." - ".$this->pass;
		if (!($this->conexion_id = mssql_connect($this->server, $this->user, $this->pass))) { 
			die("sql_connect - Funci�n connect(): Error conectando a la base de datos.<BR>".mssql_get_last_message());
			exit(); 
		} else {
			mssql_select_db($this->data_base);
		}
	}

	/** Ejecuta una consulta en la base de datos, y devuelve el resultado. */
	function sql_query($query) {
		// Borra consultas anteriores.
		unset($this->result_query);
		
		if( !mssql_query("select 1") ) {
			return false;
		}
		
		// Valida que el string no venga vacio.
		$this->query = $query ? $query : "select 'No se ha proporcionado la query'";
		//echo $this->query."<br>";
		
		// Ejecuta la consulta
		$this->result = @mssql_query($this->query);
		
		if ($this->result) {
			return $this->result;
		}
		else {
			return false;
		}
	}
	
	function show_query() {
		$this->show_query = true;
	}
	
	/** Retorna un arreglo asociativo a la consulta ejecutada, segun el �ndice de la columna. */
	function sql_fetch_row($result = "") {
		if( $result != "" ) {
				if( !@mssql_execute("SELECT 1") ) {
					return false;
				}
				return mssql_fetch_row($this->result);
		}
		else {
			return false;
		}
	}

	/** Retorna un arreglo asociativo a la consulta ejecutada, segun el nombre de la columna. */
	function sql_fetch_assoc($result = "") {
		if( $result != "" ) {
				if( !@mssql_query("SELECT 1") ) {
					return false;
				}
				return mssql_fetch_assoc($result);
		}
		else {
			return false;
		}
	}
	
	function sql_num_rows($result) {
		return mssql_num_rows($result) ? mssql_num_rows($result) : 0;
	}
	
	/** Cierra la conexi�n a la base de datos. */
	function sql_close() {
		// Valida y cierra la conexi�n.
		if( $this->conexion_id ) {
			return @mssql_close($this->conexion_id);
		}
		else {
			return false;
		}
	}
	
	function sql_buscar_check_sp($resul, $encabezados, $campos) {
		$tabla = "";
		$tabla .= "<table width=\"100%\" class=\"Tabla_datos_Registro\">";
		
		$tabla .= "<tr class=\"Tabla_datos_Registro_enc\">";
		$tabla .= "<th align=\"center\"><input name=\"selectAll\" type=\"checkbox\" id=\"selectAll\"  /></th>";
		
		for ($cont = 0; $cont < count($encabezados); $cont++) {
			$tabla .= "<th>".$encabezados[$cont]."</th>";
		}
		$tabla .= "</tr>";
		
		$i = 0;
		while ($row = $this->sql_fetch_assoc($resul)){
			$i++;
			$tabla .= "<tr>";
			for ($cont = 0; $cont < count($campos); $cont++) {
				if ($cont == 0) {
					$tabla .= "<td align=\"center\"><input name=\"check_id[]\" type=\"checkbox\" id=\"check_id".$row[$campos[$cont]]."\" value=\"".$row[$campos[$cont]]."\" class=\"checkbutton\" /></td>";
				}
				else {
					$tabla .= "<td>".$row[$campos[$cont]]."</td>";
				}
			}
			$tabla .= "</tr>";
		}
		$tabla .= "</table>";
		
		if ($i == 0)
			$tabla = "<h2>No se encontraron Registros.</h2>";
		return $tabla;
	}
        
        
        function sql_ejecutar_sp($stmt){
            
            unset($this->result_query);
		
            if( !mssql_query("select 1") ) {
                    return false;
            }

            // Valida que el string no venga vacio.
            $this->query = $stmt ? $stmt : "select 'No se ha proporcionado la query'";
            //echo $this->query."<br>";

            // Ejecuta la consulta
            
            $this->result = mssql_execute($stmt);                        
            mssql_free_statement($stmt);            
            
            if ($this->result) {
                    return $this->result;
            }
            else {
                    return false;
            }                        
        }
}
?>