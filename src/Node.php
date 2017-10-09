<?php

namespace Workflow;

class Node
{
    const STATE_UNREACHABLE = 0;
    const STATE_PENDDING    = 1;
    const STATE_DONE        = 2;

    const TYPE_BEGIN     = 'BEGIN';
    const TYPE_AND_SPLIT = 'AND_SPLIT';
    const TYPE_AND_JOIN  = 'AND_JOIN';
    const TYPE_END       = 'END';

    const TYPE_NORMAL    = 'NORMAL';
    const TYPE_BACK      = 'BACK';
    const TYPE_EMPTY     = 'EMPTY';

    public $id;
    public $type;
    public $name;
    public $ext;

    public function __construct($id, $name, $type = self::TYPE_NORMAL, $ext = null)
    {
        $this->id = $id;
        $this->type = $type;
        $this->name = $name;
        $this->ext = $ext;
    }
}
