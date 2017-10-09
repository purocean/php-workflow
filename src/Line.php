<?php

namespace Workflow;

/**
* Line
*/
class Line
{
    public $id;
    public $name;
    public $from;
    public $to;
    public $condition;

    public function __construct($id, $name, $from, $to, $condition = 'true')
    {
        $this->id = $id;
        $this->name = $name;
        $this->from = $from;
        $this->to = $to;
        $this->condition = $condition;
    }

    public static function connect(Node $from, Node $to, $condition = 'true')
    {
        return new self("{$from->id}_to_{$to->id}", "{$from->id}_to_{$to->id}", $from->id, $to->id, $condition);
    }
}
