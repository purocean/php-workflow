<?php
namespace Workflow;

interface FlowObjectInterface
{
    /**
     * Flow Object on this step.
     *
     * @param string $stepId
     * @param mixed  $pass
     * @param string $runParams
     *
     * @return bool
     */
    public function onStep($stepId, $pass, $runParams = null);

    /**
     * Do next step.
     *
     * @param string $stepId
     * @param mixed  $pass
     * @param mixed  $params
     * @param mixed  $runParams
     *
     * @return bool
     */
    public function nextStep($stepId, $pass, $params = null, $runParams = null);
}
