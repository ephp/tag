<?php

namespace Ephp\TagBundle\Model;

use Doctrine\ORM\Mapping as ORM;

trait ModelTagTrait { 
    
    public function addCreateTag(\Doctrine\ORM\EntityManager $em, $tag_name, $campo, $descrizione = '') {
        $_tag = $em->getRepository('EphpTagBundle:Tag');
        $tag = $_tag->findOneBy(array('tag' => $tag_name));
        if(!$tag) {
            $tag = new \Ephp\TagBundle\Entity\Tag();
            $tag->setTag($tag_name);
            $tag->setFavicon($campo);
            $tag->setDescrizione($descrizione);
            $tag->setPubblico(true);
            $em->persist($tag);
            $em->flush();
        }
        $this->addTag($tag, $campo);
    }        

    /**
     * get tags
     *
     * @return Doctrine\Common\Collections\ArrayCollection 
     */
    public function getTags() {
        return $this->tags;
    }

    /**
     * set tags
     *
     * @param Doctrine\Common\Collections\ArrayCollection $tabs 
     */
    public function setTags($tabs) {
        $this->tags = $tabs;
        return $this;
    }

    /**
     * add tag
     *
     * @param Tag $tag 
     */
    public function addTags($tag) {
//       \Ephp\UtilityBundle\Utility\Debug::pr(count($this->tags), true);
        $this->tags->add($tag);
//       \Ephp\UtilityBundle\Utility\Debug::pr(count($this->tags), true);
        return $this;
    }
    
    /**
     * add tag
     *
     * @param Tag $tag 
     */
    public function addTag(\Ephp\TagBundle\Entity\Tag $_tag, $campo) {
        $class = $this->getRelationModel();
        $setter = $this->getSetterModel();
        $tag = new $class();
        $tag->setTag($_tag);
        $tag->setCampo($campo);
        $tag->$setter($this);
        $this->addTags($tag);
    }

    public function getTag($key, $array = false) {
        $out = $array ? array() : false;
        foreach ($this->tags as $tag) {
            if (strtolower($tag->getCampo()) == strtolower($key)) {
                if ($array) {
                    $out[] = $tag->getTag();
                } else {
                    return $tag->getTag();
                }
            }
        }
        return $out;
    }

    public function getAllTag() {
        $out = array();
        foreach ($this->tags as $tag) {
            $out[] = $tag->getTag();
        }
        return $out;
    }

}