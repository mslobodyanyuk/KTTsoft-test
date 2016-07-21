<?php
namespace App\src\Composite;

/*
 * Class of the Composite, Composite (by id builds branches (children))
 * Composite - and there is a tree, it stores data.
 * Node_id | parent_id | node_name
 * Node_id: numeric node ID
 * Parent_id: ID of the parent node
 * Composite Elements creates Factory.
 * Composite - structure (collection) of the elements (data nodes) in a tree.
 * Composite (Composite pattern.) - Structural pattern,
 * refers to the structural patterns, brings together objects in a tree structure
 * to represent the hierarchy from the private to the whole.
 * Composite allows clients to access the individual objects and groups of objects of the same.
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
     * Returns the node id (branch)
     */
    public function getId(){
        return $this->id;
    }

    /*
     *  Returns the node name (branch)
     */
    public function getName(){
        return $this->name;
    }

    /*
     * Returns the descendants of $node
     */
    public function getChildren(){
        return $this -> children;
    }

    /*
     * Adds a branch (node), $node
     */
    public function addChild(NodeInterface $node){
        $this->children[] = $node;
    }

    /*
     * Displays a tree on screen
     */
    public function display(){
        print($this->id)." ".($this->name)."<br>";
        foreach($this->children as $child){
            $child->display();
        }
    }

    /*
     * Returns a one-dimensional array of data to display.
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