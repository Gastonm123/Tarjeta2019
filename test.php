<?php

class Hola {
    protected $tipo="hola";
    public function tipo() {
	return $this->tipo;
    }
}

$cosa = new Hola;

echo $cosa->tipo() . '\n';
echo time() . '\n';
echo date('d-m-Y', time()) . '\n';
echo date('l');
