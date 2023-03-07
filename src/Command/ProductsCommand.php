<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\HttpClient\CurlHttpClient as HttpClient;


#[AsCommand(name: 'app:product',description: 'Add a short description for your command')]

class ProductsCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $client = new HttpClient();
        
        $products = $client->request('GET' , 'http://localhost:8001/prisma/products');
        $array_output = json_decode($products->getContent(), true);
        
        $products_disabled = $client->request('GET' , 'http://localhost:8001/prisma/products/disabled');
        $array_output = json_decode($products_disabled->getContent(), true);
        
        $products_custom_fields = $client->request('GET' , 'http://localhost:8001/prisma/products/custom-fields');
        $array_output = json_decode($products_custom_fields->getContent(), true);

        $messages = '';

        if($array_output) foreach($array_output as $key => $value){
            $messages .= $key .' => ' . $value;
        }

        $io->success($messages);
        
        return Command::SUCCESS;
    }
}
