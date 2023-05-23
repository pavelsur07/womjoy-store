<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Bus;

use App\Common\Application\Command\CommandBusInterface;
use App\Common\Application\Command\CommandInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

class CommandBus implements CommandBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }

    /**
     * @throws Throwable
     */
    public function execute(CommandInterface $command): mixed
    {
        try {
            return $this->handle($command);
        } catch (HandlerFailedException $exception) {
            throw $exception->getNestedExceptions()[0];
        }
    }
}
