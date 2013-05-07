<?php

namespace Ephp\TagBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Ephp\TagBundle\Entity\Tag
 *
 * @ORM\Table(name="tag_tags")
 * @ORM\Entity(repositoryClass="Ephp\TagBundle\Entity\TagRepository")
 */
class Tag {

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
     * @ORM\Column(name="tag", type="string", length=255)
     */
    private $tag;

    /**
     * @var text $descrizione
     *
     * @ORM\Column(name="descrizione", type="text", nullable=true)
     */
    private $descrizione;

    /**
     * @var string $favicon
     *
     * @ORM\Column(name="favicon", type="string", length=64, nullable=true)
     */
    private $favicon;

    /**
     * @var boolean $pubblico
     *
     * @ORM\Column(name="pubblico", type="boolean", nullable=true)
     */
    private $pubblico;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $catalogo
     *
     * @ORM\ManyToMany(targetEntity="Gruppo", mappedBy="tags", cascade={"persist", "merge", "refresh"})
     */
    private $gruppi;

    /**
     * @Gedmo\Slug(fields={"tag"}, unique=true)
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    function __construct() {
        $this->gruppi = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param string $tag
     */
    public function setTag($tag) {
        $this->tag = $tag;
    }

    /**
     * Get tag
     *
     * @return string 
     */
    public function getTag() {
        return $this->tag;
    }

    /**
     * Set descrizione
     *
     * @param text $descrizione
     */
    public function setDescrizione($descrizione) {
        $this->descrizione = $descrizione;
    }

    /**
     * Get descrizione
     *
     * @return text 
     */
    public function getDescrizione() {
        return $this->descrizione;
    }

    /**
     * Set favicon
     *
     * @param string $favicon
     */
    public function setFavicon($favicon) {
        $this->favicon = $favicon;
    }

    /**
     * Get favicon
     *
     * @return string 
     */
    public function getFavicon() {
        return $this->favicon;
    }

    /**
     * Set pubblico
     *
     * @param boolean $pubblico
     */
    public function setPubblico($pubblico) {
        $this->pubblico = $pubblico;
    }

    /**
     * Get pubblico
     *
     * @return boolean 
     */
    public function getPubblico() {
        return $this->pubblico;
    }

    public function getGruppi() {
        return $this->gruppi;
    }

    public function setGruppi($gruppi) {
        $this->gruppi = $gruppi;
    }

    public function addGruppi($gruppi) {
        $this->gruppi->add($gruppi);
    }

    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
    }

    public function __toString() {
        return $this->tag;
    }

}