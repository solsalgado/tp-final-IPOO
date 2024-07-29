<?php

class Pasajero{
   
   private $nombre;
   private $apellido;
   private $numDocumento; 
   private $telefono;
   private $idviaje;
   private $mensajeOperacion;

   
   public function __construct() {
    $this->nombre = "";
    $this->apellido = "";
    $this->numDocumento = "";
    $this->telefono = "";
    $this->idviaje = "";
   }

   public function getNombre(){
        return $this->nombre;
   }

   public function getApellido(){
        return $this->apellido;
   }

   public function getNumDocumento(){
        return $this->numDocumento;
   }

   public function getTelefono(){
        return $this->telefono;
   }

   public function getIdviaje(){
        return $this->idviaje;
   }

   public function getMensajeOperacion () {
        return $this->mensajeOperacion;
   }

   public function setNombre($nombre){
        $this->nombre = $nombre;
   }

   public function setApellido($apellido){
        $this->apellido = $apellido;
   }

   public function setNumDocumento($numDocumento){
        $this->numDocumento = $numDocumento;
   }

   public function setTelefono($telefono){
        $this->telefono = $telefono;
   }

    public function setIdviaje($idviaje){
        $this->idviaje = $idviaje;
   }

   public function setMensajeOperacion ($mensajeOperacion) {
        $this->mensajeOperacion = $mensajeOperacion;
   }



   public function Cargar ($nombre, $apellido, $numDocumento, $telefono, $idviaje) {
     $this->setNombre($nombre);
     $this->setApellido($apellido);
     $this->setNumDocumento($numDocumento);     
     $this->setTelefono($telefono);
     $this->setIdviaje($idviaje);
   }

   /**
     * funcion insertar
     * @return boolean
     */
   public function Insertar(){
    $baseDatos = new BaseDatos();
    $resp = false;
    $pNombre = $this->getNombre();
    $pApellido = $this->getApellido();
    $doc = $this->getNumDocumento();    
    $pTelefono = $this->getTelefono();
    $id = $this->getIdviaje();

    $consultaInsertar = "INSERT INTO pasajero(nombre, apellido, numDocumento, telefono, idviaje)
                        VALUES ( '$pNombre', '$pApellido', '$doc', '$pTelefono', '$id')";
    if ($baseDatos->Iniciar()) {
        if ($baseDatos->Ejecutar($consultaInsertar)) {
            $resp = true;
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }
    } else {
        $this->setMensajeOperacion($baseDatos->getError());
    }
    return $resp;
    }

    /**
     * funcion listar
     * @return array
     */
    public function Listar ($condicion = "") {
        $arrayPasajeros = null;
        $baseDatos = new BaseDatos();
        $consultaPasajeros = "Select * from pasajero";

        if ($condicion != "") {
            $consultaPasajeros = $consultaPasajeros . 'where' . $condicion;
        }

        $consultaPasajeros .= " order by numDocumento ";

        if ($baseDatos->Iniciar()) {
            if ($baseDatos->Ejecutar($consultaPasajeros)) {
                $arrayPasajeros = [];
                while ($row2 = $baseDatos->Registro()) {
                    $nombre = $row2 ['nombre'];
                    $apellido = $row2 ['apellido'];
                    $documento = $row2 ['numDocumento'];                    
                    $telefono = $row2 ['telefono'];
                    $idviaje = $row2 ['idviaje'];

                    $objPasajero = new Pasajero();
                    $objPasajero->Cargar( $nombre, $apellido, $documento, $telefono, $idviaje);
                    array_push($arrayPasajeros, $objPasajero);
                }
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }

        return $arrayPasajeros;
    }

    /**
     * funcion buscar
     * @return boolean
     */
    public function Buscar ($dni) {
        $baseDatos = new BaseDatos();
        $consultaPasajeros = "Select * from pasajero WHERE numDocumento=".$dni;
        $resp = false;

        if ($baseDatos->Iniciar()) {
            if ($baseDatos->Ejecutar($consultaPasajeros)) {
                if ($row2 = $baseDatos->Registro()) {
                    
                    $this->Cargar($row2 ['nombre'], $row2 ['apellido'], $dni, $row2 ['telefono'], $row2 ['idviaje']);

                    $resp = true;
                }
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }

        return $resp;
    }

/**
        * funcion modificar
        * @return boolean
        */   
        public function Modificar() { 
            $resp = false;
            $baseDatos = new BaseDatos();
            $dni = $this->getNumDocumento();
            $consultaModificar = "UPDATE pasajero SET nombre='".$this->getNombre()."',apellido='".$this->getApellido()."',telefono='".
                                $this->getTelefono()."',idviaje='". $this->getIdviaje()."' WHERE numDocumento= $dni";
            if ($baseDatos->Iniciar()) {
                if ($baseDatos->Ejecutar($consultaModificar)) {
                    $resp = true;
                } else {
                    $this->setMensajeOperacion($baseDatos->getError());
                }
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
                        
            return $resp;
        }
    

    /**
     * funcion eliminar
     * @return boolean
     */
    public function eliminar () {
        $resp = false;
        $baseDatos = new BaseDatos();
        $numDni = $this->getNumDocumento();
        if ($baseDatos->Iniciar()) {
            $consultaBorrar ="DELETE FROM pasajero WHERE numDocumento= $numDni";
            if ($baseDatos->Ejecutar($consultaBorrar)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }
                    
        return $resp;

    }

    public function __toString()
    {
        return "\n Documento: " . $this->getNumDocumento().
        "\n Nombre: " . $this->getNombre().
        "\n Apellido: " . $this->getApellido().
        "\n Telefono: " . $this->getTelefono().
        "\n ID Viaje: " . $this->getIdviaje();
    }
}
