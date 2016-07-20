<?php
namespace App\src\Factory;

interface FactoryInterface{							
    public function create(array $params); 
	public function createRoot(array $params); 
} 
?>