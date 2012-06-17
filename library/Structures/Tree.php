<?php

class Structures_Tree implements Iterator
{
    private $root;
    private $list;
    private $keys;
    private $values;
    private $orphans;

    public function  __construct() {
        $this->root = new Structures_Tree_Node(0);
        $this->list = array();
        $this->keys = array();
        $this->values = array();
        $this->orphans = array();
    }

    public function addNode(Structures_Tree_Structurable $node) {
        $ident = $node->getIdent();
        $parent = $node->getParent();
        
        $this->values[$ident] = $node;

        $this->list[$ident] = new Structures_Tree_Node($ident);

        if (empty($parent)) {
            $this->root->addChild($this->list[$ident]);
        } else {
            if (array_key_exists($parent, $this->list)) {
                $this->list[$parent]->addChild($this->list[$ident]);
            } else {
                if (!isset($this->orphans[$parent])) {
                    $this->orphans[$parent] = array();
                }
                $this->orphans[$parent][] = $ident;
            }
        }

        if (isset($this->orphans[$ident])) {
            foreach ($this->orphans[$ident] as $orphan) {
                $this->list[$ident]->addChild($this->list[$orphan]);
            }
            unset($this->orphans[$ident]);
        }
    }

    public function getOrphans() {
        return $this->orphans;
    }
    
    public function __toString() {
        return (string) $this->root;
    }

    public function indexAll() {
        $this->index($this->root, 0);
    }

    private function index($node, $level) {
        foreach ($node->getChildren() as $child) {
            $this->keys[] = array('level' => $level, 'key' => $child->getIdent());
            $this->index($child, $level + 1);
        }
    }

    private $pointer;

    public function rewind() {
        $this->pointer = 0;
    }

    public function key() {
        return $this->pointer;
    }

    public function current() {
        $key = $this->keys[$this->pointer];
        return array(
            'level' => $key['level'],
            'node' => $this->values[$key['key']],
        );
    }

    public function next() {
        ++$this->pointer;
    }

    public function valid() {
        return ($this->pointer < count($this->keys));
    }
}
