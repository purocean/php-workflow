<?php
namespace Workflow;

/**
* Flow
*/
class Flow
{
    public $object = null;
    public $action = null;
    public $steps = [];


    /**
     * Attachable object implement FlowFlowObjectInterface.
     *
     * @param FlowFlowObjectInterface $object
     */
    public function __construct($object)
    {
        $this->object = $object;
    }

    /**
     * Add step.
     *
     * @param Step $step
     */
    public function addStep($step)
    {
        $this->steps[] = $step;
    }

    /**
     * Run flow.
     */
    public function run()
    {
        foreach ($this->steps as $step) {
            if ($object->onStep($step->id)) {
                if ($object->passStep($step->params)) {
                    $object->nextStep($step->thenStepId, $step->thenParams);
                } else {
                    $object->nextStep($step->elseStepId, $step->elseParams);
                }
            }
        }
    }
}

