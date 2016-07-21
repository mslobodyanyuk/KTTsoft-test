<?php

namespace App\src\Composite;
use App\src\Iterator as I;

/*
 * Class RootCompositeGoods. This class should contain a iterator.
 */
class RootCompositeGoods extends CompositeGoods{
    protected $iterator;

    /*
     * Method getIterator() returns an iterator, IteratorGoods instance.
     */
    public function getIterator() {
        return $this->iterator ? $this->iterator : $this->iterator = new I\IteratorGoods($this);
    }

}
?>
