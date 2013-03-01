<?php

namespace Ephp\TagBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ephp\TagBundle\Entity\Gruppo
 *
 * @ORM\Table(name="tag_gruppi")
 * @ORM\Entity(repositoryClass="Ephp\TagBundle\Entity\GruppoRepository")
 */
class Gruppo {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $tag
     *
     * @ORM\Column(name="tag", type="string", length=64)
     */
    private $tag;

    /**
     * @var string $tag
     *
     * @ORM\Column(name="sigla", type="string", length=64)
     */
    private $sigla;
    
    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="gruppi", cascade={"persist", "merge", "refresh"})
     * @ORM\JoinTable(name="tag_tag_gruppi",
     *      joinColumns={@ORM\JoinColumn(name="gruppo_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     * )
     * @ORM\OrderBy({"tag" = "ASC"}) 
     */
    private $tags;

    /**
     * @var string $descrizione
     *
     * @ORM\Column(name="descrizione", type="string", length=255)
     */
    private $descrizione;

    function __construct() {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set tag
     *
     * @param Tag $tag
     */
    public function setTag($tag) {
        $this->tag = $tag;
    }

    /**
     * Get tag
     *
     * @return Tag 
     */
    public function getTag() {
        return $this->tag;
    }

    /**
     * Set tag
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $tags
     */
    public function setTags($tags) {
        $this->tags = $tags;
    }

    /**
     * Get tag
     *
     * @param Tag
     */
    public function addTags($tag) {
        $this->tags->add($tag);
    }
    
    /**
     * Get tag
     *
     * @return \Doctrine\Common\Collections\ArrayCollection 
     */
    public function getTags() {
        return $this->tags;
    }

    /**
     * Set descrizione
     *
     * @param string $descrizione
     */
    public function setDescrizione($descrizione) {
        $this->descrizione = $descrizione;
    }

    /**
     * Get descrizione
     *
     * @return string 
     */
    public function getDescrizione() {
        return $this->descrizione;
    }

    public function getSigla() {
        return $this->sigla;
    }

    public function setSigla($sigla) {
        $this->sigla = $sigla;
    }

    public function contiene(Tag $tag) {
        foreach ($this->tags as $_tag) {
            if ($tag->getId() == $_tag->getId()) {
                return true;
            }
        }
        return false;
    }
    
}