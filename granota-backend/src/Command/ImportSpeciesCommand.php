<?php
namespace App\Command;

use App\Entity\Species;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader; 
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;


#[AsCommand(
    name: 'app:import-species',
    description: 'Pobla la base de dades amb espècies',
)]

class ImportSpeciesCommand extends Command
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setDescription('Import species from a CSV file')
            ->addArgument('file', InputArgument::REQUIRED, 'Path to the CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $file = $input->getArgument('file');

        if (!file_exists($file) || !is_readable($file)) {
            $output->writeln("<error>CSV file does not exist or is not readable</error>");
            return Command::FAILURE;
        }

        $csv = Reader::createFromPath($file, 'r');
        $csv->setHeaderOffset(0); 

        $records = $csv->getRecords();

        foreach ($records as $record) {
            $species = new Species();
            $species->setCommonName($record['commonName'] ?? '');
            $species->setScientificName($record['scientificName'] ?? null);
            $species->setType($record['type'] ?? '');
            $species->setObservations($record['observations'] ?? null);
            $species->setImagenes([]);

            $this->em->persist($species);
        }

        $this->em->flush();

        $output->writeln('Import completed.');

        return Command::SUCCESS;
    }
}
