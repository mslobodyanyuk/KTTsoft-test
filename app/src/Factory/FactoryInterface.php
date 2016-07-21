<?php
namespace App\src\Factory;

interface FactoryInterface{

    /*
     * Method create(array $params) creates a composite. $params - is the data from the file lines.
     */
    public function create(array $params);

    /*
     * Method createRoot(array $params) creates a composite. $params - this data to create the composition of the root.
     */
    public function createRoot(array $params);
}
?>