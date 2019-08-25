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
    protected $tipo = 'franquicia normal';
    protected $ultimoplus = false;
    protected $pago = 0; // TODO eliminar esto
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
        
        // devuelve cuando se pago el ultimo boleto
        return $this->ultimoTiempo; 
    }
    
    public function MostrarPlusDevueltos() {
        
        return $this->plusdevuelto; // ANCHOR ?
    }

    public function reiniciarPlusDevueltos() {
        
        $this->plusdevuelto = 0; // ANCHOR ?
    }
    
    public function ultimopago($monto) {
        
        // registra el ultimo pago
        $this->pago = $monto;
        
    }
    
    public function devolverUltimoPago() {
        
        return $this->pago;
    } 
    
    public function tipotarjeta() // TODO matar esto
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
    
    public function descontarPlus() { 
        if ($this->viajesplus <= 0) {
            throw new Exception('No hay viajes plus para descontar');
        }

        $this->viajesplus -= 1;
    }

    public function reiniciarPlus() {
        $this->viajesplus = 2;
    }
    
    
    public function saldoSuficiente() {
        if ($this->saldo >= Boleto::obtenerMontoNormal()) {
            return TRUE;
        }

        return FALSE;
        
    } //indica si tenemos saldo suficiente para pagar un viaje
    
    public function obtenerUltimoFueTransbordo() {
        
        return $this->ultimoFueTransbordo;
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
    
    public function restarSaldo($monto) {
        if ($this->DevolverUltimoTiempo() == NULL) {
            
            $this->saldo -= $monto;

        } else {
            
            // pagar un viaje comun y corriente
            if ($this->esTransbordo()) {
                
                $this->saldo -= $monto;
                $this->ultimoFueTransbordo = TRUE;
            }
            else {
                
                $this->saldo -= $monto;
                // el primer viaje normal luego de un transbordo debe hacer esto
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
    
    public function ColectivosIguales() { 
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
                
        if ($this->saldo >= $valor) {

            $this->restarSaldo($valor);
            $this->ultimopago($valor);
            $this->ultimoplus      = FALSE;
            $this->ultimoTiempo    = time();
            
            return TRUE;
            
        } else if ($this->viajesplus > 0) {
            $this->viajesplus -= 1;
            $this->ultimoplus = TRUE;
            $this->ultimoTiempo = time();

            return TRUE;

        }

        return FALSE;
        
    }
    
    public function recargar($monto) {
        // TODO delegar responsabilidad de extras en las recargas a otra clase
        $extra = 0.0;
        if ($monto == 962.59) {
            $extra = 221.58;
        } else if ($monto == 510.15) {
            $extra = 81.93;
        }

        $this->saldo += $monto + $extra;

        // si hay viajes plus usados descontarlos solo si se alcanzan a pagar
        if ($this->viajesplus > 0) {
            $montoViajesPlus = Boleto::obtenerMontoNormal() * $this->viajesplus;
            
            if ($this->saldo < $montoViajesPlus) {
                // ANCHOR quizas se pueda devolver false simplemente y evitar tirar un error
                throw new Exception('La carga no es posible ya que no se alcanzan a pagar los viajes plus');
            } else {
                $this->saldo -= $montoViajesPlus; 
            }

            $this->reiniciarPlus();
        } 
        
        return TRUE;
        
    }
    
}