<?php

class Empresa{
    private $id;
    private $nombre;
    private $direccion;
    private $colecViajes;
    private $mensajeOperacion;

    // ---------------------------------------------------
    //                   CONSTRUCTOR                 
    // ---------------------------------------------------

    /**
     * Método constructor de la clase Empresa
     */
    public function __construct(){
        $this->id = 0;
        $this->nombre = "";
        $this->direccion = "";
        $this->colecViajes = [];
    }

    // ---------------------------------------------------
    //                METODOS DE ACCESO              
    // ---------------------------------------------------

    public function getId(){
        return $this->id;
    }
    public function setId($id){
        $this->id = $id;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function setNombre($nombre){
        $this->nombre = $nombre;
    }
    public function getDireccion(){
        return $this->direccion;
    }
    public function setDireccion($direccion){
        $this->direccion = $direccion;
    }
    public function getColecViajes(){
        return $this->colecViajes;
    }
    public function setColecViajes($colecViajes){
        $this->colecViajes = $colecViajes;
    }
    public function getMensajeOperacion(){
        return $this->mensajeOperacion;
    }
    public function setMensajeOperacion($mensajeOperacion){
        $this->mensajeOperacion = $mensajeOperacion;
    }

    // ---------------------------------------------------
    //                  METODOS VARIOS               
    // ---------------------------------------------------

    /**
     * Carga datos al objeto
     * @param string $nombre
     * @param string $direccion
     */
    public function cargar($nombre, $direccion){
        $this->setNombre($nombre);
        $this->setDireccion($direccion);
    }

    /**
     * Modifica los datos de la empresa en el objeto y en la bd
     * @param string $nuevoNombre todo numero menor o igual a 0 lo deja igual
     * @param string $nuevaDireccion todo numero menor o igual a 0 lo deja igual
     */
    public function modificarDatos($nuevoNombre, $nuevaDireccion){
        if($nuevoNombre  > 0){
            $this->setNombre($nuevoNombre);
        }
        if($nuevaDireccion  > 0){
            $this->setDireccion($nuevaDireccion);
        }
        $this->modificar();
    }

    /**
     * Muestra únicamente los datos de la empresa
     * @return string
     */
    public function mostrarDatos(){
        return
        "\nNumero de empresa: " . $this->getId().
        "\nNombre de la empresa: " . $this->getNombre().
        "\nDirección de la empresa: " . $this->getDireccion();
    }

    // ---------------------------------------------------
    //     METODOS DE INTERACCION CON LA BASE DE DATOS                 
    // ---------------------------------------------------

    /**
     * Busca una empresa en la base de datos y asigna sus datos al objeto actual
     * @param int $idEmpresa
     * @return boolean true si encontró, false caso contrario
     */
    public function buscarEmpresa($idEmpresa){
        $base = new BaseDatos();
        $encontro = false;
        $consulta = "SELECT * FROM empresa WHERE idempresa = " . $idEmpresa;

        if($base->Iniciar()){
            if($base->Ejecutar($consulta)){
                if($fila = $base->Registro()){
                    $encontro = true;
                    $this->setId($idEmpresa);
                    $this->setNombre($fila['enombre']);
                    $this->setDireccion($fila['edireccion']);
                }
            }else{
                $this->setMensajeOperacion($base->getError());
            }
        }else{
            $this->setMensajeOperacion($base->getError());
        }

        return $encontro;
    }

    /**
     * Inserta los datos del objeto Empresa actual a la base de datos.
     * @return boolean true si se concretó, false caso contrario
     */
    public function insertar(){
        $base = new BaseDatos();
        $seConcreto = false;

        // Armamos la consulta
        $consulta = "INSERT INTO empresa(enombre, edireccion)
        VALUES ('".$this->getNombre()."','". $this->getDireccion()."');";

        // Iniciamos la base de datos
        if($base->Iniciar()){
            // Ejecutamos la consulta
            $id = $base->devuelveIDInsercion($consulta);
            if(!is_null($id)){
                $this->setId($id);
                $seConcreto = true;
            }else{
                $this->setMensajeOperacion($base->getError());
            }
        }else{
            $this->setMensajeOperacion($base->getError());
        }

        return $seConcreto;
    }

    /**
     * Modifica los datos de la empresa, colocando los del objeto actual
     * @return boolean true si se concretó, false caso contrario
     */
    public function modificar(){
        $base = new BaseDatos();
        $seConcreto = false;
        $consulta = "UPDATE empresa SET enombre = '" . $this->getNombre() . "', edireccion = '" . $this->getDireccion() .
        "' WHERE idempresa = '" . $this->getId(). "'";

        // Iniciamos la base de datos
        if($base->Iniciar()){
            // Ejecutamos la consulta
            if($base->Ejecutar($consulta)){
                $seConcreto = true;
            }else{
                $this->setMensajeOperacion($base->getError());
            }
        }else{
            $this->setMensajeOperacion($base->getError());
        }

        return $seConcreto;
    }

    /**
     * Elimina el objeto actual de la base de datos
     * @return boolean true si se concretó, false caso contrario
     */
    public function eliminar(){
        $base = new BaseDatos();
        $seConcreto = false;
        $consulta = "DELETE FROM empresa WHERE  idempresa = '" . $this->getId() ."'";

        // Iniciamos la base de datos
        if($base->Iniciar()){
            // Ejecutamos la consulta
            if($base->Ejecutar($consulta)){
                $seConcreto = true;
            }else{
                $this->setMensajeOperacion($base->getError());
            }
        }else{
            $this->setMensajeOperacion($base->getError());
        }

        return $seConcreto;
    }

    // ---------------------------------------------------
    //      REDEFINICIÓN DEL METODO __TOSTRING                
    // ---------------------------------------------------

    /**
     * Convierte un arreglo en string
     * @param array $arreglo
     * @return string
     */
    public function convertirAString($arreglo){
        $cadena = "";
        for($i = 0; $i < count($arreglo); $i++){
            $cadena .= "\n--------------------".
            $arreglo[$i];
        }

        return $cadena;
    }

    /**
     * Convierte a los atributos del objeto en string
     * @return string
     */
    public function __toString()
    {
        return
        "\nNumero de empresa: " . $this->getId().
        "\nNombre de la empresa: " . $this->getNombre().
        "\nDirección de la empresa: " . $this->getDireccion().
        "\n--------------------\nViajes: " . $this->convertirAString($this->getColecViajes());
    }
}
?>