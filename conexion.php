<?php 
//Estas lineas nos ayudan a mostrar los errores en el navegador
ini_set('display_errors', 1);
ini_set('dispaly_startup_errors', 1);
error_reporting(E_ALL);

class Conexion{
    private $host = "127.0.0.1";
    private $db_name = "crud_empleados";
    private $username = "admin";
    private $password = "kitkat2909";
    public $conn;

    public function obtenerConexion(){
        $this->conn = null;
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exeption){
            echo "Error de conexion a la base de datos: " . $exeption->getMessage(); 
        }
        return $this->conn;
    }
}

//Prueba de conexion
$conexionTest = new Conexion();
if($conexionTest->obtenerConexion() != null){
   // echo "Conexion exitosa";
}

?>