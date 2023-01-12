<?php

namespace App\Listener;

use App\Entity\Commentaire;

class CommentaireListener
{
    public function __construct()
    {
    }

    public function preUpdate(Commentaire $commentaire) {
        $commentaire->setDateCommentaire(new \DateTime());
    }

}
