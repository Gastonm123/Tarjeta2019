<?php

namespace TrabajoTarjeta;

class Tiempo implements TiempoInterface {
    public function __construct($tiempo = null) {
        $this->tiempo = $tiempo;
    }

    public function tiempo() {
        if ($this->tiempo == null) {
            return time();
        }

        return $this->tiempo;
    }

    public static function obtenerTiempoTransbordo() 
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
}