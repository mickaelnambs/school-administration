<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\DegreeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=DegreeRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Degree
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Vous devez renseigner le nom de la classe")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Branch::class, inversedBy="degrees")
     * @ORM\JoinColumn(nullable=false)
     */
    private $branch;

    /**
     * @ORM\OneToMany(targetEntity=Student::class, mappedBy="degree", cascade={"remove"})
     */
    private $students;

    /**
     * @ORM\OneToMany(targetEntity=Matter::class, mappedBy="degree", cascade={"remove"})
     */
    private $matters;

    /**
     * @ORM\ManyToMany(targetEntity=Professor::class, inversedBy="degrees")
     */
    private $professors;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * Perment d'initialiser la date de creation.
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return void
     */
    public function prePersist()
    {
        $this->createdAt = new DateTime();
    }

    public function __construct()
    {
        $this->students = new ArrayCollection();
        $this->matters = new ArrayCollection();
        $this->professors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBranch(): ?Branch
    {
        return $this->branch;
    }

    public function setBranch(?Branch $branch): self
    {
        $this->branch = $branch;

        return $this;
    }

    /**
     * @return Collection|Student[]
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): self
    {
        if (!$this->students->contains($student)) {
            $this->students[] = $student;
            $student->setDegree($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): self
    {
        if ($this->students->contains($student)) {
            $this->students->removeElement($student);
            // set the owning side to null (unless already changed)
            if ($student->getDegree() === $this) {
                $student->setDegree(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Matter[]
     */
    public function getMatters(): Collection
    {
        return $this->matters;
    }

    public function addMatter(Matter $matter): self
    {
        if (!$this->matters->contains($matter)) {
            $this->matters[] = $matter;
            $matter->setDegree($this);
        }

        return $this;
    }

    public function removeMatter(Matter $matter): self
    {
        if ($this->matters->contains($matter)) {
            $this->matters->removeElement($matter);
            // set the owning side to null (unless already changed)
            if ($matter->getDegree() === $this) {
                $matter->setDegree(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Professor[]
     */
    public function getProfessors(): Collection
    {
        return $this->professors;
    }

    public function addProfessor(Professor $professor): self
    {
        if (!$this->professors->contains($professor)) {
            $this->professors[] = $professor;
        }

        return $this;
    }

    public function removeProfessor(Professor $professor): self
    {
        if ($this->professors->contains($professor)) {
            $this->professors->removeElement($professor);
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
