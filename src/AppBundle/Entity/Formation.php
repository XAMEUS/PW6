<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Formation
 *
 * @ORM\Table(name="formation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FormationRepository")
 */
class Formation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="contents", type="text")
     */
    private $contents;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var float
     *
     * @ORM\Column(name="cost", type="float")
     */
    private $cost;

    /**
     * @var int
     *
     * @ORM\Column(name="duration", type="integer")
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="prerequisites", type="text")
     */
    private $prerequisites;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="applicants",
     *      joinColumns={@ORM\JoinColumn(name="formation_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *      )
     */
    private $applicants;

    /**
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="intraining",
     *      joinColumns={@ORM\JoinColumn(name="formation_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     *      )
     */
    private $intraining;



    public function __construct()
    {
        $this->applicants = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set contents
     *
     * @param string $contents
     *
     * @return Formation
     */
    public function setContents($contents)
    {
        $this->contents = $contents;

        return $this;
    }

    /**
     * Get contents
     *
     * @return string
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Formation
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set cost
     *
     * @param float $cost
     *
     * @return Formation
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return float
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return Formation
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set prerequisites
     *
     * @param string $prerequisites
     *
     * @return Formation
     */
    public function setPrerequisites($prerequisites)
    {
        $this->prerequisites = $prerequisites;

        return $this;
    }

    /**
     * Get prerequisites
     *
     * @return string
     */
    public function getPrerequisites()
    {
        return $this->prerequisites;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Formation
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add applicant
     *
     * @param \AppBundle\Entity\User $applicant
     *
     * @return Formation
     */
    public function addApplicant(\AppBundle\Entity\User $applicant)
    {
        $this->applicants[] = $applicant;

        return $this;
    }

    /**
     * Remove applicant
     *
     * @param \AppBundle\Entity\User $applicant
     */
    public function removeApplicant(\AppBundle\Entity\User $applicant)
    {
        $this->applicants->removeElement($applicant);
    }

    /**
     * Get applicants
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getApplicants()
    {
        return $this->applicants;
    }

    /**
     * Add intraining
     *
     * @param \AppBundle\Entity\User $intraining
     *
     * @return Formation
     */
    public function addIntraining(\AppBundle\Entity\User $intraining)
    {
        $this->intraining[] = $intraining;

        return $this;
    }

    /**
     * Remove intraining
     *
     * @param \AppBundle\Entity\User $intraining
     */
    public function removeIntraining(\AppBundle\Entity\User $intraining)
    {
        $this->intraining->removeElement($intraining);
    }

    /**
     * Get intraining
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIntraining()
    {
        return $this->intraining;
    }
}
