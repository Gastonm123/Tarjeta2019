<?php

namespace TrabajoTarjeta;

class Boletera implements BoleteraInterface {

    protected $colectivo; // la boletera obviamente esta en un unico colectivo
    protected $ingreso;

    public function __construct(ColectivoInterface $colectivo)
    {
        $this->colectivo = $colectivo;
        $this->ingreso = 0;
    }

    public function sacarBoleto($tarjeta)
    {
        $tipo = $this->tipoBoleto($tarjeta);
        $boleto = new Boleto($this, $tarjeta, $tipo);
        $descontado = $boleto->obtenerValor();

        if ($tipo == 'medio boleto' && $tarjeta->medios == 0) {
            throw new Exception('No se pueden utilizar mas medios');
        }

        $tarjeta->informarUso($this->colectivo);
        $pago = $tarjeta->pagar($descontado);

        if ($pago == FALSE) {
            throw new Exception('No se pudo realizar el pago correctamente');
        }

        $tarjeta->guardarUltimoBoleto($boleto);
        $this->ingreso += $descontado;

        return TRUE;
    }

    private function tipoBoleto($tarjeta) 
    {
        if ($tarjeta->tipo == 'media franquicia estudiantil' || 
            $tarjeta->tipo == 'medio boleto universitario') 
        {
            if ($tarjeta->tipo == 'medio boleto universitario') {
                $tarjeta->contarMedio();
            }

            $tipo = "medio boleto";
        } else if ($tarjeta->tipo == 'franquicia normal') {
            $tipo = "normal";
        } else if ($tarjeta->tipo == 'franquicia completa') {
            $tipo = "franquicia completa";
        } if ($this->esTransbordo($tarjeta)) {
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