<?php

interface Auth_Interface {

    public static function getInstance();

    public function authenticate($mailAdress, $password);

    public function getData();

    public function isLogged();

    public function clear();
}