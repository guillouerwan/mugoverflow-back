<?php

namespace App\Service;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
/**
 * Manage add and delete of pictures in BDD and folders
 */
class PicturesManager
{

    private $params;
    private $filesystem;

    public function __construct(ParameterBagInterface $params, Filesystem $filesystem)
    {
        $this->params = $params;
        $this->filesystem = $filesystem;
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $entity
     * @param [type] $propertyNamePicture
     * @return void
     */
    public function delete($entity, $propertyNamePicture){

        $getImage = "get".$propertyNamePicture;
        $setImage = "set".$propertyNamePicture;

        $path = $this->params->get('images_directory').'/'.$entity->$getImage();

        if(!$this->filesystem->exists($path)){
            return false;
        }

        $this->filesystem->remove($path);

        $entity->$setImage(null);

        return $entity;

    }

}