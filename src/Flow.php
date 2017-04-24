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
    public function run($pass, $params = null)
    {
        $result = [];
        foreach ($this->steps as $step) {
            if ($this->object->onStep($step->id, $pass, $params)) {
                if ($pass) {
                    $this->object->nextStep($step->thenStepId, $pass, $step->thenParams, $params)
                        and $result[$step->thenStepId] = true;
                } else {
                    $this->object->nextStep($step->elseStepId, $pass, $step->elseParams, $params)
                        and $result[$step->elseStepId] = true;
                }

                break;
            }
        }

        return $result;
    }
}
