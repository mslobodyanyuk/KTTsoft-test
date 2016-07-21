<?php
namespace App\src\Factory;

use App\src\Composite as C;
use App\src\Iterator as I;

/*
 * Factory creates Composite elements. Factory creates nodes(branches), one by one.
 */
class GoodsFactory implements FactoryInterface{

    /*
     * Method create(array $params) creates a composite. $params - is the data from the file lines.
     */
    public function create(array $params) {
        return new C\CompositeGoods($params['nodeId'],$params['nodeName']);
    }

    /*
     * Method createRoot(array $params) creates a composite. $params - this data to create the composition of the root.
     */
    public function createRoot(array $params) {
        return new C\RootCompositeGoods($params['nodeId'],$params['nodeName']);
    }

}

?>