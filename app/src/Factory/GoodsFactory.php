<?php
namespace App\src\Factory;

use App\src\Composite as C;
use App\src\Iterator as I;

/*
 * Factory создает элементы Cоmposite. Фабрика создает ноды(ветки), по одной.
 */
class GoodsFactory implements FactoryInterface{

	/*
	 * метод create(array $params) создаёт композит. $params - это данные из файла, строки.
	 */
	public function create(array $params) {
		return new C\CompositeGoods($params['nodeId'],$params['nodeName']);
	}
	
	/*
	 * метод createRoot(array $params) создаёт композит. $params - это данные для создания корня композиции.
	 */
	public function createRoot(array $params) {
		return new C\RootCompositeGoods($params['nodeId'],$params['nodeName']);  
	}	
	
}
 
?>