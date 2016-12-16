<?php
namespace Workflow;

interface FlowObjectInterface
{
    /**
     * Flow Object on this step.
     *
     * @param string $stepId
     * @param string $runParams
     *
     * @return bool
     */
    public function onStep($stepId, $runParams = null);

    /**
     * Do next step.
     *
     * @param string $stepId
     * @param mixed  $params
     * @param mixed  $runParams
     *
     * @return bool
     */
    public function nextStep($stepId, $params = null, $runParams = null);
}
