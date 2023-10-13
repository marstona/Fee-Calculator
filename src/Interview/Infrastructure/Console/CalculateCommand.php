<?php

declare(strict_types=1);

namespace PragmaGoTech\Interview\Infrastructure\Console;

use Money\Currencies\ISOCurrencies;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use PragmaGoTech\Interview\Application\Factory\PLNMoneyFactory;
use PragmaGoTech\Interview\Application\Service\Calculator\RoundedFixedFeeCalculator;
use PragmaGoTech\Interview\Domain\Exception\InterviewException;
use PragmaGoTech\Interview\Domain\ValueObject\LoanProposal;
use PragmaGoTech\Interview\Domain\ValueObject\LoanTerm;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'pragmago:calculator:calculate',
)]
final class CalculateCommand extends Command
{
    /**
     * @param PLNMoneyFactory           $moneyFactory
     * @param RoundedFixedFeeCalculator $feeCalculator
     */
    public function __construct(
        private readonly PLNMoneyFactory $moneyFactory,
        private readonly RoundedFixedFeeCalculator $feeCalculator
    ) {
        parent::__construct();
    }

    /**
     * @param  SymfonyStyle $io
     * @return Money
     */
    public function askLoanAmount(SymfonyStyle $io): Money
    {
        return $io->ask(
            'Enter loan amount',
            null,
            function (string $input) {
                return $this->moneyFactory->createMoney($input);
            },
        );
    }

    /**
     * @param  SymfonyStyle $io
     * @return LoanTerm
     */
    public function askLoanTerm(SymfonyStyle $io): LoanTerm
    {
        return $io->ask(
            'Enter loan term',
            null,
            function (int $input) {
                return LoanTerm::fromInt($input);
            },
        );
    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Loan Fee Calculation');

        $term = $this->askLoanTerm($io);
        $amount = $this->askLoanAmount($io);

        try {
            $application = new LoanProposal($term, $amount);
            $fee = $this->feeCalculator->calculate($application);

            $moneyFormatter = new DecimalMoneyFormatter(new ISOCurrencies());

            $io->createTable()
                ->setHeaders(['Loan amount', 'Term', 'Fee'])
                ->addRow([
                    $moneyFormatter->format($amount) . ' PLN',
                    sprintf('%d months', $term->toInt()),
                    $moneyFormatter->format($fee) . ' PLN',
                ])
                ->render();

            return Command::SUCCESS;
        } catch (InterviewException $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }
    }
}
