<?php

class ResponsableV{
    private $numEmpleado;
    private $numLicencia;
    private $nombre;
    private $apellido;
    private $mensajeOperacion;

    // ---------------------------------------------------
    //                   CONSTRUCTOR                 
    // ---------------------------------------------------

    /**
     * Método constructor
     */
    public function __construct()
    {
        $this->numEmpleado = null;
        $this->numLicencia = 0;
        $this->nombre = "";
        $this->apellido = "";
    }

    // ---------------------------------------------------
    //                METODOS DE ACCESO              
    // ---------------------------------------------------

    
    public function getNumEmpleado()
    {
        return $this->numEmpleado;
    }
    public function setNumEmpleado($nuevoNumEmpleado)
    {
        $this->numEmpleado = $nuevoNumEmpleado;
    }
    public function getNumLicencia()
    {
        return $this->numLicencia;
    }
    public function setNumLicencia($nuevoNumLicencia)
    {
        $this->numLicencia = $nuevoNumLicencia;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function setNombre($nuevoNombre)
    {
        $this->nombre = $nuevoNombre;
    }
    public function getApellido()
    {
        return $this->apellido;
    }
    public function setApellido($nuevoApellido)
    {
        $this->apellido = $nuevoApellido;
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
     * @param int $numLicencia
     * @param string $nombre
     * @param string $apellido
     */
    public function cargar($numLicencia, $nombre, $apellido){
        $this->setNumLicencia($numLicencia);
        $this->setNombre($nombre);
        $this->setApellido($apellido);
    }

    /**
     * Modifica los datos de un responsable
     * @param string $nombre
     * @param string $apellido
     */
    public function modificarDatos($nombre, $apellido){
        if($nombre != -1){
            $this->setNombre($nombre);
        }
        if($apellido != -1){
            $this->setApellido($apellido);
        }
        $this->modificar();
    }

    // ---------------------------------------------------
    //     METODOS DE INTERACCION CON LA BASE DE DATOS                 
    // ---------------------------------------------------
    
    /**
     * Recupera los datos de un responsable - colocandolos al objeto actual.
     * @param int $numEmpleado
     * @return boolean - true si encontró, false caso contrario
     */
    public function buscarResponsable($numEmpleado){
        $base = new BaseDatos();
        $consulta = "SELECT * FROM responsable WHERE rnumeroempleado=" . $numEmpleado;
        $seConcreto = false;

        // Iniciamos la bd
        if($base->Iniciar()){
            // Ejecutamos la consulta
            if($base->Ejecutar($consulta)){
                // Asignamos los datos a $fila siempre y cuando hayan datos (Registro() retorne true)
                if($fila = $base->Registro()){
                    $this->setNumEmpleado($numEmpleado);
                    $this->setNumLicencia($fila['rnumerolicencia']);
                    $this->setNombre($fila['rnombre']);
                    $this->setApellido($fila['rapellido']);
                    $seConcreto = true;
                }
            }else{
                $this->setMensajeOperacion($base->getError());
            }
        }else{
            $this->setMensajeOperacion($base->getError());
        }

        return $seConcreto;
    }

    /**
     * Recupera los datos de un responsable mediante el número de licencia - colocandolos al objeto actual.
     * @param int $numLicencia
     * @return boolean - true si encontró, false caso contrario
     */
    public function buscarResponsablePorLicencia($numLicencia){
        $base = new BaseDatos();
        $consulta = "SELECT * FROM responsable WHERE rnumerolicencia =" . $numLicencia;
        $seConcreto = false;

        // Iniciamos la bd
        if($base->Iniciar()){
            // Ejecutamos la consulta
            if($base->Ejecutar($consulta)){
                // Asignamos los datos a $fila siempre y cuando hayan datos (Registro() retorne true)
                if($fila = $base->Registro()){
                    $this->setNumEmpleado($fila['rnumeroempleado']);
                    $this->setNumLicencia($numLicencia);
                    $this->setNombre($fila['rnombre']);
                    $this->setApellido($fila['rapellido']);
                    $seConcreto = true;
                }
            }else{
                $this->setMensajeOperacion($base->getError());
            }
        }else{
            $this->setMensajeOperacion($base->getError());
        }

        return $seConcreto;
    }


    /**
     * Lista todos los responsables de la base de datos
     * @param string $condicion (opcional)
     * @return array|null colección de responsables o null si no hay alguno
     */
    public function listar($condicion = ""){
        $base = new BaseDatos();
        $arreglo = null;

        $consulta = "SELECT * FROM responsable";
        if($condicion != ""){
            $consulta .= " WHERE " . $condicion;
        }

        // Iniciamos la base de datos
        if($base->Iniciar()){
            // Ejecutamos la consulta
            if($base->Ejecutar($consulta)){
                $arreglo = [];
                // Registro() retorna los datos de la fila y mueve el puntero a la siguiente.
                // Con while se recorre todo.
                while($fila = $base->Registro()){
                    $objResponsableV = new ResponsableV();
                    $objResponsableV->setNumEmpleado($fila['rnumeroempleado']);
                    $objResponsableV->setNumLicencia($fila['rnumerolicencia']);
                    $objResponsableV->setNombre($fila['rnombre']);
                    $objResponsableV->setApellido($fila['rapellido']);
                    array_push($arreglo, $objResponsableV);
                }
            }else{
                $this->setMensajeOperacion($base->getError());
            }
        }else{
            $this->setMensajeOperacion($base->getError());
        }

        return $arreglo;
    }


    /**
     * Inserta los datos del objeto ResponsableV actual a la base de datos.
     * @return boolean true si se concretó, false caso contrario
     */
    public function insertar(){
        $base = new BaseDatos();
        $seConcreto = false;

        // Armamos la consulta
        $consulta = "INSERT INTO responsable(rnumerolicencia, rnombre, rapellido)
        VALUES ('". $this->getNumLicencia()."','".$this->getNombre()."','".$this->getApellido()."');";

        // Iniciamos la base de datos
        if($base->Iniciar()){
            // Ejecutamos la consulta
            $id = $base->devuelveIDInsercion($consulta);
            if(!is_null($id)){
                $this->setNumEmpleado($id);
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
     * Modifica los datos del responsable, colocando los del objeto actual
     * @return boolean true si se concretó, false caso contrario
     */
    public function modificar(){
        $base = new BaseDatos();
        $seConcreto = false;
        $consulta = "UPDATE responsable SET rnumerolicencia = '" . $this->getNumLicencia() . "', rnombre = '" . $this->getNombre() .
        "', rapellido = '" . $this->getApellido() . "' WHERE rnumeroempleado = '" . $this->getNumEmpleado(). "'";

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
        $consulta = "DELETE FROM responsable WHERE  rnumeroempleado = '" . $this->getNumEmpleado() ."'";

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
     * Muestra los atributos del objeto como string
     */
    public function __toString()
    {
        return
        "\nNúmero de empleado: " . $this->getNumEmpleado().
        "\nNúmero de licencia: " . $this->getNumLicencia().
        "\nNombre: " . $this->getNombre().
        "\nApellido: " . $this->getApellido();
    }
}
?>