<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SkillsRepository")
 */
class Skills
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $skill_name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $skill_level;

    /**
     * @ORM\ManyToMany(targetEntity="Job")
     * @ORM\JoinTable(name ="job_skills")
     */
    private $jobsCollection;

    public function __construct()
    {
        $this->jobsCollection = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSkillName(): ?string
    {
        return $this->skill_name;
    }

    public function setSkillName(?string $skill_name): self
    {
        $this->skill_name = $skill_name;

        return $this;
    }

    public function getSkillLevel(): ?int
    {
        return $this->skill_level;
    }

    public function setSkillLevel(?int $skill_level): self
    {
        $this->skill_level = $skill_level;

        return $this;
    }
}
