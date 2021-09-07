<?php

namespace App\Entity;

use App\Repository\IntervenantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $code_exam;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $perstudent;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="intervenant", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="intervenant")
     */
    private $events;


    public function __construct()
    {
        $this->sessions = new ArrayCollection();
        $this->events = new ArrayCollection();
    }

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setIntervenant($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getIntervenant() === $this) {
                $event->setIntervenant(null);
            }
        }

        return $this;
    }

}
