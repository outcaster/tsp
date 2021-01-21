<?php
declare(strict_types=1);
namespace App\Business\Listener;

use Symfony\Component\Console\Event\ConsoleErrorEvent;

/**
 * ExceptionListener
 */
class ExceptionListener
{
    /**
     * onConsoleError
     *
     * @param  mixed $event
     * @return void
     */
    public function onConsoleError(ConsoleErrorEvent $event)
    {
        //any additional treatment for the command exception could be here.
        //I will keep symfony exception listener also active for more comfortability
        $event
            ->getOutput()
            ->writeln(
                "Something went wrong trying to process your command: " . $event->getError()->getMessage()
            );
    }
}
