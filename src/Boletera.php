<?php

namespace TrabajoTarjeta;

class Boletera implements BoleteraInterface {

    protected $colectivo; // la boletera obviamente esta en un unico colectivo
    protected $ingreso;

    public function __construct(ColectivoInterface $colectivo, $tiempo = null)
    {
        $this->colectivo = $colectivo;
        $this->ingreso = 0;
        $this->tiempo = new Tiempo($tiempo);
    }

    public function sacarBoleto($tarjeta)
    {
        $tipo = $this->tipoBoleto($tarjeta);
        $boleto = new Boleto($this, $tarjeta, $tipo);
        $descontado = $boleto->obtenerValor();

        $tarjeta->informarUso($this->colectivo);
        $pago = $tarjeta->pagar($descontado);

        if ($pago == FALSE) {
            return FALSE;
        }

        $tarjeta->guardarUltimoBoleto($boleto);
        $this->ingreso += $descontado;

        return TRUE;
    }

    private function tipoBoleto($tarjeta) 
    {
        $tipo_tarjeta = $tarjeta->obtenerTipo();

        if ((
            $tipo_tarjeta == 'media franquicia estudiantil' || 
            $tipo_tarjeta == 'medio boleto universitario'
            ) && $tarjeta->medios > 0
           )
        {
            if ($tipo_tarjeta == 'medio boleto universitario') {
                $tarjeta->contarMedio();
            }

            $tipo = "medio boleto";
        } else if ($tipo_tarjeta == 'franquicia completa') {
            $tipo = "franquicia completa";
        } else {

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

        }

        return $tipo;
    }

    private function esTransbordo($tarjeta) 
    {
        $tiempo_desde_ultimo_viaje = time() - $tarjeta->DevolverUltimoTiempo();     

        if ($tarjeta->obtenerUltimoPlus() == FALSE && 
            $tarjeta->ColectivosIguales() == FALSE && 
            $this->obtenerLimiteTransbordos() > $tarjeta->obtenerNroTransbordos() &&
            $tiempo_desde_ultimo_viaje <= Tiempo::obtenerTiempoTransbordo()) 
        {
            return TRUE;
        }
        
        return FALSE;
    }

    private function obtenerLimiteTransbordos()
    {
        return 5000; // virtualmente infinito
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