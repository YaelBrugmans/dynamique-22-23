<?php

namespace App\Listener;

use App\Entity\CollectionCarte;
use App\Entity\Deck;
use App\Entity\ListeDeSouhaits;
use App\Entity\User;

class UserListener
{
    public function __construct(){}

    public function preUpdate(CollectionCarte $collectionCarte, ListeDeSouhaits $listeDeSouhaits, Deck $deck, User $users) {
        $users->setRoles(['ROLE_USER']);
        $collectionCarte->setUser($users);
        $listeDeSouhaits->setUser($users);
        $deck->setUser($users);
    }

}