<?php


namespace App\Service;


class Slugger
{
    public function slugify(string $string)
    {
        $string = strtolower($string);

        return preg_replace('/\W+/', '-', trim(strip_tags($string)));
    }
}

