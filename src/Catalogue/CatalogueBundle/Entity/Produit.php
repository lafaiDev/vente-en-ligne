<?php

namespace Catalogue\CatalogueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Catalogue\CatalogueBundle\Entity\ProduitRepository")
 */
class Produit
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\OneToOne(targetEntity="Catalogue\CatalogueBundle\Entity\Photo", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $photo;

    /**
     * @ORM\ManyToOne(targetEntity="Catalogue\CatalogueBundle\Entity\Categorie", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity="Catalogue\CatalogueBundle\Entity\ProduitOption", mappedBy="produit", cascade={"persist","remove"})
     */
    private $optionsProduit;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Produit
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->optionsProduit = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set photo
     *
     * @param \Catalogue\CatalogueBundle\Entity\Photo $photo
     *
     * @return Produit
     */
    public function setPhoto(\Catalogue\CatalogueBundle\Entity\Photo $photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return \Catalogue\CatalogueBundle\Entity\Photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set categorie
     *
     *@param \Catalogue\CatalogueBundle\Entity\Categories $categorie
     *
     *@return Produit
     */
    public function setCategorie(\Catalogue\CatalogueBundle\Entity\Categorie $categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }


    /**
     * Get categorie
     *
     * @return \Catalogue\CatalogueBundle\Entity\Categories
     */
    public function getCategorie()
    {
        return $this->categorie;
    }



    /**
     * Add optionsProduit
     *
     * @param \Catalogue\CatalogueBundle\Entity\ProduitOption $optionsProduit
     *
     * @return Produit
     */
    public function addOptionsProduit(\Catalogue\CatalogueBundle\Entity\ProduitOption $optionsProduit)
    {
        $this->optionsProduit[] = $optionsProduit;

        return $this;
    }

    /**
     * Remove optionsProduit
     *
     * @param \Catalogue\CatalogueBundle\Entity\ProduitOption $optionsProduit
     */
    public function removeOptionsProduit(\Catalogue\CatalogueBundle\Entity\ProduitOption $optionsProduit)
    {
        $this->optionsProduit->removeElement($optionsProduit);
    }

    /**
     * Get optionsProduit
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOptionsProduit()
    {
        return $this->optionsProduit;
    }
}
