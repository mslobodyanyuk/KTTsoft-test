<?php
namespace src\Composite;

/* 
 * класс Composite (Компоновщик),компоновщик(по айди выстраивает ветки(children,дети))
 * Компоновщик - и есть дерево, он хранит данные.
 *		node_id | parent_id | node_name
 *		node_id: числовой идентификатор узла
 *		parent_id: идентификатор родительского узла
 * Элементы Cоmposite создает Factory.
 * Composite - структура(коллекция) элементов(данных, нод) в виде дерева.
 * Компоновщик (англ. Composite pattern) — структурный шаблон проектирования,
 * относится к структурным паттернам, объединяет объекты в древовидную структуру
 * для представления иерархии от частного к целому. 
 * Компоновщик позволяет клиентам обращаться к отдельным объектам и к группам объектов одинаково.
 */
class CompositeGoods implements NodeInterface{
	private $id;
	private $name;  
	private $children = array(); // private т.к. мы используем только ветки.

	public function __construct($id,$name){
		$this->id = $id;
		$this->name = $name;
	}		
	
	/*
	 * возвращает id узла(ветки)
	 */
	public function getId(){					
		return $this->id; 
	}
	
	/*
	 * возвращает имя узла(ветки)
	 */
	public function getName(){
		return $this->name; 
	}
	
	/*
	 *	возвращает потомков $node  
	 */ 
	public function getChildren(){		
		return $this -> children;
	}
		
	/*
	 * добавляет ветку(узел), $node 
	 */
	public function addChild(NodeInterface $node){
		$this->children[] = $node;
	}			
	
	/*
	 * выводит дерево на экран
	 */
	public function display(){				
		print($this->id)." ".($this->name)."<br>";	
		foreach($this->children as $child){			
			$child->display();
		}		
	}
		
	/*
	 * возвращает одномерный массив данных для вывода на экран.
	 */		
	public function getDataToPrint($hyphen = ''){
		$hyphen .= ' - ';
		$dataToPrint = array($hyphen.$this->name);
		
		foreach ($this->children as $child){   
			$dataToPrint = array_merge($dataToPrint, $child->getDataToPrint($hyphen));
		}

		return $dataToPrint;
	}
	
} 

?>