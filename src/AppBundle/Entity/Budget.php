<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BudgetRepository")
 * @ORM\Table(name="budget")
 */
class Budget
{   
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $title;
    /**
     * @ORM\Column(type="decimal", precision=13, scale=4)
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 1,
     *      max = 999999999999.9999,
     *      minMessage = "Budget can't be smaller that 1",
     *      maxMessage = "Budget can't be greater than 999999999999.9999"
     * )
     */
    private $value;
    /**
     * @ORM\Column(type="datetime", name="start_date")
     * @Assert\NotBlank()
     * @Assert\Type("\DateTime")
     */
    private $startDate;
    /**
     * @ORM\Column(type="datetime", name="end_date")
     * @Assert\NotBlank()
     * @Assert\Type("\DateTime")
     * @Assert\Expression(
     *     "this.getStartDate() <= this.getEndDate()",
     *     message="The end date must be after the start date"
     * )
     */
    private $endDate;

    /**
     * Get id
     *
     * @return integer
     */
    
    /**
     * @ORM\OneToMany(targetEntity="Initiative", mappedBy="budget")
     */
    private $initiatives;

    public function __construct()
    {
        $this->initiatives = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }
    /**
     * Set title
     *
     * @param string $title
     *
     * @return Budget
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Budget
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Budget
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Budget
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Add initiative
     *
     * @param \AppBundle\Entity\Initiative $initiative
     *
     * @return Budget
     */
    public function addInitiative(\AppBundle\Entity\Initiative $initiative)
    {
        $this->initiatives[] = $initiative;

        return $this;
    }

    /**
     * Remove initiative
     *
     * @param \AppBundle\Entity\Initiative $initiative
     */
    public function removeInitiative(\AppBundle\Entity\Initiative $initiative)
    {
        $this->initiatives->removeElement($initiative);
    }

    /**
     * Get initiatives
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInitiatives()
    {
        return $this->initiatives;
    }
}
