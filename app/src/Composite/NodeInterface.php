<?php
namespace App\src\Composite;

interface NodeInterface{

    /*
     * Returns the node id (branch)
     */
    public function getId();

    /*
     *  Returns the node name (branch)
     */
    public function getName();

    /*
     * Adds a branch (node), $node
     */
    public function addChild(NodeInterface $node);

    /*
     * Returns the descendants of $node
     */
    public function getChildren();

    /*
     * Returns a one-dimensional array of data to display.
     */
    public function getDataToPrint($hyphen);
}
?>