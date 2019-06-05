<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JobRepository")
 */
class Job
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $country_code;

    /**
     * @ORM\Column(type="boolean")
     */
    private $remote;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $experience_level;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $company_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $company_url;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $company_address_text;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $salary_from;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $salary_to;

    /**
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $salary_currency;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $published_at;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $employment_type;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $skills;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->country_code;
    }

    public function setCountryCode(?string $country_code): self
    {
        $this->country_code = $country_code;

        return $this;
    }

    public function getRemote(): ?bool
    {
        return $this->remote;
    }

    public function setRemote(bool $remote): self
    {
        $this->remote = $remote;

        return $this;
    }

    public function getExperienceLevel(): ?string
    {
        return $this->experience_level;
    }

    public function setExperienceLevel(?string $experience_level): self
    {
        $this->experience_level = $experience_level;

        return $this;
    }

    public function getCompanyName(): ?string
    {
        return $this->company_name;
    }

    public function setCompanyName(?string $company_name): self
    {
        $this->company_name = $company_name;

        return $this;
    }

    public function getCompanyUrl(): ?string
    {
        return $this->company_url;
    }

    public function setCompanyUrl(?string $company_url): self
    {
        $this->company_url = $company_url;

        return $this;
    }

    public function getCompanyAddressText(): ?string
    {
        return $this->company_address_text;
    }

    public function setCompanyAddressText(?string $company_address_text): self
    {
        $this->company_address_text = $company_address_text;

        return $this;
    }

    public function getSalaryFrom(): ?int
    {
        return $this->salary_from;
    }

    public function setSalaryFrom(?int $salary_from): self
    {
        $this->salary_from = $salary_from;

        return $this;
    }

    public function getSalaryTo(): ?int
    {
        return $this->salary_to;
    }

    public function setSalaryTo(?int $salary_to): self
    {
        $this->salary_to = $salary_to;

        return $this;
    }

    public function getSalaryCurrency(): ?string
    {
        return $this->salary_currency;
    }

    public function setSalaryCurrency(?string $salary_currency): self
    {
        $this->salary_currency = $salary_currency;

        return $this;
    }

    public function getPublishedAt(): ?string
    {
        return $this->published_at;
    }

    public function setPublishedAt(?string $published_at): self
    {
        $this->published_at = $published_at;

        return $this;
    }

    public function getEmploymentType(): ?string
    {
        return $this->employment_type;
    }

    public function setEmploymentType(?string $employment_type): self
    {
        $this->employment_type = $employment_type;

        return $this;
    }

    public function getSkills(): ?string
    {
        return $this->skills;
    }

    public function setSkills(?string $skills): self
    {
        $this->skills = $skills;

        return $this;
    }
}
