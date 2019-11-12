<?php

namespace TrabajoTarjeta;

interface TarjetaInterface {

    /**
     * Recarga una tarjeta con un cierto valor de dinero.
     *
     * @param float $monto
     *
     * @return bool
     *   Devuelve TRUE si el monto a cargar es válido, o FALSE en caso de que no
     *   sea valido.
     */
    public function recargar($monto);

    /**
     * Devuelve el saldo que le queda a la tarjeta.
     *
     * @return float
     *      el saldo de la saldo
     */
    public function obtenerSaldo(); 


    /**
     * Devuelve el ultimo boleto que se saco con la tarjeta
     * En caso de que sea el primer viaje de la tarjeta esta funcion retorna NULL
     * 
     * @return int 
     *       El tiempo del ultimo boleto que se saco con la tarjeta
     */
    public function DevolverUltimoBoleto(); 

    /**
     * Devuelve TRUE si el ultimo viaje realizo fue plus. Devuelve FALSE en caso contrario
     * 
     * @return bool
     *          $Ultimoplus
     */
    public function obtenerUltimoPlus();

    /**
     * Retorna la cantidad de dinero que usamos el ultimo viaje, que se encuentra almacenada 
     * en la variable pago.
     * @return float
     *          Pago del ultimo viaje
     */
    public function devolverUltimoPago(); 

    /**
     * Devuelve el tipo de tarjeta, que puede ser:
     * -franquicia normal
     * -franquicia completa
     * -media franquicia estudiantil
     * -medio universitario
     *  @return string
     *              El tipo de tarjeta
     */ 
    public function obtenerTipo();

    /**
     * Almacena la cantidad de viajes plus que DEBEMOS
     * 
     *   @return int
     *           la cantidad de plus que debemos
     */
    public function CantidadPlus();

    /**
     * Incrementa en 1 la cantidad de plus que debemos. Esta funcion no retorna nada 
     * 
     * @return bool
     *      Retorna si se pueden o no descontar plus
     */
    public function descontarPlus();

    /**
     * Reinicia la cantidad de viajes plus usados a 0
     */
    public function reiniciarPlus();

    /**
     * Retorna TRUE en caso de que tengamos el saldo suficiente para pagar un viaje.
     * Retorna FALSE en caso contrario.
     * 
     *  @return bool
     *          Condicion para pagar un viaje
     */
    public function saldoSuficiente();

    /**
     * Funcion necesaria para configurar en que colectivo nos encontramos actualmente que
     * cualquier implementacion de boletera deberia usar antes de informar un pago
     * 
     * @param ColectivoInterface colectivo
     *      El colectivo en el que se encuentra el usuario
     */
    public function informarUso(ColectivoInterface $colectivo);

    /**
     * Devuelve TRUE si el viaje que vamos a pagar debe ser transbordo. 
     * Devuelve FALSO en caso contrario
     * 
     * @return bool
     */
    public function esTransbordo();

    /**
     * Resta el saldo a nuestra tarjeta despues de pagar un viaje
     */
    public function restarSaldo();

    /**
     * Devuelve la ID de nuestra tarjeta
     *  @return int
     *             ID de la tarjeta
     * */
    public function obtenerID();

    /**
     * Devuelve el ultimo colectivo que hayamos viajado
     * @return ColectivoInterface
     *                  Ultimo colectivo en el que viajamos
     */
    public function devolverUltimoColectivo();

    /**
     * Devuelve TRUE en el caso de que el ultimo colectivo que viajamos sea igual al colectivo que 
     * nos vamos a subir
     * FALSE en caso contrario
     * 
     * @return bool
     */
    public function ColectivosIguales();

    /**
     * Descuenta un medio boleto de la tarjeta
     */
    public function contarMedio();

    /**
     * Devuelve TRUE y realiza las acciones correspondientes en caso de que podamos pagar un viaje
     * FALSE en caso contario
     * @return bool
     */
    public function pagar($valor);

}
