<?php

namespace TrabajoTarjeta;

class Boletera implements BoleteraInterface {

    protected $colectivo; // la boletera obviamente esta en un unico colectivo
    protected $ingreso;

    public function __construct(ColectivoInterface $colectivo)
    {
        $this->colectivo = $colectivo;
    }

    public function sacarBoleto($tarjeta)
    {
        if ($tarjeta->obtenerTipo() == 'medio universitario' ||
            $tarjeta->obtenerTipo() == 'media franquicia estudiantil') 
        {
            if ($tarjeta->pagoMedioBoleto() == FALSE) {
                throw new Exception('No se pudo utilizar el medio boleto');
            }      
        }

        $tipo = $this->tipoBoleto($tarjeta);
        $boleto = new Boleto($this, $tarjeta, $tipo);
        $descontado = $boleto->obtenerValor();

        $tarjeta->informarUso($this->colectivo);
        if ($tarjeta->pagar($descontado) != TRUE) {
            throw new Exception('No se pudo realizar el pago correctamente');
        }

        $tarjeta->guardarUltimoBoleto($boleto);
        $this->ingreso += $descontado;

        return TRUE;
    }

    private function tipoBoleto($tarjeta) 
    {
        if ($this->esTransbordo($tarjeta)) {
            $tipo = "transbordo";
        } else if ($tarjeta->saldoSuficiente()) {
            $tipo = "viaje normal";
        } else if ($tarjeta->CantidadPlus() > 0) {
            $tipo = "viaje plus";
            $tarjeta->descontarPlus();
        } else {
            $tipo = "viaje denegado";
        }

        return $tipo;
    }

    private function esTransbordo($tarjeta) 
    {
        if ($tarjeta->obtenerUltimoPlus() == FALSE && 
            $tarjeta->ColectivosIguales() == FALSE && 
            $this->obtenerLimiteTransbordos() > $tarjeta->obtenerNroTransbordos() &&
            time() - $tarjeta->DevolverUltimoTiempo() <= $this->obtenerTiempoTransbordo()) 
        {
            return TRUE;
        }
        
        return FALSE;
    }

    private function obtenerLimiteTransbordos()
    {
        return 5000; // virtualmente infinito
    }

    private function obtenerTiempoTransbordo() 
    {
        $semana = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        // si es semana 1 hora si es finde 2 horas
        if (in_array(date('l'), $semana)) {
            $minutos = 60;
        } else {
            $minutos = 120;
        }

        return (60 * $minutos);
    }

    public function obtenerColectivo() 
    {
        return $this->colectivo;
    }

    public function obtenerIngreso()
    {
        return $this->ingreso;
    }

    public function revision()
    {
        $this->ingreso = 0;
        // se deberia registrar la ultima revision

        return true;
    }
}