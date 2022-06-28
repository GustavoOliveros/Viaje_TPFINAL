<?php

class Pasajero{
    private $nombre;
    private $apellido;
    private $numDoc;
    private $telefono;
    private $objViaje;
    private $mensajeOperacion;

    // ---------------------------------------------------
    //                   CONSTRUCTOR                 
    // ---------------------------------------------------

    /**
     * Método constructor
     */
    public function __construct()
    {
        $this->nombre = "";
        $this->apellido = "";
        $this->numDoc = "";
        $this->telefono = 0;
        $this->objViaje = new Viaje();
    }

    // ---------------------------------------------------
    //                METODOS DE ACCESO              
    // ---------------------------------------------------

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
    public function getNumDoc()
    {
        return $this->numDoc;
    }
    public function setNumDoc($nuevoNumDoc)
    {
        $this->numDoc = $nuevoNumDoc;
    }
    public function getTelefono()
    {
        return $this->telefono;
    }
    public function setTelefono($nuevoTelefono)
    {
        $this->telefono = $nuevoTelefono;
    }
    public function getObjViaje(){
        return $this->objViaje;
    }
    public function setObjViaje($objViaje){
        $this->objViaje = $objViaje;
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
     * @param string $apellido
     * @param string $numDoc
     * @param int $telefono
     * @param object $objViaje
     */
    public function cargar($nombre, $apellido, $numDoc, $telefono, $objViaje){
        $this->setNombre($nombre);
        $this->setApellido($apellido);
        $this->setNumDoc($numDoc);
        $this->setTelefono($telefono);
        $this->setObjViaje($objViaje);
    }

    /**
     * Modifica los datos de un pasajero
     * @param string $nombre todo numero menor o igual a 0 lo deja igual
     * @param string $apellido todo numero menor o igual a 0 lo deja igual
     * @param int $telefono todo numero menor o igual a 0 lo deja igual
     */
    public function modificarDatos($nombre, $apellido, $telefono){
        if($nombre > 0){
            $this->setNombre($nombre);
        }
        if($apellido > 0){
            $this->setApellido($apellido);
        }
        if($telefono > 0){
            $this->setTelefono($telefono);
        }
        $this->modificar();
    }

    /**
     * Muestra los datos del objeto
     * @return string
     */
    public function mostrarDatos(){
        $cadena =
        "\nNombre: " . $this->getNombre().
        "\nApellido: " . $this->getApellido().
        "\nNumero de documento: " . $this->getNumDoc().
        "\nNumero de teléfono: " . $this->getTelefono();
        if($this->getObjViaje()->getId() != null){
            $cadena .= "\nNumero de viaje: " . $this->getObjViaje()->getId();
        }
        return $cadena;
    }

    // ---------------------------------------------------
    //     METODOS DE INTERACCION CON LA BASE DE DATOS                 
    // ---------------------------------------------------

    /**
     * Recupera los datos de un pasajero por número de documento
     * @param int $numDoc
     * @return boolean - true si encontró, false caso contrario
     */
    public function buscarPasajero($numDoc){
        $base = new BaseDatos();
        $consulta = "SELECT * FROM pasajero WHERE rdocumento = " . $numDoc;
        $seConcreto = false;

        // Iniciamos la bd
        if($base->Iniciar()){
            // Ejecutamos la consulta
            if($base->Ejecutar($consulta)){
                // Asignamos los datos a $fila siempre y cuando hayan datos (Registro() retorne true)
                if($fila = $base->Registro()){
                    $this->setNumDoc($numDoc);
                    $this->setNombre($fila['pnombre']);
                    $this->setApellido($fila['papellido']);
                    $this->setTelefono($fila['ptelefono']);
                    $objViaje = new Viaje();
                    $objViaje->buscarViaje($fila['idviaje']);
                    $this->setObjViaje($objViaje);
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
     * Lista todos los pasajeros de la base de datos (o algunos si se le agrega una condición)
     * @param string $condicion (opcional)
     * @return array Arreglo de objetos de clase Pasajero
     */
    public function listar($condicion = ""){
        $base = new BaseDatos();
        $arreglo = null;

        // Construcción de la consulta
        $consulta = "SELECT * FROM pasajero";
        if($condicion != ""){
            $consulta .= " WHERE ". $condicion;
        }
        $consulta .= " ORDER BY papellido";

        // Iniciamos la base de datos
        if($base->Iniciar()){
            // Ejecutamos la consulta
            if($base->Ejecutar($consulta)){
                $arreglo = [];

                // Registro() retorna los datos de la fila y mueve el puntero a la siguiente.
                // Con while se recorre todo.
                while($fila = $base->Registro()){
                    // Datos
                    $objPasajero = new Pasajero();
                    $objPasajero->setNumDoc($fila['rdocumento']);
                    $objPasajero->setNombre($fila['pnombre']);
                    $objPasajero->setApellido($fila['papellido']);
                    $objPasajero->setTelefono($fila['ptelefono']);
                    array_push($arreglo, $objPasajero);
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
     * Inserta los datos del objeto pasajero actual a la base de datos.
     * @return boolean true si se concretó, false caso contrario
     */
    public function insertar(){
        $base = new BaseDatos();
        $seConcreto = false;

        // Armamos la consulta
        $consulta = "INSERT INTO pasajero(rdocumento, pnombre, papellido, ptelefono, idviaje)
        VALUES ('".$this->getNumDoc()."','". $this->getNombre()."','".
        $this->getApellido()."',".$this->getTelefono() . ",". $this->getObjViaje()->getId() . ");";

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
     * Modifica los datos de algún pasajero en la base de datos y le coloca los del objeto actual
     * @return boolean true si se concretó, false caso contrario
     */
    public function modificar(){
        $base = new BaseDatos();
        $seConcreto = false;
        $consulta = "UPDATE pasajero SET pnombre = '" . $this->getNombre() . "', papellido = '" . $this->getApellido() .
        "', ptelefono = " . $this->getTelefono() . " WHERE rdocumento = '" . $this->getNumDoc(). "'";

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
        $consulta = "DELETE FROM pasajero WHERE rdocumento = '" . $this->getNumDoc() ."'";

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
     * Elimina todos los pasajeros de un viaje
     * @param int $idViaje
     */
    public function eliminarPasajerosViaje($idViaje){
        $base = new BaseDatos();
        $seConcreto = false;
        $consulta = "DELETE FROM pasajero WHERE idviaje = '" . $idViaje ."'";

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
     * Método toString para mostrar el objeto en string
     */
    public function __toString()
    {
        return
        "\nNombre: " . $this->getNombre().
        "\nApellido: " . $this->getApellido().
        "\nNúmero de documento: " . $this->getNumDoc().
        "\nNúmero de teléfono: " . $this->getTelefono(). "\n--------------------".
        "\nViaje al que pertenece:\n--------------------" . $this->getObjViaje()->mostrarDatos();
    }
}
?>