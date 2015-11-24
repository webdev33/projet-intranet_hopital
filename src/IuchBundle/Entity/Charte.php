<?php

namespace IuchBundle\Entity;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Fichier
 */
class Charte
{
    /**
     * @var File $file
     */
    public $file;

    protected function getUploadDir()
    {
        return 'uploads';
    }

    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../app/'.$this->getUploadDir();
    }

    public function getAbsolutePath()
    {
        return null === $this->charte_name ? null : $this->getUploadRootDir().'/'.$this->charte_name;
    }

    public function preUpload()
    {
        if (null !== $this->file) {
            // do whatever you want to generate a unique name
            $this->charte_name = uniqid().'.'.$this->file->guessExtension();
        }
    }

    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->file->move($this->getUploadRootDir(), $this->charte_name);

        unset($this->file);
    }

    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

    // GENERATED CODE
    /**
     * @var integer
     */
    protected $id;
    /**
     * @var string
     */
    protected $file_name;
    /**
     * @var string
     */
    private $nom;
    /**
     * @var string
     */
    private $description;

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
     * Set file_name
     *
     * @param string $file_name
     * @return Charte
     */
    public function setFileName($file_name)
    {
        $this->file_name = $file_name;
        return $this;
    }
    /**
     * Get file_name
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->file_name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Charte
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Charte
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }
    /**
     * @var \IuchBundle\Entity\Service
     */
    private $services;


    /**
     * Set services
     *
     * @param \IuchBundle\Entity\Service $services
     *
     * @return Charte
     */
    public function setServices(\IuchBundle\Entity\Service $services = null)
    {
        $this->services = $services;

        return $this;
    }

    /**
     * Get services
     *
     * @return \IuchBundle\Entity\Service
     */
    public function getServices()
    {
        return $this->services;
    }
    /**
     * @var \IuchBundle\Entity\Service
     */
    private $service;


    /**
     * Set service
     *
     * @param \IuchBundle\Entity\Service $service
     *
     * @return Charte
     */
    public function setService(\IuchBundle\Entity\Service $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \IuchBundle\Entity\Service
     */
    public function getService()
    {
        return $this->service;
    }
    /**
     * @var string
     */
    private $charte_name;


    /**
     * Set charteName
     *
     * @param string $charteName
     *
     * @return Charte
     */
    public function setCharteName($charteName)
    {
        $this->charte_name = $charteName;

        return $this;
    }

    /**
     * Get charteName
     *
     * @return string
     */
    public function getCharteName()
    {
        return $this->charte_name;
    }
}
