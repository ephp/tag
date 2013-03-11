<?php

namespace Ephp\TagBundle\Model;

use Doctrine\ORM\Mapping as ORM;

abstract class BaseRelation {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Ephp\TagBundle\Entity\Tag $tag
     * 
     * @ORM\ManyToOne(targetEntity="Ephp\TagBundle\Entity\Tag")
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     */
    protected $tag;

    /**
     * @var string $campo
     *
     * @ORM\Column(name="campo", type="string", length=32, nullable=true)
     */
    protected $campo;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * 
     * @return \Ephp\TagBundle\Entity\Tag
     */
    public function getTag() {
        return $this->tag;
    }

    /**
     * 
     * @param \Ephp\TagBundle\Entity\Tag $tag
     */
    public function setTag($tag) {
        $this->tag = $tag;
    }

    /**
     * 
     * @return string
     */
    public function getCampo() {
        return $this->campo;
    }

    /**
     * 
     * @param string $campo
     */
    public function setCampo($campo) {
        $this->campo = $campo;
    }

}