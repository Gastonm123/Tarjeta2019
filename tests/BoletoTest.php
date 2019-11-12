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

    public function circuitoViajePlus()
    {
        $colectivo = new Colectivo("133 negra", "semptur", "1234");
        $tarjeta = new Tarjeta("franquicia completa");

        $tarjeta->recargar(30);

        $boleto = $colectivo->pagarCon($tarjeta);

        $this->assertEquals("franquicia normal", $boleto->obtenerTipo());
        $this->assertEquals(Boleto::obtenerMontoNormal(), $boleto->obtenerValor());

        // Usamos los dos viajes plus
        $colectivo->pagarCon($tarjeta);
        $boleto = $colectivo->pagarCon($tarjeta);

        $this->assertEquals("plus", $boleto->obtenerTipo());
        $this->assertEquals(0, $boleto->obtenerValor());

        // Detectar que error tira y esperarlo
        $colectivo->pagarCon($tarjeta);
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

        $this->assertEquals($tarjeta->)
    }

    // public function testTransbordo()
    // {
    //     $colectivo = new Colectivo("133 negra", "semptur", "1234");
    //     $tarjeta = new Tarjeta("medio universitario");

    // }    
}
