<?php

namespace Minds\Core\Email\Batches;

class Factory
{
    /**
     * Build the Batch
     * @param  string $batch
     * @param  array $options (optional)
     * @return EmailBatchInterface
     */
    public static function build($batch, $options = array(), $db = null)
    {
        $batch = ucfirst($batch);
        $batch = "Minds\\Core\\Email\\Batches\\$batch";
        if (class_exists($batch)) {
            $class = new $batch($options, $db);
            if ($class instanceof EmailBatchInterface) {
                return $class;
            }
        }
        throw new \Exception("Batch not found");
    }
}