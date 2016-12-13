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
     * Set Params.
     *
     * @param mixed $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * Set then.
     *
     * @param string $stepId
     * @param mixed  $params
     */
    public function setThen($stepId, $params)
    {
        $this->thenStep = $stepId;
        $this->thenParams = $params;
    }

    /**
     * Set else
     *
     * @param string $stepId
     * @param mixed  $params
     */
    public function setElse($stepId, $params)
    {
        $this->elseStepId = $stepId;
        $this->elseParams = $params;
    }
}
