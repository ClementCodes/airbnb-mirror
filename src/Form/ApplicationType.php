<?php

// Que sont les espaces de noms ? Dans la définition la plus large, les espaces de noms sont un moyen d'encapsuler des éléments. Cela peut être considéré comme un concept abstrait dans de nombreux endroits. Par exemple, dans n'importe quel système d'exploitation, les répertoires servent à regrouper les fichiers associés et agissent comme un espace de noms pour les fichiers qu'ils contiennent. À titre d'exemple concret, le fichier foo.txtpeut exister à la fois dans répertoire /home/greget dans /home/other, mais deux copies de foo.txtne peuvent pas coexister dans le même répertoire. De plus, pour accéder au foo.txtfichier en dehors du /home/gregrépertoire, nous devons ajouter le nom du répertoire au nom du fichier en utilisant le séparateur de répertoire pour obtenir /home/greg/foo.txt. Ce même principe s'étend aux espaces de noms dans le monde de la programmation.
namespace Ap\Form;


use Symfony\Component\Form\AbstractType;



class ApplicationType extends AbstractType
{


    //pour rappel mettre une ption par defaut comme $options pour pouvoir avoir une focntion avec duex parametre sou un seul parametre
    /**
     * Permet d'avoir la configuration de base d'un champ de texte 
     
     * @param string $label
     * @param string $placeholder
     * @param array $otpions
     *  
     * @return  array
     */
    public function getConfiguration($label, $placeholder, $options  = [])
    {
        return array_merge([
            "label" => $label,
            "attr" => [
                'placeholder' =>  $placeholder
            ]
        ], $options);
    }
}
