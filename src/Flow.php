<?php

namespace Workflow;

/**
* Flow
*/
class Flow
{
    public $id;
    public $name;
    public $lines = [];
    public $nodes = [];
    public $ext;

    public function __construct($id, $name, $ext = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->ext = $ext;
    }

    public function addLine(Line $line)
    {
        $this->lines[$line->id] = $line;
    }

    public function setLines($lines)
    {
        foreach ($lines as $line) {
            $this->addLine($line);
        }
    }

    public function addNode(Node $node)
    {
        $this->nodes[$node->id] = $node;
    }

    public function setNodes($nodes)
    {
        foreach ($nodes as $node) {
            $this->addNode($node);
        }
    }
}
