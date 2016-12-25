<?php
namespace Workflow;

/**
* Step
*/
class Step
{
    public $id = null;
    public $params = null;
    public $thenStepId = null;
    public $thenParams = null;
    public $elseStepId = null;
    public $elseParams = null;


    /**
     * New step.
     *
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Set then.
     *
     * @param string $stepId
     * @param mixed  $params
     *
     * @return Step $this
     */
    public function setThen($stepId, $params = null)
    {
        $this->thenStepId = $stepId;
        $this->thenParams = $params;

        return $this;
    }

    /**
     * Set else
     *
     * @param string $stepId
     * @param mixed  $params
     *
     * @return Step $this
     */
    public function setElse($stepId, $params = null)
    {
        $this->elseStepId = $stepId;
        $this->elseParams = $params;

        return $this;
    }
}
