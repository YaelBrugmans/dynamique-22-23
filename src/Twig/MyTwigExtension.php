<?php

namespace App\Twig;

use NumberFormatter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MyTwigExtension extends AbstractExtension
{

    public function getFilters() {
        return [
            new TwigFilter('prix', [$this, 'prix']),
            new TwigFilter('removeExpansionExtension', [$this, 'removeExpansionExtension'])
        ];
    }

//  filtre pour retirer le nom du dossier d'images et de l'extansion d'un fichier image
    public function removeExpansionExtension(string $file): array|string {
        $file = str_replace('expansions/', '', $file);
        $file = str_replace('cartes/', '', $file);
        return pathinfo($file, PATHINFO_FILENAME);
    }

//    filtre pour changer le format de l'integer du prix, pour le mettre en format "0.00" EUR
    public function prix(int $prix): string {
        $fmt = new NumberFormatter( 'fr_FR', NumberFormatter::CURRENCY );
        return $fmt->formatCurrency($prix, "EUR");
    }

}
