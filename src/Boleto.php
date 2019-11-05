<?php

namespace TrabajoTarjeta;

use Exception;

class Boleto implements BoletoInterface {

    protected $valor;
    protected $colectivo;
    protected $fecha;
    protected $hora;
    protected $saldo;
    protected $id;
    protected $tipo;
    protected $timeult;
    
    public function __construct($boletera, $tarjeta, $tipo) { 
        if($tipo == "transbordo") {
            $this->valor = Boleto::obtenerMontoTransbordo();
        } else if ($tipo == "medio boleto") {
            $this->valor = Boleto::obtenerMedioBoleto();
        } else if ($tipo == "normal") {
            $this->valor = Boleto::obtenerMontoNormal();
        } else if ($tipo == 'franquicia completa') {
            $this->valor = Boleto::obtenerMontoFranquicia();
        } else if ($tipo == "plus") {
            $this->valor = 0.0; 
        } else if ($tipo == "denegado") {
            throw new Exception("Boleto denegado");
        } else {
            throw new Exception("Tipo desconocido");
        }

        $this->colectivo   = $boletera->obtenerColectivo();
        $this->saldo       = $tarjeta->obtenerSaldo();
        $this->id          = $tarjeta->obtenerID();
        $this->fecha       = date('d-m-Y', time());
        $this->tipo        = $tipo;
    }
    
    /**
     * Devuelve el valor del boleto.
     *
     * @return int
     */
    public function obtenerValor() {
        return $this->valor;
    }
    
    
    public function obtenerTipo() {
        return $this->tipo;
    }
    /**
     * Devuelve un objeto que respresenta el colectivo donde se viajó.
     *
     * @return ColectivoInterface
     */
    
    public function obtenerColectivo() {
        return $this->colectivo;
    }
    
    public function obtenerFecha() {
        return $this->fecha;
    }

    public static function obtenerMontoTransbordo() 
    {
        return 0.0;
    }

    public static function obtenerMontoNormal()
    {
        return 30.0;
    }
    
    public static function obtenerMedioBoleto()
    {
        return 15.0;
    }

    public static function obtenerMontoFranquicia()
    {
        return 0.0;
    }
    
}

