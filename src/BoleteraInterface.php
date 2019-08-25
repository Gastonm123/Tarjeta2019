<?php

namespace TrabajoTarjeta;

interface BoleteraInterface {

    /**
     * Ejecuta toda la logica necesaria para descontar credito de
     * la tarjeta y emitir un boleto dependiendo de las condiciones
     * de la tarjeta
     * 
     * @param TarjetaInterface $tarjeta
     * 
     * @return BoletoInterface
     *      Devuelve el boleto emitido
     */
    public function sacarBoleto($tarjeta);

    /**
     * Devuelve la cantidad de dinero q fue descontada desde la ultima
     * revision
     */
    public function obtenerIngreso();

    /**
     * Realiza la logica necesaria para una revision
     * (la revision es un descuento del ingreso de la boletera que lo deja a 0,
     * es decir, se recupera el ingreso)
     * 
     * @return bool
     *      Devuelve si la operacion se realizo con exito
     */
    public function revision();
}