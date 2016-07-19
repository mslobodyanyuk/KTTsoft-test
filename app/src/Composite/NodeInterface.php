<?php
namespace src\Composite;

interface NodeInterface{	
	public function getId();
	public function getName();
	public function addChild(NodeInterface $node);
	public function getChildren();	
	public function getDataToPrint($hyphen);	
}
?>