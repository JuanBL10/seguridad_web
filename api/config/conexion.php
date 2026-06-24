<?php
class Conexion{
    private $host = "localhost";
    private $database = "seguridad_web";
    private $user = "root";
    private $password = "";

    public function Conectar(){
        try{
            $conexion = new PDO(
                "mysql:host={$this->host};dbname={$this->database}",
                $this->user,
                $this->password
            );
            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conexion;
        }
        catch(PDOException $error){
            die($error->getMessage());
        }
    }
}
?>