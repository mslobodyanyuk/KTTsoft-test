<?php

namespace src\Composite;
use src\Iterator as I; 

/*
 * класс RootCompositeGoods. Этот класс в себе должен содержать итератор.
 */
class RootCompositeGoods extends CompositeGoods{
	protected $iterator;
	
	/*
	 * метод getIterator(), возвращает итератор, экземпляр класса IteratorGoods.
	 */
	public function getIterator() {
		return $this->iterator ? $this->iterator : $this->iterator = new I\IteratorGoods($this);
	}

}
?>
