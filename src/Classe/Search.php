<?php 

namespace App\Classe;

use App\Entity\Category;

class Search{


    /**
     * Recherche par mot
     *
     * @var string
     */
    public $string = '';


    /**
     * Recherche par catégorie
     *
     * @var Category[]
     */
    public $categories = [];
}