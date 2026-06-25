<?php
//Dado que id es autoincremental, fecha registro se coloca,
//el token es null y el estado es activo por dafult, no se 
//necesitan manipular al crear un usuario.
class Usuario{
    private $nombre;
    private $correo;
    private $password;

    public function __construct($nombre, $correo, $password){
        $this->nombre = $nombre;
        $this->correo = $correo;
        $this->password = $password;
    }

    public function getNombre(){
        return $this->nombre;
    }

    public function getCorreo(){
        return $this->correo;
    }

    public function getPassword(){
        return $this->password;
    }
}
?>