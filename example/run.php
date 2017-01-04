<?php
require '../src/Flow.php';
require '../src/Step.php';
require 'Object.php';

use Workflow\Flow;
use Workflow\Step;

/**
 *
 *
 *           +----------------+
 *           |                |
 * +--------->    rejected    |
 * |         |                |
 * |         +-------+--------+
 * |                 |
 * |                 |reedit
 * |                 |
 * |         +-------v--------+
 * |         |                |
 * |         |     normal     |
 * |         |                |
 * |         +-------+--------+
 * |                 |
 * |                 |apply
 * |                 |
 * |         +-------v--------+
 * | reject  |                |
 * +---------+    applyed     |
 *           |                |
 *           +-------+--------+
 *                   |
 *                   |accept
 *                   |
 *           +-------v--------+
 *           |                |
 *           |    accepted    |
 *           |                |
 *           +----------------+
*/

$initStatus = \Object::STATUS_APPLIED;

$flow = new Flow(new Object($initStatus));
echo $flow->object->getStatus();

$flow->addStep(
    (new Step(\Object::STATUS_REJECTED))
        ->setThen(\Object::STATUS_NORMAL)
        ->setElse(\Object::STATUS_NORMAL)
);

$flow->addStep(
    (new Step(\Object::STATUS_NORMAL))
        ->setThen(\Object::STATUS_APPLIED)
        ->setElse(\Object::STATUS_APPLIED)
);

$flow->addStep(
    (new Step(\Object::STATUS_APPLIED))
        ->setThen(\Object::STATUS_ACCEPTED)
        ->setElse(\Object::STATUS_REJECTED)
);

$accept = true;

$flow->run($accept);

echo ' ----> ';

echo $flow->object->getStatus();
