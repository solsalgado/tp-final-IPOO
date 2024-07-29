<?php

class Viaje{
    private $idViaje; // auto increment
    private $destino;
    private $cantMaxpasajeros;
    private $idEmpresa;
    private $numEmpResponsable;
    private $importe;
    private $mensajeOperacion;

    public function __construct() {
        $this->idViaje = "";
        $this->destino = "";
        $this->cantMaxpasajeros = "";
        $this->idEmpresa;
        $this->numEmpResponsable;
        $this->importe = "";
       }

    public function getIdviaje(){
        return $this->idViaje;
    }

    public function getDestino(){
        return $this->destino;
    }

    public function getCantMaxPasajeros(){
        return $this->cantMaxpasajeros;
    }

    public function getIdEmpresa(){
        return $this->idEmpresa;
    }

    public function getNumEmpResponsable(){
        return $this->numEmpResponsable;
    }

    public function getImporte(){
        return $this->importe;
    }

    public function getMensajeOperacion () {
        return $this->mensajeOperacion;
    }

    public function setIdviaje($idViaje){
        $this->idViaje = $idViaje;
    }

    public function setDestino($destino){
        $this->destino = $destino;
    }

    public function setCantMaxPasajeros($cantMaxPasajeros){
        $this->cantMaxpasajeros = $cantMaxPasajeros;
    }

    public function setIdEmpresa($idEmpresa){
        $this->idEmpresa = $idEmpresa;
    }

    public function setNumEmpResponsable($numEmpResponsable){
        $this->numEmpResponsable = $numEmpResponsable;
    }

    public function setImporte($importe){
        $this->importe = $importe;
    }

    public function setMensajeOperacion ($mensajeOperacion) {
        $this->mensajeOperacion = $mensajeOperacion;
    }
    
       public function Cargar ($idViaje, $destino, $cantMaxPasajeros, $idEmpresa, $numEmpResponsable, $importe) {
         $this->setIdviaje($idViaje);
         $this->setDestino($destino);
         $this->setCantMaxPasajeros($cantMaxPasajeros);
         $this->setIdEmpresa($idEmpresa);
         $this->setNumEmpResponsable($numEmpResponsable);
         $this->setImporte($importe);
       }
    
       /**
        * funcion insertar
        * @return boolean
        */   
       public function Insertar (){
        $baseDatos = new BaseDatos();
        $resp = false;
        $destino =$this->getDestino();
        $cantMaxPasajeros = $this->getCantMaxPasajeros();
        $objEmpresa = $this->getIdEmpresa();
        $objResp = $this->getNumEmpResponsable();
        $idE = $objEmpresa->getIdEmpresa();
        $nroEmpleado = $objResp->getNumEmpleado();
        $importe = $this->getImporte();
    
        $consultaInsertar = "INSERT INTO viaje (destino, cantMaxPasajeros, idEmpresa, numEmpResponsable, importe)
                            VALUES ('$destino', '$cantMaxPasajeros', '$idE', '$nroEmpleado', '$importe')";
        if ($baseDatos->Iniciar()) {
            if ($id = $baseDatos->devuelveIDInsercion($consultaInsertar)) {
                $this->setIdViaje($id);
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
            $arrayViajes = null;
            $baseDatos = new BaseDatos();
            $consultaViajes = "Select * from viaje";
    
            if ($condicion != "") {
                $consultaViajes = $consultaViajes . 'where' . $condicion;
            }
    
            $consultaViajes .= " order by idviaje ";
    
            if ($baseDatos->Iniciar()) {
                if ($baseDatos->Ejecutar($consultaViajes)) {
                    $arrayViajes = [];
                    while ($row2 = $baseDatos->Registro()) {
                        $idViaje = $row2 ['idViaje'];
                        $destino = $row2 ['destino'];
                        $cantmaxpasajeros = $row2 ['cantMaxpasajeros'];
                        $idEmpresa = $row2 ['idempresa'];
                        $numEmpResponsable = $row2 ['numEmpResponsable'];
                        $importe = $row2 ['importe'];
    
                        $objViaje = new Viaje();
                        $objViaje->Cargar($idViaje, $destino, $cantmaxpasajeros, $idEmpresa, $numEmpResponsable, $importe);
                        array_push($arrayViajes, $objViaje);
                    }
                } else {
                    $this->setMensajeOperacion($baseDatos->getError());
                }
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
    
            return $arrayViajes;
        }
    
        /**
        * funcion buscar
        * @return boolean
        */   
        public function Buscar ($id) {
            $baseDatos = new BaseDatos();
            $consultaViajes = "Select * from viaje where idviaje=".$id;
            $resp = false;
    
            if ($baseDatos->Iniciar()) {
                if ($baseDatos->Ejecutar($consultaViajes)) {
                    if ($row2 = $baseDatos->Registro()) {

                        $objetoEmpresa = new Empresa();
                        $objetoEmpresa->Buscar($row2 ['idempresa']);

                        $objetoResponsable = new Responsable();
                        $objetoResponsable->Buscar($row2 ['numEmpResponsable']);

                        $this->Cargar($id, $row2 ['destino'], $row2 ['cantMaxpasajeros'], $objetoEmpresa, $objetoResponsable, $row2 ['importe']);
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
        public function Modificar () { 
            $resp = false;
            $baseDatos = new BaseDatos();
            $objEmp = $this->getIdEmpresa();
            $objResp = $this->getNumEmpResponsable();
            $idMod = $objEmp->getIdEmpresa();
            $nroEmpleadoMod = $objResp->getNumEmpleado();
            $consultaModificar = "UPDATE viaje SET destino='".$this->getDestino()."',cantMaxpasajeros='".$this->getCantMaxPasajeros()."',idEmpresa='".$idMod."',numEmpResponsable='".$nroEmpleadoMod."',importe='".$this->getImporte().
                                "' WHERE idviaje=". $this->getIdviaje();
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
            if ($baseDatos->Iniciar()) {
                $consultaBorrar ="DELETE FROM viaje WHERE idViaje=".$this->getIdviaje();
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
            return "\n ID Viaje: " . $this->getIdviaje().
            "\n Destino: " . $this->getDestino().
            "\n Cantidad máxima de pasajeros: " . $this->getCantMaxPasajeros().
            "\n ID Empresa: " . $this->getIdEmpresa().
            "\n Numero de Empleado: " . $this->getNumEmpResponsable().
            "\n Importe: " . $this->getImporte();

        }

}
