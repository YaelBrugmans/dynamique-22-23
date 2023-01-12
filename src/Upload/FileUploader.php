<?php

namespace App\Upload;

use ErrorException;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    public function __construct(private $carteImagePath)
    {
    }

//    upload les fichiers fournits par un formulaire et les place dans le fichier correspondant
    /**
     * @throws ErrorException
     */
    public function uploadFilesFromForm(Form $fileForm, string $folder): void
    {
//        en fonction de si l'image est une carte ou une extansion, via $folder, on récupèrera une cellule différentes
         if ($folder == 'cartes') {
             /**
              * @var UploadedFile $imageFile
              */
             $imageFile = $fileForm->get('image')->getData();
         }
         elseif ($folder == 'expansions'){
             /**
              * @var UploadedFile $imageFile
              */
             $imageFile = $fileForm->get('expansion')->getData();
         }
         else
             throw new ErrorException('le fichier n\'est pas valide');

//         si il trouve un fichier dans les cellules ci-dessus, il créera un nouveau nom pour ce premier et lui écrira un nouveau chemin d'accès
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
            if ($folder == 'cartes' || $folder == 'expansions')
                $newFilename = $folder . '/' . $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
            else
                $newFilename = 'other/' . $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

//            essaye de placer le fichier dans le path indiqué
            try {
                $imageFile->move(
                    $this->carteImagePath . 'img/' . $folder,
                    $newFilename
                );
            } catch (FileException $e) {
//                $this->path('default/index.html.twig');
            }

//            modifie le contenu des cellules pour leur donner les nouvelles valeurs ci-dessus, sinon, il retourne une erreur
             if ($folder == 'cartes')
                 $fileForm->getData()->setImage($newFilename);
             elseif ($folder == 'expansions')
                 $fileForm->getData()->setExpansion($newFilename);
             else
                 throw new ErrorException('le fichier n\'est pas valide');
        }
    }

}
