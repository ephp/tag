<?php

namespace Ephp\TagBundle\Model;

interface IModelTag {

    public function getTags() ;
    public function setTags($tags);
    public function addTags($tag);
    public function addTag(\Ephp\TagBundle\Entity\Tag $_tag, $campo);
    public function getTag($key, $array = false);
    public function getAllTag();
    
}
