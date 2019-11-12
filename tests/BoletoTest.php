<?php

namespace TrabajoTarjeta;

use PHPUnit\Framework\TestCase;

class BoletoTest extends TestCase {
    
    public function boletoDenegado() {
        $colectivo = new Colectivo("133 negra", "semptur", "1234");
        $tarjeta = new Tarjeta("franquicia normal");
        
        $this->expectException("Boleto denegado");
        
        $boleto = $colectivo->pagarCon($tarjeta);
    }

    /**
     *Testeamos que la funcion fecha ande correctamente
     */
    public function franquiciaCompleta() {
        $colectivo = new Colectivo("133 negra", "semptur", "1234");
        $tarjeta = new Tarjeta("franquicia completa");

        $boleto = $colectivo->pagarCon($tarjeta);

        $this->assertEquals(0, $boleto->obtenerValor());
    }

    public function franquiciaNormal() {
        $colectivo = new Colectivo("133 negra", "semptur", "1234");
        $tarjeta = new Tarjeta("franquicia completa");

        $tarjeta->recargar(Boleto::obtenerMontoNormal());

        // Usamos el credito cargado 
        $boleto = $colectivo->pagarCon($tarjeta);

        // Comprobamos que se pueda sacar un boleto
        $this->assertNotFalse($boleto);

        // Comprobamos que el boleto se cree bien
        $this->assertEquals("franquicia normal", $boleto->obtenerTipo());
        $this->assertEquals(Boleto::obtenerMontoNormal(), $boleto->obtenerValor());
    }

    public function circuitoViajePlus()
    {
        $colectivo = new Colectivo("133 negra", "semptur", "1234");
        $tarjeta = new Tarjeta("franquicia completa");
        
        // Usamos los dos viajes plus
        $colectivo->pagarCon($tarjeta);
        $boleto = $colectivo->pagarCon($tarjeta);

        // Comprobamos q se puedan usar dos plus
        $this->assertNotFalse($boleto);

        // Comprobamos que el boleto se cree bien
        $this->assertEquals("plus", $boleto->obtenerTipo());
        $this->assertEquals(0, $boleto->obtenerValor());

        // Comprobamos q no se puedan usar mas viajes
        $this->assertFalse($colectivo->pagarCon($tarjeta));
    }

    public function circuitoMedioBoleto()
    {
        $colectivo = new Colectivo("133 negra", "semptur", "1234");
        $tarjeta = new Tarjeta("media franquicia estudiantil");

        $tarjeta->recargar(15);

        $boleto = $colectivo->pagarCon($tarjeta);

        $this->assertEquals("medio boleto", $boleto->obtenerTipo());
        $this->assertEquals(Boleto::obtenerMedioBoleto(), $boleto->obtenerValor());

        // Usamos los dos viajes plus
        $colectivo->pagarCon($tarjeta);
        $boleto = $colectivo->pagarCon($tarjeta);

        $tarjeta->recargar(100);

        $this->assertEquals($tarjeta->obtenerSaldo(), 100 - 2 * Boleto::obtenerMedioBoleto());
    }
    
    public function circuitoTransbordo() {
        
    }
}
