<?php

namespace TrabajoTarjeta;

class Tarjeta implements TarjetaInterface {
    
    /** TODO cambiar el nombre de las variable:
     *  - iguales: usado para el transbordo y saber cuando se tomo la misma linea
     */ 
    protected $saldo;
    protected $viajesplus;
    protected $ID;
    protected $ultboleto = null;
    protected $tipo;
    protected $ultimoplus = false;
    protected $ultimoPago = 0; // TODO eliminar esto
    public $universitario = false;
    protected $ultimoBoleto = null;
    protected $montoTransbordo;
    protected $tiempoTr;
    public $medios;
    
    protected $colec;
    protected $ultimoColectivo = null;
    protected $iguales = false;
    
    
    public function __construct($tipo_franquicia) {
        $this->saldo     = 0.0;
        $this->viajesplus = 0;
        $this->medios     = 2;
        $this->ID        = rand(0, 100);
        $this->ultboleto = null;
        $this->tipo_franquicia      = $tipo_franquicia;
    }

    public function contarMedio() {
        $this->medios -= 1;
    }
    
    public function obtenerTipo() {
        return $this->tipo_franquicia;
    }    
    
    public function DevolverUltimoBoleto() {
        return $this->ultimoBoleto; 
    }
    
    public function devolverUltimoPago() {
        return $this->ultimoPago;
    } 
    
    public function CantidadPlus() {
        return $this->viajesplus; 
    }
    
    public function descontarPlus() { 
        if ($this->viajesplus >= 2) {
            return false;
        }

        $this->viajesplus += 1;
        return true;
    }

    public function reiniciarPlus() {
        $this->viajesplus = 0;
    }
    
    
    public function saldoSuficiente() {
        if ($this->saldo >= Boleto::obtenerMontoNormal()) {
            return TRUE;
        }

        return FALSE;  
    } 
    
    public function restarSaldo($monto) {
        if ($this->ultimoBoleto == NULL) {       
            // primer viaje 
            $this->saldo -= $monto;
        } else {

            if (! $this->esTransbordo() ) {
                $this->saldo -= $monto;
            }

        }
    }
    
    public function esTransbordo()
    {
        $tiempo_desde_ultimo_boleto = time() - $this->ultimoBoleto;
        
        if ($tiempo_desde_ultimo_boleto < Tiempo::obtenerTiempoTransbordo()) {
            return TRUE;
        }

        return FALSE;
    }

    public function obtenerID() {
        return $this->ID;
    }
    
    public function devolverUltimoColectivo() {
        return $this->ultimoColectivo;
    }
    
    public function ColectivosIguales() { 
        return $this->iguales;
    }
    

    public function informarUso(ColectivoInterface $colectivo) 
    {
        if ($this->ultimoBoleto == NULL) {
            $this->iguales = FALSE;
        }
        else {
            if ($colectivo->linea() == $this->devolverUltimoColectivo()->linea()) {
                $this->iguales = TRUE;
            }
            else {
                $this->iguales = FALSE;
            }
        }

        $this->ultimoColectivo = $colectivo;
    }

    public function obtenerUltimoPlus()
    {
        return $this->ultimoplus;
    }
    
    public function pagar($valor) {
                
        if ($this->saldo >= $valor) {

            $this->restarSaldo($valor);
            $this->ultimoPago      = $valor;
            $this->ultimoplus      = FALSE;
            $this->ultimoBoleto    = time();
            
            return TRUE;
            
        } else if ($this->viajesplus > 0) {
            $this->viajesplus -= 1;
            $this->ultimoplus = TRUE;
            $this->ultimoBoleto = time();

            return TRUE;

        }

        return FALSE;
        
    }

    public function obtenerSaldo() {
        return $this->saldo;
    }
    
    public function recargar($monto) {
        $this->saldo = 0;

        if ($monto == 962.59) {
            $this->saldo += 221.58;
        } else if ($monto == 510.15) {
            $this->saldo += 81.93;
        }

        if ($this->viajesplus > 0) {
            $montoViajesPlus = Boleto::obtenerMontoNormal() * $this->viajesplus;
            
            if ($this->saldo < $montoViajesPlus) {
                return FALSE;
            } else {
                $this->saldo -= $montoViajesPlus; 
            }

            $this->reiniciarPlus();
        } 
        
        return TRUE;
        
    }
    
}