<?php

// ---------------------------------------------------
//                INCLUSIÓN DE CLASES                 
// ---------------------------------------------------

include_once 'Viaje.php';
include_once "Pasajero.php";
include_once "ResponsableV.php";
include_once "Empresa.php";
include_once "BaseDatos.php";

// ---------------------------------------------------
//                     FUNCIONES                    
// ---------------------------------------------------

/**
 * Pide un número y se asegura de que se encuentre en un rango. Retorna un número válido
 * @param int $numMenor
 * @param int $numMayor
 * @return int
 */
function rango($numMenor, $numMayor){
    $contador = 0;
    do{
        if($contador != 0){
            echo "Ingrese un número entre " . $numMenor . " y " . $numMayor . ": ";
        }
        $respuesta = trim(fgets(STDIN));
        $contador++;
    }while(!is_numeric($respuesta) || ($respuesta > $numMayor || $respuesta < $numMenor));
    return $respuesta;
}

/**
 * Pide un arreglo con datos y se asegura que el usuario ingrese algo de ese arreglo
 * @param array $arreglo
 * @return string|null la elección del usuario|null si el arreglo esta vacio
 */
function entre($arreglo){
    $bandera = false;
    $respuesta = null;
    $contador = 0;
    do{
        if($contador != 0){
            echo "+Ingresa alguna de estas opciones: \n";
            foreach($arreglo as $unDato){
                echo "- ". $unDato . "\n";
            }
            echo ">";
        }
        $respuesta = strtolower(trim(fgets(STDIN)));
        foreach($arreglo as $unDato){
            if($unDato == $respuesta){
                $bandera = true;
            }
        }
        $contador++;
    }while(!$bandera);

    return $respuesta;
}

/**
 * Genera una línea de asteriscos
 * @return string
 */
function linea(){
    return "\n*****************************************";
}

/**
 * Convierte a un arreglo en string
 * @param array $arreglo arreglo
 * @return string
 */
function convertirAString($arreglo){
    $cadena = "";
    foreach($arreglo as $unObjeto){
        $cadena .= "\n--------------------".
        $unObjeto;
    }
    return $cadena;
}

/**
 * Convierte a un arreglo con objetos (con métodos mostrarDatos()) en string
 * @param array $arreglo arreglo con objetos pasajeros, responsablev ó viaje
 * @return string
 */
function mostrarDatos($arreglo){
    $cadena = "";
    foreach($arreglo as $unObjeto){
        $cadena .= "\n--------------------".
        $unObjeto->mostrarDatos();
    }
    return $cadena;
}

// ---------------------------------------------------
//                      MENUS                   
// ---------------------------------------------------

/**
 * Genera el menú
 * @return int
 */
function menu(){
    echo
        "\n
        MENU PRINCIPAL
        -------EMPRESA-------
        1) Ver datos de la empresa
        2) Modificar datos de la empresa
        -------VIAJE-------
        3) Agregar un viaje
        4) Seleccionar un viaje (...)
        -------RESPONSABLE-------
        5) Buscar responsable
        6) Agregar un responsable
        7) Modificar un responsable
        8) Eliminar un responsable
        -------PASAJERO-------
        9) Buscar pasajero
        10) Modificar pasajero
        11) Eliminar pasajero
        ----------------------------------
        12) Salir
        >>>>>>>>>";
    $respuesta = rango(1, 12);
    return $respuesta;
}

/**
 * Menú para los viajes
 */
function menuViajes(){
    echo 
    "\n
    MENU - VIAJE
    -------VIAJE-------
    1) Ver datos del viaje
    2) Modificar datos del viaje
    3) Eliminar viaje
    -------PASAJERO-------
    4) Ver todos los pasajeros
    5) Vender pasaje - Agregar pasajero
    -------RESPONSABLE-------
    6) Cambiar responsable
    ----------------------------------
    7) Salir al menú principal
    >>>>>>>>>";
    $respuesta = rango(1, 7);
    return $respuesta;
}


// ---------------------------------------------------
//               CREACIÓN DE OBJETOS                      
// ---------------------------------------------------

$objEmpresa = new Empresa();
$objPasajero = new Pasajero();
$objViaje = new Viaje();
$objNuevoResponsableV = new ResponsableV();
$objResponsableV = new ResponsableV();

// ---------------------------------------------------
//                PROGRAMA PRINCIPAL                       
// ---------------------------------------------------

// Si no hay una empresa, te exige crearla.
if(!$objEmpresa->buscarEmpresa(1)){
    echo "\n+Antes de iniciar, debe agregar los datos de la empresa.";
    echo "\n+Ingrese el nombre de la empresa: \n>";
    $nombre = trim(fgets(STDIN));
    echo "+Ingrese la dirección de la empresa: \n>";
    $direccion = trim(fgets(STDIN));
    $objEmpresa->setNombre($nombre);
    $objEmpresa->setDireccion($direccion);
    $objEmpresa->insertar();
    echo "++++INSERCIÓN EXITOSA";
}

do{
    $respuesta = menu();
    switch($respuesta){
        case 1:
            echo "\n\n+++++VER DATOS DE LA EMPRESA";
            echo linea();
            echo $objEmpresa->mostrarDatos();
            echo linea();

            break;
        case 2:
            echo "\n\nMODIFICAR DATOS DE LA EMPRESA";
            echo "\n+Ingrese el nuevo nombre de la empresa: (Actual: ". $objEmpresa->getNombre() .") - '-1' para dejarlo igual \n>";
            $nombre = trim(fgets(STDIN));
            echo "+Ingrese la nueva dirección de la empresa (Actual: ". $objEmpresa->getDireccion() .") - '-1' para dejarlo igual \n>";
            $direccion = trim(fgets(STDIN));
            $objEmpresa->modificarDatos($nombre, $direccion);
            echo "++++MODIFICACIÓN EXITOSA";
            break;
        case 3:
            echo "\n\n+++++AGREGAR UN VIAJE";
            // Datos
            echo "\n+Ingrese el destino del viaje: \n>";
            $objViaje->setDestino(strtolower(trim(fgets(STDIN))));
            echo "+Ingrese la cantidad máxima de pasajeros: \n>";
            $objViaje->setCantMaxPasajeros(rango(1, 300));
            echo "+Ingrese el importe del viaje: \n>";
            $objViaje->setImporte(rango(1, 100000000));
            echo "+Ingrese el tipo de asiento (semicama/cama)\n>";
            $objViaje->setTipoAsiento(entre(["semicama", "cama"]));
            echo "+¿El viaje es ida y vuelta? (si/no)\n>";
            $objViaje->setIdaYVuelta(entre(["si", "no"]));
            // Empresa - Hay una única empresa.
            $objEmpresa->buscarEmpresa(1);
            $objViaje->setObjEmpresa($objEmpresa);
            // Responsable
            $encontro = false;
            do{
                echo "+Ingrese el número de licencia del responsable del viaje (-1 para salir al menú principal):\n>";
                $respuestaR = rango(-1, 1000000000);
    
                if($respuestaR != -1){
                    if($objResponsableV->buscarResponsablePorLicencia($respuestaR)){
                        $encontro = true;
                        $objViaje->setObjResponsableV($objResponsableV);
                    }else{
                        echo "+El responsable ingresado no existe. Puede registrarlo en la opción 6 del menú principal.\n";
                    }
                }
            }while($respuestaR != -1 && !$encontro);
            if($respuestaR != -1){
                // Inserción
                $objViaje->insertar();
                // Los pasajeros se agregan al seleccionar un viaje
                echo "+Para agregar pasajeros, seleccione el viaje número ". $objViaje->getId(). " en la opción 4 del menú principal.\n";
                echo "++++INSERCIÓN EXITOSA";
            }
            break;
        case 4:
            echo "\n\n+++++SELECCIONAR UN VIAJE";
            // Seleccionar un viaje (abre un menú de opciones para modificar el viaje)
            // Seleccion de destino
            echo "\n+Ingrese el destino del viaje (* para mostrarlos todos): \n>";
            $destinosDisp = $objViaje->retornarDestinos();
            array_push($destinosDisp, "*");
            $destino = entre($destinosDisp);
            if($destino != "*"){
                $colecViajes = $objViaje->listar("vdestino = '" . $destino . "'");
            }else{
                $colecViajes = $objViaje->listar();
            }
            // Muestra en pantalla los viajes a ese destino (o todos)
            if(count($colecViajes) > 0){
                echo mostrarDatos($colecViajes);
                echo linea();
                echo "\nCantidad de viajes: " . count($colecViajes) . "\n";
            }else{
                echo "+No hay viajes.";
            }
            // Selección de algunos de los viajes
            echo "\n+Ingrese el número de viaje de alguno de los viajes mostrados: (* para salir) \n>";

            $respuesta = trim(fgets(STDIN));
            if(is_numeric($respuesta)){
                if($objViaje->buscarViaje($respuesta)){
                    echo linea();
                    echo "\nViaje seleccionado: ";
                    echo linea();
                    echo $objViaje->mostrarDatos();
                    $banderaEliminacion = false;

                    // Menú para viajes
                    do{
                        $respuesta = menuViajes();
                        switch($respuesta){
                            case 1:
                                echo "\n\n+++++VER DATOS DEL VIAJE";
                                linea();
                                echo $objViaje->mostrarDatos();
                                linea();
                                break;
                            case 2:
                                echo "\n\n+++++MODIFICAR DATOS DEL VIAJE";
                                echo "\n+Ingrese el destino del viaje (Actual: ". $objViaje->getDestino(). ")  - '-1' para dejarlo igual\n>";
                                $destino = strtolower(trim(fgets(STDIN)));
                                echo "+Ingrese la cantidad máxima de pasajeros: (Actual: ". $objViaje->getCantMaxPasajeros(). ")\n>";
                                $cantMaxPsj = rango(count($objViaje->getColecObjPasajeros()), 300);
                                echo "+Ingrese el importe del viaje: (Actual: ". $objViaje->getImporte(). ") - '-1' para dejarlo igual\n>";
                                $importe = rango(-1, 100000000);
                                echo "+Ingrese el tipo de asiento (semicama/cama) (Actual: ". $objViaje->getTipoAsiento(). ") - '-1' para dejarlo igual\n>";
                                $tipoAsiento = entre(["semicama", "cama", "-1"]);
                                echo "+¿El viaje es ida y vuelta? (si/no) (Actual: ". $objViaje->getIdaYVuelta(). ")  - '-1' para dejarlo igual\n>";
                                $idaYVuelta = entre(["si", "no", "-1"]);

                                $objViaje->modificarDatos($destino, $cantMaxPsj, $importe, $tipoAsiento, $idaYVuelta);
                                echo "++++MODIFICACIÓN COMPLETADA";
                                break;
                            case 3:
                                echo "\n\n+++++ELIMINAR VIAJE";
                                echo "\n+¿Seguro que desea eliminar el viaje? (si/no): \n>";
                                $respuesta = entre(["si", "no"]);
                                if($respuesta == "si"){
                                    $objViaje->eliminar();
                                    echo "++++ELIMINADO.";
                                    $banderaEliminacion = true;
                                }
                                break;
                            case 4:
                                echo "\n\n+++++VER TODOS LOS PASAJEROS";
                                $colecPasajeros = $objPasajero->listar("idviaje = " . $objViaje->getId());
                                if(count($colecPasajeros) > 0){
                                    echo mostrarDatos($colecPasajeros);
                                }else{
                                    echo "\nNo hay pasajeros en este viaje. Use la opción 5 del menú viaje para agregar.";
                                }
                                break;
                            case 5:
                                // +++++VENDER PASAJE/ AGREGAR PASAJEROS
                                $objViaje->setColecObjPasajeros($objPasajero->listar("idviaje = " . $objViaje->getId()));
                                if($objViaje->hayPasajesDisponibles()){
                                    echo "\n\n+++++VENDER PASAJE/ AGREGAR PASAJEROS";
                                    echo "\n+Ingrese el número de documento del pasajero: \n>";
                                    $respuesta = rango(1, 1000000000);
                                    if(!$objPasajero->buscarPasajero($respuesta)){
                                        $objPasajero->setNumDoc($respuesta);
                                        echo "+Ingrese el nombre del pasajero: \n>";
                                        $objPasajero->setNombre(trim(fgets(STDIN)));
                                        echo "+Ingrese el apellido del pasajero: \n>";
                                        $objPasajero->setApellido(trim(fgets(STDIN)));
                                        echo "+Ingrese el numero de telefono del pasajero: \n>";
                                        $objPasajero->setTelefono(rango(1, 99999999999));
                                        $objPasajero->setObjViaje($objViaje);

                                        echo linea();
                                        echo $objPasajero->mostrarDatos();
                                        echo linea();

                                        $importe = $objViaje->venderPasaje($objPasajero);
                                        echo "\n+Total a pagar: " . $importe;
                                        echo "\n++++INSERCION EXITOSA";
                                    }else{
                                        echo "+El pasajero con el documento nro " . $objPasajero->getNumDoc() . " ya está registrado en el viaje nro ". $objPasajero->getObjViaje()->getId();
                                        echo "\n+Puede eliminarlo en el menu principal.";
                                    }
                                }else{
                                    echo "No quedan puestos disponibles.";
                                }
                                break;
                            case 6:
                                echo "\n\n+++++CAMBIAR RESPONSABLE";
                                echo "\n+Ingrese el número de licencia: \n>";
                                $respuesta = rango(1, 1000000000);
                                if($objResponsableV->buscarResponsablePorLicencia($respuesta)){
                                    echo linea();
                                    echo $objResponsableV;
                                    echo linea();
                                    $objViaje->setObjResponsableV($objResponsableV);
                                    $objViaje->modificar();
                                    echo "\n++++MODIFICACION EXITOSA";
                                }else{
                                    echo "El responsable ingresado no fue encontrado. Puede registrarlo en la opción 6 del menú principal.";
                                }
                                break;
                        }
                    }while($respuesta != 7 && !$banderaEliminacion);
                }else{
                    echo "+El viaje ingresado no existe.";
                }
            }
            break;
        case 5:
            echo "\n\n+++++BUSCAR RESPONSABLE";
            echo "\n+Ingrese el número de licencia (-1 para verlos todos): \n>";
            $respuesta = rango(-1, 1000000000);
            if($respuesta != -1){
                if($objResponsableV->buscarResponsablePorLicencia($respuesta)){
                    echo linea();
                    echo $objResponsableV;
                    echo linea();
                }else{
                    echo "+El responsable ingresado no fue encontrado. Puede registrarlo en la opción 6 del menú principal.";
                }
            }else{
                $colecResponsables = $objResponsableV->listar();
                if(count($colecResponsables) > 0){
                    echo convertirAString($colecResponsables);
                }else{
                    echo "+No hay responsables registrados. Registre alguno en la opción 6 del menú principal.";
                }
            }
            break;
        case 6:
            echo "\n\n+++++AGREGAR UN RESPONSABLE";
            // Como la base de datos si permite licencias repetidas, se controla en el script
            echo "\n+Ingrese el número de licencia: \n>";
            $licencia = rango(1, 1000000000);
            if(!$objResponsableV->buscarResponsablePorLicencia($licencia)){
                $objResponsableV->setNumLicencia($licencia);
                echo "+Ingrese el nombre: \n>";
                $objResponsableV->setNombre(trim(fgets(STDIN)));
                echo "+Ingrese el apellido: \n>";
                $objResponsableV->setApellido(trim(fgets(STDIN)));
                $objResponsableV->insertar();
                echo linea();
                echo $objResponsableV;
                echo linea();
                echo "\n++++INSERCIÓN EXITOSA";
            }else{
                echo "+El responsable con la licencia nro " . $licencia . " ya está registrado.";
            }
            break;
        case 7:
            echo "\n\n++++MODIFICAR RESPONSABLE";
            echo "\n+Ingrese el número de licencia del responsable: \n>";
            $respuesta = rango(1, 1000000000);
            if($objResponsableV->buscarResponsablePorLicencia($respuesta)){
                echo "+Ingrese el nuevo nombre del responsable: (Actual: " . $objResponsableV->getNombre() . ") - '-1' para dejarlo igual\n>";
                $nombre = trim(fgets(STDIN));
                echo "+Ingrese el nuevo apellido del responsable: (Actual: " . $objResponsableV->getApellido() . ") - '-1' para dejarlo igual\n>";
                $apellido = trim(fgets(STDIN));
                $objResponsableV->modificarDatos($nombre, $apellido);
                echo "++++MODIFICACIÓN EXITOSA";
            }else{
                echo "+El responsable no fue encontrado.";
            }
            break;
        case 8:
            // +++++ELIMINAR RESPONSABLE
            $colecResponsables = $objResponsableV->listar();
            if(count($colecResponsables) > 1){
                echo "\n\n+++++ELIMINAR RESPONSABLE";
                echo "\n+Ingrese el número de licencia: \n>";
                $respuesta = rango(1, 1000000000);
                if($objResponsableV->buscarResponsablePorLicencia($respuesta)){
                    echo linea();
                    echo $objResponsableV;
                    echo linea();

                    echo "\n+¿Seguro que desea eliminar este responsable? (si/no): \n>";
                    $confirmacion = entre(["si", "no"]);
                    if($confirmacion == "si"){
                        $colecViajes = $objViaje->listar("rnumeroempleado = " . $objResponsableV->getNumEmpleado());
                        if(count($colecViajes) == 0){
                            $objResponsableV->eliminar();
                            echo "++++ELIMINADO.";
                        }else{
                            echo "+Para poder eliminar un responsable asignado a algun viaje. Debe indicar un nuevo responsable";
                            echo "\n+Ingrese el numero de licencia del nuevo responsable: \n>";
                            $respuestaLic = rango(1, 1000000000000);
                            if($respuestaLic != $respuesta){
                                if($objNuevoResponsableV->buscarResponsablePorLicencia($respuestaLic)){
                                    $objViaje->setObjResponsableV($objResponsableV);
                                    $objViaje->cambiarResponsable($objNuevoResponsableV);
                                    $objResponsableV->eliminar();
                                    echo "++++ELIMINADO.";
                                }else{
                                    echo "+El responsable no fue encontrado. Utilice la opcion 6 del menu principal para agregarlo.";
                                }
                            }else{
                                echo "+No puede usar el mismo responsable que desea eliminar. Utilice otro.";
                            }

                        }
                    }
                }else{
                    echo "+El responsable ingresado no fue encontrado.";
                }
            }else{
                echo "+Se requieren al menos dos responsables para ejecutar esta operacion. Utilice la opcion 6 del menu principal para agregar responsables.";
            }
            break;
        case 9:
            echo "\n\n+++++BUSCAR PASAJERO";
            echo "\n+Ingrese el número de documento del pasajero: \n>";
            $respuesta = rango(1, 1000000000);
            if($objPasajero->buscarPasajero($respuesta)){
                echo linea();
                echo $objPasajero->mostrarDatos();
                echo linea();
            }else{
                echo "+Pasajero no encontrado.";
            }
            break;
        case 10:
            echo "\n\n++++MODIFICAR PASAJERO";
            echo "\n+Ingrese el número de documento del pasajero: \n>";
            $respuesta = rango(1, 1000000000);
            if($objPasajero->buscarPasajero($respuesta)){
                echo "+Ingrese el nuevo nombre del pasajero: (Actual: " . $objPasajero->getNombre() . ") - '-1' para dejarlo igual\n>";
                $nombre = trim(fgets(STDIN));
                echo "+Ingrese el nuevo apellido del pasajero: (Actual: " . $objPasajero->getApellido() . ") - '-1' para dejarlo igual\n>";
                $apellido = trim(fgets(STDIN));
                echo "+Ingrese el nuevo número de telefono del pasajero: (Actual: " . $objPasajero->getTelefono() . ") - '-1' para dejarlo igual\n>";
                $telefono = rango(-1, 99999999999);
                $objPasajero->modificarDatos($nombre, $apellido, $telefono);
                echo "++++MODIFICACIÓN EXITOSA";
            }else{
                echo "+ERROR: El pasajero no fue encontrado.";
            }
            break;
        case 11:
            echo "\n\n+++++ELIMINAR PASAJERO";
            echo "\n+Ingrese el número de documento: \n>";
            $respuesta = rango(1, 1000000000);
            if($objPasajero->buscarPasajero($respuesta)){
                echo linea();
                echo $objPasajero->mostrarDatos();
                echo linea();

                echo "\n+¿Seguro que desea eliminar este pasajero? (si/no): \n>";
                $respuesta = entre(["si", "no"]);
                if($respuesta == "si"){
                    $objPasajero->eliminar();
                    echo "++++ELIMINADO.";
                }
            }else{
                echo "El pasajero ingresado no fue encontrado.";
            }
            break;
    }
}while($respuesta != 12)
?>