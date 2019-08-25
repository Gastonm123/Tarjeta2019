<?php

namespace TrabajoTarjeta;

class Tarjeta implements TarjetaInterface {
    
    protected $saldo = 0;
    public $monto = 14.8; // TODO el monto es responsabilidad del boleto
    protected $viajesplus = 0; // ANCHOR quizas sea mejor cargar saldo negativo
    protected $ID;
    protected $ultboleto = null;
    protected $tipo = 'franquicia normal';
    protected $ultimoplus = false;
    protected $fechault; // ANCHOR ?
    protected $pago = 0;
    protected $plusdevuelto = 0;
    public $universitario = false;
    protected $ultimoTiempo = null;
    protected $montoTransbordo;
    protected $tiempoTr;
    
    // el ultimo boleto fue transbordo
    protected $ultimoFueTransbordo = false; 
    
    protected $colec;
    protected $ultimoColectivo = null;
    protected $iguales = false;
    
    
    public function __construct() {
        $this->saldo     = 0.0;
        $this->viajesplus = 2;
        $this->ID        = rand(0, 100);
        $this->ultboleto = null;
    }
    
    
    public function getTiempo() {
        return time();
    }
    
    
    public function DevolverUltimoTiempo() {
        
        return $this->ultimoTiempo; // ANCHOR ?
    }
    
    public function MostrarPlusDevueltos() {
        
        return $this->plusdevuelto; // ANCHOR ?
    }

    public function reiniciarPlusDevueltos() {
        
        $this->plusdevuelto = 0; // ANCHOR ?
    }
    
    public function ultimopago() {
        if ($this->ultimoFueTransbordo) {
          $this->pago = ($this->monto * 0.33); // TODO aplicar tarifas de transbordo
        }
       
        else {
          $this->pago = $this->monto + 14.8*$this->MostrarPlusDevueltos(); // ANCHOR ?
          // TODO aplicar tarifas de viaje
        }
        
    }
    
    public function devolverUltimoPago() {
        
        return $this->pago;
    } 
    
    public function tipotarjeta() 
    {
        // esto es una aberracion
        if ($this->monto == 14.8) {
            return $this->tipo;
        }
        else {
            if ($this->monto == 7.4) {
                
                if ($this->universitario == TRUE) {
                    $this->tipo = 'medio universitario';
                    return $this->tipo;
                }
                $this->tipo = 'media franquicia estudiantil';
                return $this->tipo;
            }
            $this->tipo = 'franquicia completa';
            return $this->tipo;
        }
        
    }
    
    public function CantidadPlus() {
        return $this->viajesplus; //devuelve la cantidad de viajes plus que adeudamos
        
    }
    
    public function descontarPlus() { // TODO cambiar IncrementoPlus por descontarPlus
        if ($this->viajesplus <= 0) {
            throw new Exception('No hay viajes plus para descontar');
        }

        $this->viajesplus -= 1;
    }
    
    
    public function saldoSuficiente() {
        if ($this->viajesplus > 0) {
            $this->viajesplus -= 1;
            return True;
        }
        if ($this->saldo >= Boleto::obtenerMontoNormal()) {
            return TRUE;
        }

        return FALSE;
        
    } //indica si tenemos saldo suficiente para pagar un viaje
    
    public function obtenerUltimoFueTransbordo() {
        
        return $this->ultimoFueTransbordo;
    }

    public function devolverMontoTransbordo() {
        $this->montoTransbordo = ($this->monto*0.33); // TODO cargar monto de transbordo
        return $this->montoTransbordo;
    }
    
    // TODO meter toda la logica de cuando es tranbordo en boletera
    public function tiempoTransbordo() {
        if ($this->tiempo->esDiaSemana() && $this->tiempo->esFeriado() == FALSE) {
            $tiempoTr = 60 * 60;
            return $tiempoTr;
        }
        
        $tiempoTr = 90 * 60;
        return $tiempoTr;
    }
    
    public function restarSaldo() {
        if ($this->DevolverUltimoTiempo() == NULL) {
            
            
            $this->saldo -= $this->monto;
            $this->viajesplus        = 0;
            $this->ultimoFueTransbordo = FALSE;
        }
        else {
            
            if ($this->esTransbordo()) {
                
                
                $this->montoTransbordo = ($this->monto * 0.33);
                $this->saldo -= $this->montoTransbordo;
                $this->ultimoFueTransbordo = TRUE;
            }
            else {
                
                $this->saldo -= ($this->monto + $this->CantidadPlus() * 14.8);
                $this->viajesplus        = 0;
                $this->ultimoFueTransbordo = FALSE;
            }
            
        }
    }
    
    public function obtenerID() {
        return $this->ID;
    }
    
    public function guardarUltimoBoleto($boleto) {
        $this->ultboleto = $boleto;
    }
    
    public function devolverUltimoColectivo() {
        return $this->ultimoColectivo;
    }
    
    public function ColectivosIguales() { // ANCHOR ?
        return $this->iguales;
    }
    

    public function informarUso(ColectivoInterface $colectivo) 
    {
        if ($this->DevolverUltimoTiempo() == NULL) {
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
        // TODO cambiar este metodo para que se pueda especificar el monto a pagar
                
        if ($this->saldoSuficiente()) {
            // TODO entender este trozo
            if ($this->ultimoplus == FALSE) {
                $this->restarSaldo();
                $this->ultimopago();
                $this->plusdevuelto    = 0;
                $this->ultimoplus      = FALSE;
                $this->ultimoTiempo    = time();
                $this->ultimoColectivo = $colectivo;
            }
            else {
                $this->plusdevuelto = $this->CantidadPlus();
                $this->restarSaldo();
                $this->ultimopago();
                $this->RestarPlus();
                $this->ultimoplus      = false;
                $this->ultimoTiempo    = time();
                $this->ultimoColectivo = $colectivo;
            }
            
            return true;
            
        }
        else {
            
            if ($this->CantidadPlus() < 2) {
                $this->plusdevuelto = 0;
                $this->ultimoplus   = true;
                $this->IncrementoPlus();
                $this->ultimoTiempo    = time();
                $this->ultimoColectivo = $colectivo;
                return true;
            }
            return false;
            
        }
        
    }
    
    public function recargar($monto) {
        
        if ($monto == 10 || $monto == 20 || $monto == 30 || $monto == 50 || $monto == 100 || $monto == 510.15 || $monto == 962.59) {
            if ($monto == 962.59) {
                $this->saldo += ($monto + 221.58);
                return true;
            }
            else {
                if ($monto == 510.15) {
                    $this->saldo += ($monto + 81.93);
                    return true;
                }
                else {
                    $this->saldo += $monto;
                    return true;
                }
            }
            
        }
        else {
            return false; // TODO revisar por q no pueden pagar distinto de esas cantidades
            
        }
        
    }
    
}