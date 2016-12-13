<?php
namespace Workflow;

interface FlowObjectInterface
{
    /**
     * Flow Object on this step.
     *
     * @param string $stepId
     *
     * @return bool
     */
    public function onStep($stepId);

    /**
     * Pass this Step.
     *
     * @param mixed $params
     *
     * @return bool
     */
    public function passStep($params);

    /**
     * Do next step.
     *
     * @param string $stepId
     * @param mixed  $params
     */
    public function nextStep($stepId, $params);
}
