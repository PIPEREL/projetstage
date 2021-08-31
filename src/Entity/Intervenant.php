<?php

namespace App\Entity;

use App\Repository\IntervenantRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IntervenantRepository::class)
 */
class Intervenant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $dailyrate;

    /**
     * @ORM\Column(type="float")
     */
    private $halfDayRate;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="intervenant", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $code_exam;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $perstudent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDailyrate(): ?float
    {
        return $this->dailyrate;
    }

    public function setDailyrate(float $dailyrate): self
    {
        $this->dailyrate = $dailyrate;

        return $this;
    }

    public function getHalfDayRate(): ?float
    {
        return $this->halfDayRate;
    }

    public function setHalfDayRate(float $halfDayRate): self
    {
        $this->halfDayRate = $halfDayRate;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setIntervenant(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getIntervenant() !== $this) {
            $user->setIntervenant($this);
        }

        $this->user = $user;

        return $this;
    }

    public function getCodeExam(): ?string
    {
        return $this->code_exam;
    }

    public function setCodeExam(?string $code_exam): self
    {
        $this->code_exam = $code_exam;

        return $this;
    }

    public function getPerstudent(): ?float
    {
        return $this->perstudent;
    }

    public function setPerstudent(float $perstudent): self
    {
        $this->perstudent = $perstudent;

        return $this;
    }
}