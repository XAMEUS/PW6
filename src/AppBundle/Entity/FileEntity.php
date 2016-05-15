<?php
// src/AppBundle/Entity/FileEntity.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="file")
 */
class FileEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string",unique=true)
     * @Assert\NotBlank(message="Please, upload a file.")
     * @Assert\File(maxSize="64k")
     */
    private $path;

    /**
     * @ORM\Column(type="string")
     */
    private $originalname;


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
     * Set originalname
     *
     * @param string $originalname
     *
     * @return FileEntity
     */
    public function setOriginalname($originalname)
    {
        $this->originalname = $originalname;

        return $this;
    }

    /**
     * Get originalname
     *
     * @return string
     */
    public function getOriginalname()
    {
        return $this->originalname;
    }


    /**
     * Set path
     *
     * @param string $path
     *
     * @return FileEntity
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
