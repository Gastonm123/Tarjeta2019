<?php

class base {
    public function hola() {
	echo 'hola mundo';
    }
}

class hijo extends base {
    public function test() {
	$this->hola();
    }
}

$cosa = new hijo;

$cosa->test();
