<?php
namespace src\Iterator;
/*
 * Итератор -класс абстрагирующий за единым интерфейсом доступ к элементам коллекции.
 */
class IteratorGoods implements IteratorInterFace{
    /**
     * @var NodeInterface
     */
    protected $current;

    /**
     * @var NodeInterface
     */
    protected $root;
    public function __construct($root)
    {
        $this->root = $root;
        $this->current = $root;
    }

    /**
     * @param NodeInterface[] $children
     * @param integer         $index
     *
     * @return null|NodeInterface
     */  
    private function find(array $children, $index)
    {
        foreach ($children as $childNode) {
            if ((int)$childNode->getId() == $index) {// !!! (int)
                return $childNode;
            }			
            $node = $this->find($childNode->getChildren(), $index);
            if ($node) {
                return $node;
            }
        }
        
        return null;
    }

    /**
     * @param $index
     *
     */
    public function seek($index){       
		if ((int)$this->root->getId() == $index){// !!! (int) 
			$this->current = $this->root;
			return;
		}
				
		$node = $this->find($this->root->getChildren(), $index);
        if (!$node) {							
		   echo "Can't find element ".$index;
        }
        $this->current = $node;		
				
    }

    /**
     * @return NodeInterface
     */
    public function current(){
        return $this->current;
    }

    public function next(){
        $index = $this->current->getId() + 1;
        $node = $this->find($this->root->getChildren(), $index);
        if ($node) {
            $this->current = $node;
        }
    }
}