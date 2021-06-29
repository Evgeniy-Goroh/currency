<?php

namespace App\Command;

use App\Entity\Currency;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Repository\CurrencyRepository;
use Throwable;

/**
 * Class CurrencyCommand
 */
class CurrencyCommand extends Command
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var CurrencyRepository
     */
    private $currencyRepository;

    public const LIST_CURRENCY = 'https://www.cbr.ru/scripts/XML_daily.asp';

    protected static $defaultName = 'app:currency';

    public function __construct(
        LoggerInterface $logger,
        EntityManagerInterface $entityManager,
        CurrencyRepository $currencyRepository,
        string $name = null
    ) {
        parent::__construct($name);

        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->currencyRepository = $currencyRepository;

    }

    protected function configure(): void
    {
        $this->setDescription('Update currency');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $xml = simplexml_load_string(file_get_contents(self::LIST_CURRENCY));

        if ( null !== $xml) {
            try {
                $this->currencyRepository->deleteAllCurrency();
            } catch (Throwable $exception) {
                $this->logger->error(sprintf('Method deleteAllCurrency: %s', $exception->getMessage()));
            }

            foreach ($xml as $value) {
                $currency = new Currency();
                $currency->setName($value->Name);
                $currency->setRate($value->Value);
                $this->entityManager->persist($currency);
            }
        } else {
            $this->logger->error('Not found currency');
        }

        $this->entityManager->flush();

        $this->logger->debug('Statuses currency successfully');

        return Command::SUCCESS;
    }
}
