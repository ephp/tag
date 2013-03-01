<?php

namespace Ephp\TagBundle\Model;

use Doctrine\ORM\Mapping as ORM;

abstract class BaseModel {

    /**
     * @var ArrayCollection $tags
     *
     * @ORM\OneToMany(targetEntity="TagRelation", mappedBy="post", cascade={"persist", "remove", "merge", "refresh"})
     */
    protected $tags;

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
        $this->tags->add($tag);
        return $this;
    }
    
    /**
     * add tag
     *
     * @param Tag $tag 
     */
    public function addTag(\Ephp\TagBundle\Entity\Tag $_tag, $campo) {
        $tag = new Tag();
        $tag->setTag($_tag);
        $tag->setCampo($campo);
        $tag->setPost($this);
        $this->tags->add($tag);
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