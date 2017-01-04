<?php
require '../src/FlowObjectInterface.php';

/**
* An example Object
*/
class Object implements \Workflow\FlowObjectInterface
{
    const STATUS_NORMAL  = 0;
    const STATUS_APPLIED = 10;
    const STATUS_ACCEPTED  = 100;
    const STATUS_REJECTED  = 999;

    public $status = 0;

    public function __construct($initStatus)
    {
        $this->status = $initStatus;
    }

    /**
     * @inheritdoc FlowObjectInterface
     */
    public function onStep($stepId, $runParams = null)
    {
        return $stepId === $this->status;
    }

    /**
     * @inheritdoc FlowObjectInterface
     */
    public function nextStep($stepId, $params = null, $runParams = null)
    {
        $this->status = $stepId;
        // You can save status
    }

    public function getStatus()
    {
        return [
            self::STATUS_NORMAL => 'normal',
            self::STATUS_APPLIED => 'applied',
            self::STATUS_ACCEPTED => 'accepted',
            self::STATUS_REJECTED => 'rejected',
        ][$this->status];
    }
}
