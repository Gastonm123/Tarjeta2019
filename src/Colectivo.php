<?php

namespace TrabajoTarjeta;

class Colectivo implements ColectivoInterface {
    
    protected $linea;
    protected $empresa;
    protected $numero;
    public $boletera;
    
    /**
     * Constructor del colectivo
     */
    public function __construct($l, $e, $n) {
        $this->linea   = $l;
        $this->empresa = $e;
        $this->numero  = $n;
        $this->boletera = new Boletera($this);
    }
    
    public function linea() {
        return $this->linea;
    }
    
    public function empresa() {
        return $this->empresa;
    }
    
    public function numero() {
        return $this->numero;
    }
    
    public function pagarCon(TarjetaInterface $tarjeta) {
        return ($this->boletera->sacarBoleto($tarjeta));
    }
    
}
