<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand(
    name: 'app:serializedname',
    description: 'Add a short description for your command',
)]
class SerializednameCommand extends Command
{


    public function __construct(
        private readonly SerializerInterface $serializer
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $json = <<<JSON
{
  "customer": {
    "custnr": "custnr_567035",
    "oxid_number": "oxid_number_567035"
  }
}
JSON;

        $order = $this->serializer->deserialize($json, Order::class, 'json');

        $io->info($order->customer->oxidNumber);

        return Command::SUCCESS;
    }
}

class Order
{
    public function __construct(
        public readonly Customer $customer
    ) {
    }
}

class Customer
{
    public function __construct(
        #[SerializedName('custnr')]
        public readonly string $oxidNumber,
    ) {
    }
}
