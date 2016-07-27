<?php
namespace Minds\Interfaces;

/**
 * Interface for "CLI Controllers"-like objects
 */
interface CliControllerInterface
{
    /**
     * Echoes $commands (or overall) help text to standard output.
     * @param  string|null $command - the command to be executed. If null, it corresponds to exec()
     * @return null
     */
    public function help($command = null);

    /**
     * Executes the default command for the controller.
     * @return mixed
     */
    public function exec();

    /**
     * Sets the flags and/or arguments for the controller. If the first argument
     * matches a public controller method name, that will be executed instead of exec() and
     * ignored from arguments list.
     * @param array $args
     */
    public function setArgs(array $args);

    /**
     * Gets the method to be executed. Setted on $this->setArgs().
     * @return string
     */
    public function getExecCommand();
}
