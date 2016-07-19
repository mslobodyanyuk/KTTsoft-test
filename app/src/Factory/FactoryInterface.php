<?php
namespace src\Factory;

interface FactoryInterface{							
    public function create(array $params); 
	public function createRoot(array $params); 
} 
?>