<?php

namespace App\Jobs\Imports\Factory;

use Exception;
use App\Models\ProcessedFile;

class ImportFactoryJob
{
    /**
     * @var string
     */
    private $class;

    /**
     * @param string $class
     *
     * @return void
     */
    public function setClass(string $class): void
    {
        if (!class_exists($class)) {
            throw new Exception("Class {$class} not found");
        }

        $this->class = $class;
    }

    /**
     * @param ProcessedFile $processedFile
     *
     * @return object
     */
    public function create(ProcessedFile $processedFile): object
    {
        return new $this->class($processedFile);
    }
}
