# php-Workflow
    A simple exclusive workflow lib

## Usage
```php
$flow = new Flow($object);

$flow->addStep(
    (new Step($currentStepId))
        ->setThen($acceptStepId)
        ->setElse($rejectStepId)
);

$flow->run(true);
```

## Example
```bash
cd example
php run.php
```
