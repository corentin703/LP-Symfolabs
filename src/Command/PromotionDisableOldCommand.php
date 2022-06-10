<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PromotionDisableOldCommand extends Command
{
    protected static $defaultName = 'promotion:disable-old';
    protected static $defaultDescription = 'Disable promotions older than an amount of mouth';

    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('mouth', InputArgument::OPTIONAL, 'Max mouth old (1 by default)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $mouth = $input->getArgument('mouth');

        if ($mouth === null) {
            $mouth = 1;
        }
        else if (is_int($mouth))
        {
            $io->error("The mouth amount must be an integer");
            return Command::FAILURE;
        }

        if ($mouth)
        {
            $io->note(sprintf('Disabling promotions older than %i mouth.', $mouth));
        }

        $rsm = new ResultSetMapping();
        $this->entityManager
            ->createNativeQuery(
                "UPDATE promotion SET is_disabled = 1 WHERE created_at < :dateTime",
                $rsm
            )
            ->setParameter('dateTime',
                $this->makeDateTime($mouth)
            )
            ->execute()
            ;

        $io->text($this->makeDateTime($mouth));

        return Command::SUCCESS;
    }

    private function makeDateTime(int $mouths): string {
        $now = (new \DateTime())->getTimestamp();
        return date("Y-m-d H:i:s", strtotime("-" . $mouths . " months", $now));
    }
}
