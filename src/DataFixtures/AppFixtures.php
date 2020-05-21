<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Movie;
use App\Entity\MovieActor;
use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as Fk;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Fk::create();

        $categoryList = [];
        $categories = [
            "Action", "Adventure", "Animation", "Biography", "Comedy",
       "Crime", "Documentary", "Drama",
        "Family", "Fantasy", "Film Noir", "History", "Horror", "Music", "Musical", "Mystery", "Romance", "Sci-Fi",
        "Short Film", "Sport", "Superhero", "Thriller", "War", "Western"
        ];
        foreach ($categories as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $categoryList[] = $category;
            $manager->persist($category);
        }

        $personList = [];
        for ($i = 0; $i < 500; $i++) {
            $person = new Person();
            $person->setName($faker->name());
            $person->setBirthDate($faker->dateTimeThisCentury());
            $personList[] = $person;
            $manager->persist($person);
        }

        $movieList = [];
        for ($i = 0; $i < 100; $i++) {
            $movie = new Movie();
            $movie->setTitle($faker->catchPhrase());
            $director = $personList[mt_rand(0, count($personList) - 1)];
            $writer1 = $personList[mt_rand(0, count($personList) - 1)];
            $writer2 = $personList[mt_rand(0, count($personList) - 1)];
            $movie->setDirector($director);
            $movie->addWriter($writer1);
            $movie->addWriter($writer2);

            for ($j = 0; $j < mt_rand(1, 3); $j++) {
                $category = $categoryList[mt_rand(0, count($categoryList) - 1)];
                if (!$movie->getCategories()->contains($category)) {
                    $movie->addCategory($category);
                }
            }


            $movie->setReleaseDate($faker->dateTimeThisCentury());
            $movieList[] = $movie;
            $manager->persist($movie);
        }

        
        foreach($movieList as $movie){
            $actor = [];
            for ($i = 0; $i < 3; $i++) {
                $movieactor = new MovieActor();
                $movieactor->setCharacterName($faker->name());
                $movieactor->setMovie($movie);
                $actor[$i] = ($personList[mt_rand(0, count($personList) - 1)]);
                $movieactor->setPerson($actor[$i]);
                $manager->persist($movieactor);
            }
            
        }

        $manager->flush();
    }
}
