<?php

namespace App\Command;

use App\Repository\MovieRepository;
use App\Service\Slugger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MovieSlugifyAllCommand extends Command
{
    protected static $defaultName = 'app:movie:slugify-all';

    private $em;
    private $movieRepository;
    private $slugger;

    public function __construct(EntityManagerInterface $em, MovieRepository $movieRepository,  Slugger $slugger)
    {
        parent::__construct();
        
        $this->slugger = $slugger;
        $this->movieRepository = $movieRepository;
        $this->em = $em;
    }

    protected function configure()
    {
        $this->setDescription('Calcule et met à jour le slug de tous les films dans la bdd');
        //->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
        //->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        // ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        /*   $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        } */

        $movies = $this->movieRepository->findAll();
        //dump($movies);
        foreach ($movies as $movie) {
            $slug = $this->slugger->slugify($movie->getTitle());
            $movie->setSlug($slug);
        }

        $this->em->flush();

        $io->success('Tous les films ont maintenant un slug');

        return 0;
    }
}
