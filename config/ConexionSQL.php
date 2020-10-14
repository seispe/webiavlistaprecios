<?php

    $driver_secundaria = "sqlServer";
    $host_secundaria = "18.224.232.68";
    $user_secundaria = "sa";
    $pass_secundaria = "Sistemas@2019*P";
    $database_secundaria = "GPIAV";
    $charset_secundaria = "utf8";
    $port_secundaria = "3306";


$con;
try {
    $conexionSQL = new PDO(
        "sqlsrv:Server=$host_secundaria;Database=$database_secundaria;", "$user_secundaria", "$pass_secundaria",
        array(
            //PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        )
    );
}
catch(PDOException $e) {
    die("Error connecting to SQL Server: " . $e->getMessage());
}

if (!function_exists('ejecutarConsultaSQL'))
{
	  function ejecutarConsultaSQL($sql){
        global $conexionSQL;
          $query=$conexionSQL->query($sql);
          return $query;
    }

    function ejecutarConsultaSimpleFila($sql)
	{
        global $conexionSQL;
        $query=$conexionSQL->query($sql);
		$row = $query->fetchObject();
		return $row;
	}

     function ejecutarProcedureSQL($sql){
        global $conexionSQL;
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        return $conexionSQL->prepare($sql);
       
  }  

   function ejecutarConsultaSQL_InsertID($sql){
    global $conexionSQL;
    $conexionSQL->query($sql);
    return $conexionSQL->lastInsertId(); 
    }
}
?>
