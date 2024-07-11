<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Console\Cart;

use App\Common\Infrastructure\Doctrine\Flusher;
use App\Store\Infrastructure\Repository\CartRepository;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'cart:email:first',
    description: 'Cart send email first.',
    aliases: ['cart:email:first'],
    hidden: false
)]
class CartSendFirstEmailTrigger extends Command
{
    public function __construct(
        private readonly CartRepository $carts,
        private readonly Flusher $flusher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            /*foreach ($this->carts->getOldCarts() as $cart) {
                $this->carts->remove($cart);
            }
            $this->flusher->flush();*/
        } catch (Exception $e) {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
