<?php

namespace TrabajoTarjeta;

class Boleto implements BoletoInterface {
    /**
     * TODO agregar metodos estaticos que devuelvan el monto de viaje normal y de transbordo
     */
    
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
        } else if ($tipo == "normal") {
            $this->valor = Boleto::obtenerMontoNormal();
        } else if ($tipo == "plus") {
            $this->valor = 0.0; 
        } else if ($tipo == "denegado") {
            $this->valor = 0.0;
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
    
}

