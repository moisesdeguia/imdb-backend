<?php

namespace App\Command;

use App\Entity\Movie;
use App\Entity\Actor;
use App\Entity\Director;
use App\Entity\Genre;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[AsCommand(
    name: 'app:import-movies',
    description: 'Command for import csv of movies into DB',
)]
class ImportMoviesCommand extends Command
{

    private $projectDir;

    private $managerRegistry;

    public function __construct(string $projectDir, ManagerRegistry $managerRegistry)
    {
        $this->projectDir = $projectDir;
        $this->managerRegistry = $managerRegistry;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $movies = $this->csvToArray();
        $counter = 0;

        foreach ($movies as $movieData) {
            if($this->createMovie($movieData))$counter++;
        }
        
        $io = new SymfonyStyle($input, $output);
        $io->success($counter . ' have been added.');

        return Command::SUCCESS;
    }

    public function csvToArray(){

        $file = $this->projectDir . '/public/csv/IMDb movies.csv';

        $encoders       = [new CsvEncoder()];
        $normalizers    = [new ObjectNormalizer()];
        $serializer     = new Serializer($normalizers, $encoders);

        return $serializer->decode(file_get_contents($file), 'csv');
    }

    public function createMovie($movieData){
        
        $entityManager = $this->managerRegistry->getManager();

        if(!$entityManager->getRepository(Movie::class)->findBy(['imdb_id' => $movieData['imdb_title_id']])){

            $movie = new Movie();
            $movie->setTitle($movieData['title']);
            $movie->setDateRelease($this->validateDate($movieData['date_published']));
            $movie->setDuration($this->validateNumber($movieData['duration']));
            $movie->setProductionCompany($movieData['production_company']);
            $movie->setImdbId($movieData['imdb_title_id']);

            foreach ($this->strToArray($movieData['actors']) as $actorName) {
                if($actor = $this->createActor($actorName)){
                    $movie->addActor($actor);
                }
            }
            
            foreach ($this->strToArray($movieData['director']) as $directorName) {
                if($director = $this->createDirector($directorName)){
                    $movie->addDirector($director);
                }
            }
            
            foreach ($this->strToArray($movieData['genre']) as $genreName) {
                if($genre = $this->createGenre($genreName)){
                    $movie->addGenre($genre);
                }
            }

            $entityManager->persist($movie);
            $entityManager->flush();

            return true;
        }

        return false;
        
    }

    public function createActor($actorName){
        $entityManager = $this->managerRegistry->getManager();

        if(!$actor = $entityManager->getRepository(Actor::class)->findOneBy(['name' => $actorName])){
            $actor = new Actor();
            $actor->setName($actorName);
            $entityManager->persist($actor);
            $entityManager->flush();
        } 

        return $actor;
    }
    
    public function createDirector($directorName){
        $entityManager = $this->managerRegistry->getManager();
        if(!$director = $entityManager->getRepository(Director::class)->findOneBy(['name' => $directorName])){
            $director = new Director();
            $director->setName($directorName);
            $entityManager->persist($director);
            $entityManager->flush();
        }
        return $director;
    }
    
    public function createGenre($genreName){
        $entityManager = $this->managerRegistry->getManager();
        if(!$genre = $entityManager->getRepository(Genre::class)->findOneBy(['name' => $genreName])){
            $genre = new Genre();
            $genre->setName($genreName);
            $entityManager->persist($genre);
            $entityManager->flush();
        }
        return $genre;
    }

    public function validateDate($date){
        if (!is_string($date))return null;
        $timestamp = strtotime($date); 
        if (!is_numeric($timestamp))return null; 
        if (checkdate(date('m', $timestamp), date('d', $timestamp), date('Y', $timestamp)) ) return new \DateTime($date); 
    }
    
    public function validateNumber($number){
        if (is_numeric($number))return $number;
        return null; 
    }

    public function strToArray($actors){
        return explode(",", $actors);
    }
}
