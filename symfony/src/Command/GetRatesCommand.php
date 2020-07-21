<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\BankXml;
use App\Service\CurrencyManager;

class GetRatesCommand extends Command
{
    protected static $defaultName = 'app:get-rates';
    private CurrencyManager $currencyManager;
    private BankXml $bankXml;

    public function __construct(CurrencyManager $currencyManager, BankXml $bankXml)
    {
        parent::__construct();

        $this->currencyManager = $currencyManager;
        $this->bankXml = $bankXml;
    }

    protected function configure()
    {
        $this
            ->setDescription('Šī komanda iegūst valūtas kursu datus no bank.lv')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->text("Iegūst valūtu kursus.");
        $currencies = $this->bankXml->getArray();

        $io->text("Ievieto kursus datubāzē.");
        $this->currencyManager->insertFromArray($currencies);

        $io->success('Valūtu kursi atjaunoti veiksmīgi!');

        return Command::SUCCESS;
    }
}
