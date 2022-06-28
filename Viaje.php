<?php

class Viaje{
    private $id;
    private $destino;
    private $cantMaxPasajeros;
    private $importe;
    private $tipoAsiento;
    private $idaYVuelta;
    private $objEmpresa;
    private $objResponsableV;
    private $colecObjPasajeros;
    private $mensajeOperacion;

    // ---------------------------------------------------
    //                   CONSTRUCTOR                 
    // ---------------------------------------------------

    /**
     * Método constructor de la clase Viaje
     */
    public function __construct(){
        $this->id = null;
        $this->destino = "";
        $this->cantMaxPasajeros = 0;
        $this->importe = 0;
        $this->tipoAsiento = "";
        $this->idaYVuelta = "";
        $this->objEmpresa = new Empresa();
        $this->objResponsableV = new ResponsableV();
        $this->colecObjPasajeros = [];
        $this->mensajeOperacion = "";
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
    public function getDestino(){
        return $this->destino;
    }
    public function setDestino($destino){
        $this->destino = $destino;
    }
    public function getCantMaxPasajeros(){
        return $this->cantMaxPasajeros;
    }
    public function setCantMaxPasajeros($cantMaxPasajeros){
        $this->cantMaxPasajeros = $cantMaxPasajeros;
    }
    public function getImporte(){
        return $this->importe;
    }
    public function setImporte($importe){
        $this->importe = $importe;
    }
    public function getTipoAsiento(){
        return $this->tipoAsiento;
    }
    public function setTipoAsiento($tipoAsiento){
        $this->tipoAsiento = $tipoAsiento;
    }
    public function getIdaYVuelta(){
        return $this->idaYVuelta;
    }
    public function setIdaYVuelta($idaYVuelta){
        $this->idaYVuelta = $idaYVuelta;
    }
    public function getObjResponsableV(){
        return $this->objResponsableV;
    }
    public function setObjResponsableV($objResponsableV){
        $this->objResponsableV = $objResponsableV;
    }
    public function getObjEmpresa(){
        return $this->objEmpresa;
    }
    public function setObjEmpresa($objEmpresa){
        $this->objEmpresa = $objEmpresa;
    }
    public function getColecObjPasajeros(){
        return $this->colecObjPasajeros;
    }
    public function setColecObjPasajeros($colecObjPasajeros){
        $this->colecObjPasajeros = $colecObjPasajeros;
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
     * Carga datos a la clase viaje
     * @param string $destino
     * @param int $cantMaxPasajeros
     * @param float $importe
     * @param string $tipoAsiento
     * @param string $idaYVuelta
     * @param object $objEmpresa
     * @param object $objResponsableV
     */
    public function cargar($destino, $cantMaxPasajeros, $importe, $tipoAsiento, $idaYVuelta, $objResponsableV, $objEmpresa){
        $this->setDestino($destino);
        $this->setCantMaxPasajeros($cantMaxPasajeros);
        $this->setImporte($importe);
        $this->setTipoAsiento($tipoAsiento);
        $this->setIdaYVuelta($idaYVuelta);
        $this->setObjResponsableV($objResponsableV);
        $this->setObjEmpresa($objEmpresa);
    }

    /**
     * Muestra los datos del viaje sin pasajeros
     * @return string
     */
    public function mostrarDatos(){
        return
        "\n--------------------\nNumero de viaje: " . $this->getId(). "\n--------------------".
        "\nDestino: " . $this->getDestino().
        "\nImporte: " . $this->getImporte().
        "\nTipo de asiento: " . $this->getTipoAsiento().
        "\nIda y vuelta: ". $this->getIdaYVuelta() . 
        "\nCantidad máxima de pasajeros: " . $this->getCantMaxPasajeros().
        "\nCantidad de pasajeros: " . count($this->getColecObjPasajeros()). "\n--------------------" .
        "\nEmpresa:\n--------------------" . $this->getObjEmpresa()->mostrarDatos() . "\n--------------------" .
        "\nResponsable:\n--------------------" . $this->getObjResponsableV();
    }

    /**
     * Revisa si hay pasajes disponibles
     * @return boolean
     */
    public function hayPasajesDisponibles(){
        $cantPasajeros = count($this->getColecObjPasajeros());
        $hayDisponible = false;

        if($cantPasajeros < $this->getCantMaxPasajeros()){
            $hayDisponible = true;
        }

        return $hayDisponible;
    }

    /**
     * Carga datos a la clase viaje
     * @param string $destino -1 lo deja igual
     * @param int $cantMaxPasajeros cualquier numero menor o igual a 0 lo deja igual
     * @param float $importe cualquier numero menor o igual a 0 lo deja igual
     * @param string $tipoAsiento -1 lo deja igual
     * @param string $idaYVuelta -1 lo deja igual
     */
    public function modificarDatos($destino, $cantMaxPasajeros, $importe, $tipoAsiento, $idaYVuelta){
        if($destino != "-1"){
            $this->setDestino($destino);
        }
        if($cantMaxPasajeros > 0){
            $this->setCantMaxPasajeros($cantMaxPasajeros);
        }
        if($importe > 0){
            $this->setImporte($importe);
        }
        if($tipoAsiento != "-1"){
            $this->setTipoAsiento($tipoAsiento);
        }
        if($idaYVuelta != "-1"){
            $this->setIdaYVuelta($idaYVuelta);
        }
        $this->modificar();
    }

    /**
     * Vende un pasaje agregando al pasajero a la base de datos
     * @param object $objPasajero
     * @return float total a pagar
     */
    public function venderPasaje($objPasajero){
        $importe = $this->getImporte();
        if($this->getTipoAsiento() == "cama"){
            $importe = $importe * 1.25;
        }
        if($this->getIdaYVuelta() == "si"){
            $importe = $importe * 1.50;
        }
        $objPasajero->insertar();
        return $importe;
    }

    // ---------------------------------------------------
    //     METODOS DE INTERACCION CON LA BASE DE DATOS                 
    // ---------------------------------------------------

    /**
     * Busca un viaje en la base de datos y le coloca sus datos al objeto actual
     * @param $idViaje
     * @return boolean true si encontró, false caso contrario
     */
    public function buscarViaje($idViaje){
        $base = new BaseDatos();
        $encontro = false;

        // Se arma la consulta
        $consulta = "SELECT * FROM viaje WHERE idviaje = " . $idViaje;

        // Se inicia la base de datos
        if($base->Iniciar()){
            // Se ejecuta la consulta
            if($base->Ejecutar($consulta)){
                if($fila = $base->Registro()){
                    $encontro = true;
                    // Asignación de datos
                    $this->setId($idViaje);
                    $this->setDestino($fila['vdestino']);
                    $this->setCantMaxPasajeros($fila['vcantmaxpasajeros']);
                    $this->setImporte($fila['vimporte']);
                    $this->setTipoAsiento($fila['tipoAsiento']);
                    $this->setIdaYVuelta($fila['idayvuelta']);

                    // Asignación de objetos (delegación)
                    $objResponsableV = new ResponsableV();
                    $objResponsableV->buscarResponsable($fila['rnumeroempleado']);
                    $this->setObjResponsableV($objResponsableV);

                    $objEmpresa = new Empresa();
                    $objEmpresa->buscarEmpresa($fila['idempresa']);
                    $this->setObjEmpresa($objEmpresa);

                    // Asignación de la colección de objetos Pasajero
                    $objPasajero = new Pasajero();
                    $colecObjPasajeros = $objPasajero->listar("idviaje = ". $this->getId());
                    $this->setColecObjPasajeros($colecObjPasajeros);
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
     * Lista todos los viajes ó algunos si se agrega condición
     * @param string $condicion (opcional)
     * @return array|null null si no hay viajes
     */
    public function listar($condicion = ""){
        $base = new BaseDatos();
        $arreglo = null;

        $consulta = "SELECT * FROM viaje";
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
                    $objViaje = new Viaje();
                    // Datos
                    $objViaje->setId($fila['idviaje']);
                    $objViaje->setDestino($fila['vdestino']);
                    $objViaje->setCantMaxPasajeros($fila['vcantmaxpasajeros']);
                    $objViaje->setImporte($fila['vimporte']);
                    $objViaje->setTipoAsiento($fila['tipoAsiento']);
                    $objViaje->setIdaYVuelta($fila['idayvuelta']);

                    // Asignación de objetos (delegación)
                    $objResponsableV = new ResponsableV();
                    $objResponsableV->buscarResponsable($fila['rnumeroempleado']);
                    $objViaje->setObjResponsableV($objResponsableV);

                    // Empresa
                    $objEmpresa = new Empresa();
                    $objEmpresa->buscarEmpresa($fila['idempresa']);
                    $objViaje->setObjEmpresa($objEmpresa);

                    // Arreglos
                    $objPasajero = new Pasajero();
                    $colecPasajeros = $objPasajero->listar("idviaje = " . $objViaje->getId());
                    $objViaje->setColecObjPasajeros($colecPasajeros);
                    array_push($arreglo, $objViaje);
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
     * Selecciona los destinos
     * @return array arreglo con todos los destinos
     */
    public function retornarDestinos(){
        $base = new BaseDatos();
        $consulta = "SELECT * FROM viaje";
        $arreglo = null;
        $contador = 0;
        $encontro = false;

        if($base->Iniciar()){
            if($base->Ejecutar($consulta)){
                $arreglo = [];
                while($fila = $base->Registro()){
                    while($contador < count($arreglo) && !$encontro){
                        if($arreglo[$contador] == $fila['vdestino']){
                            $encontro = true;
                        }
                        $contador++;
                    }
                    if(!$encontro){
                        array_push($arreglo, $fila['vdestino']);
                    }
                    $contador = 0;
                    $encontro = false;
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
     * Inserta los datos del objeto viaje actual a la base de datos.
     * @return boolean true si se concretó, false caso contrario
     */
    public function insertar(){
        $base = new BaseDatos();
        $seConcreto = false;

        // Armamos la consulta
        $consulta = "INSERT INTO viaje(vdestino, vcantmaxpasajeros, rnumeroempleado, idempresa, vimporte, tipoAsiento, idayvuelta)
        VALUES ('".
        $this->getDestino()."',".
        $this->getCantMaxPasajeros().",".
        $this->getObjResponsableV()->getNumEmpleado() . ",".
        $this->getObjEmpresa()->getId() . ",".
        $this->getImporte() . ",'".
        $this->getTipoAsiento() . "','".
        $this->getIdaYVuelta()."');";

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
     * Modifica los datos del viaje, colocando los del objeto actual
     * @return boolean true si se concretó, false caso contrario
     */
    public function modificar(){
        $base = new BaseDatos();
        $seConcreto = false;
        $consulta = "UPDATE viaje SET 
        vdestino = '" . $this->getDestino() . "',".
        "vcantmaxpasajeros = " . $this->getCantMaxPasajeros(). ",".
        "idempresa = " . $this->getObjEmpresa()->getId(). ",".
        "rnumeroempleado = " . $this->getObjResponsableV()->getNumEmpleado(). ",".
        "vimporte = " . $this->getImporte() . ",".
        "tipoAsiento = '". $this->getTipoAsiento() . "',".
        "idayvuelta = '" . $this->getIdaYVuelta() ."'" .
        " WHERE idviaje = " . $this->getId();

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
     * Elimina el viaje
     * @param boolean true si se completó, false caso contrario
     */
    public function eliminar(){
        // Antes de eliminar el viaje se tienen que eliminar sus pasajeros
        $objPasajero = new Pasajero();
        $objPasajero->eliminarPasajerosViaje($this->getId());

        // Ahora si se puede eliminar el viaje
        $base = new BaseDatos();
        $consulta = "DELETE FROM viaje WHERE idviaje = " . $this->getId();
        $completado = false;
        

        if($base->Iniciar()){
            if($base->Ejecutar($consulta)){
                $completado = true;
            }else{
                $this->setMensajeOperacion($base->getError());
            }
        }else{
            $this->setMensajeOperacion($base->getError());
        }
        return $completado;
    }

    /**
     * Cambia el responsable de todos los viajes que tiene el responsable actual al un nuevo responsable
     * @param object $objResponsableV objeto de la clase ResponsableV
     * @return boolean true si se completó, false caso contrario
     */
    public function cambiarResponsable($objResponsableV){
        $base = new BaseDatos();
        $consulta = "UPDATE viaje SET rnumeroempleado = " . $objResponsableV->getNumEmpleado() . " WHERE rnumeroempleado = " . $this->getObjResponsableV()->getNumEmpleado();
        $completado = false;

        if($base->Iniciar()){
            if($base->Ejecutar($consulta)){
                $this->setObjResponsableV($objResponsableV);
                $completado = true;
            }else{
                $this->setMensajeOperacion($base->getError());
            }
        }else{
            $this->setMensajeOperacion($base->getError());
        }
        return $completado;
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
    public function __toString(){
        return
        "\nNumero de viaje: " . $this->getId().
        "\nDestino: " . $this->getDestino().
        "\nImporte: " . $this->getImporte().
        "\nTipo de asiento: " . $this->getTipoAsiento().
        "\nIda y vuelta: ". $this->getIdaYVuelta() . "\n--------------------" .
        "\nEmpresa:\n--------------------" . $this->getObjEmpresa()->mostrarDatos(). "\n--------------------" .
        "\nResponsable:\n--------------------" . $this->getObjResponsableV() . "\n--------------------" .
        "\nPasajeros: " . $this->convertirAString($this->getColecObjPasajeros()). "\n--------------------" .
        "\nCantidad máxima de pasajeros: " . $this->getCantMaxPasajeros().
        "\nCantidad de pasajeros: " . count($this->getColecObjPasajeros());
    }
}
?>