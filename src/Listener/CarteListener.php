<?php

namespace App\Listener;

use App\Entity\Carte;

class CarteListener
{

    public function __construct()
    {
    }

    public function preUpdate(Carte $cartes) {
        $cartes->setPrix(number_format($cartes->getPrix(),2,',',' '));
    }

}
