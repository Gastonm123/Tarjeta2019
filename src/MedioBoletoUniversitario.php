<?php

namespace TrabajoTarjeta;

class MedioBoletoUniversitario extends Tarjeta implements TarjetaInterface {
    
    protected $CantidadBoletos = 0;
    public $universitario = true;
    
    /**
     * Analiza si podemos realizar un pago, y que tipo de viaje haremos. 
     * Devuelve TRUE en caso de que podamos pagar un viaje y falso en caso contrario
     * 
     * @param Colectivo
     *              El colectivo en el que queremos pagar
     * @return bool
     *              Si se pudo pagar o no
     */
    public function pagoMedioBoleto(Colectivo $colectivo)
    {
        
        $this->informarUso($colectivo);
        $valor = Boleto::obtenerMontoNormal();

        if ($this->revisarHora() == FALSE) {
            
            $this->pagar($valor);
        
        // supongo q tienen que esperar  5 minutos para volver a usar el colectivo
        } else if (time() - $this->DevolverUltimoTiempo() > 5 * 60) { 
            $valor = Boleto::obtenerMedioBoletoUniversitario();
            
            $this->pagar($valor);
            $this->IncrementarBoleto();
            
        }

        return false;
        
    }
    
    /**
     * Incrementa en 1 la cantidad de medios boletos que usamos en el dia
     *  @return int
     *              cantidad de medios boletos usados en el dia
     */
    public function IncrementarBoleto() {
        
        $this->CantidadBoletos += 1;
        return $this->CantidadBoletos;
    }
    
    /**
     * Reinicia la cantidad de boletos que podemos usar a 0
     */
    public function ReiniciarBoleto() {
        
        $this->CantidadBoletos = 0;
        
    }
    
    /**
     * Devuelve TRUE si nos quedan medios boletos para usar y FALSE en caso contrario
     * @return bool         
     *            
     */
    public function ViajesRestantes() {
        return ($this->CantidadBoletos < 2);
    }

    /**
     * @return int
     *              la cantidad de medios boletos que usamos en el dia
     */
    
    public function DevolverCantidadBoletos() {
        
        return $this->CantidadBoletos;
    }
    
    /**
     * Horas devuelve falso cuando la tarjeta realizará su primer pago, o cuando haya pasado mas de 24 horas
     * con respecto al ultimo pago. Si pasaron mas de 24 horas reinicia la cantidad de boletos.
     * 
     * Horas tambien devuelve FALSE en caso de que la tarjeta usada no sea de tipo medio universitario 
     * 
     * @return bool
     *          
     */
    public function revisarHora()
    {
        
        if($this->tipotarjeta() == 'medio universitario') {
            $ultimoBoleto = $this->DevolverUltimoTiempo();

            if ($ultimoBoleto != NULL) {
            
                // si no pasaron 24hs y le quedan boletos devuelve true
                if (time() - $ultimoBoleto < 60 * 60 * 24)
                {
                    return ($this->ViajesRestantes()); 
                    
                }
                
                // si pasaron 24hs devuelve false y reinicia los boletos
                $this->ReiniciarBoleto();
                return TRUE; 
            }
        } 
        
    }
    
    
}