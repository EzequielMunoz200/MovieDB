<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Repository\MovieRepository;
use App\Service\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;

class MovieGetPostersCommand extends Command
{
    protected static $defaultName = 'app:movie:get-posters';

    private $movieRepository;
    private $imageUploader;
    private $em;

    public function __construct(MovieRepository $movieRepository, ImageUploader $imageUploader, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->movieRepository = $movieRepository;
        $this->imageUploader = $imageUploader;
        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setDescription('Download movie posters from OMdbAPI');
        /* ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ; */
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        /* $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        } */

        //On récupère la liste de tous les films
        $movies = $this->movieRepository->findAll();

        //pour chaque film on fait une requête sur OMdbAPI avec le titre
        //on récupère le poster, on le télécharge et on attribut le nom de ce fichier
        foreach ($movies as $movie) {
            //il faut reemplacer les espaces pour %20
            $titleUrl = str_replace(' ', '%20', $movie->getTitle());
            $jsonString = file_get_contents('http://omdbapi.com/?apikey='.$_ENV['KEY_API_OMDB'].'=' . $titleUrl);
            $objectResponse = json_decode($jsonString);
            if ($objectResponse->Response === 'True' && filter_var($objectResponse->Poster, FILTER_VALIDATE_URL)) {
                $image = file_get_contents($objectResponse->Poster);
                $filename = $this->imageUploader->getRandFilename('jpg');
                file_put_contents('public/uploads/movie_images/' . $filename, $image);
                $movie->setImageFilename($filename);
            }
        }

        $this->em->flush();

        $io = new SymfonyStyle($input, $output);
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return 0;
    }
}
