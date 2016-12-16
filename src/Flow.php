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
     *
     * @param bool  $pass
     * @param mixed $params
     *
     * @return array
     */
    public function run($pass, $params)
    {
        $result = [];
        foreach ($this->steps as $step) {
            if ($object->onStep($step->id, $params)) {
                if ($pass) {
                    $object->nextStep($step->thenStepId, $step->thenParams, $params)
                        and $result[$step->thenStep] = true;
                } else {
                    $object->nextStep($step->elseStepId, $step->elseParams, $params)
                        and $result[$step->elseStepId] = true;
                }
            }
        }

        return $result;
    }
}
