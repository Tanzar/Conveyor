<?php

namespace Tanzar\Conveyor\Console;

use Illuminate\Console\GeneratorCommand;

class MakeConveyorCommand extends GeneratorCommand
{
    protected $name = 'make:conveyor';

    protected $description = 'Create a new conveyor class';

    protected $type = 'ConveyorCore';

    protected function getStub()
    {
        return __DIR__ . '/stubs/ConveyorCore.php.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Conveyor';
    }

    public function handle()
    {
        parent::handle();

        $this->doOtherOperations();
    }

    protected function doOtherOperations()
    {
        // Get the fully qualified class name (FQN)
        $class = $this->qualifyClass($this->getNameInput());

        // get the destination path, based on the default namespace
        $path = $this->getPath($class);

        $content = file_get_contents($path);

        // Update the file content with additional data (regular expressions)

        file_put_contents($path, $content);
    }
}