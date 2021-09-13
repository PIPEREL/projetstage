<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * @ORM\Column(type="datetime")
     */
    private $start;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxcandidate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $all_day;

    /**
     * @ORM\ManyToOne(targetEntity=Intervenant::class, inversedBy="events")
     */
    private $intervenant;

    /**
     * @ORM\ManyToOne(targetEntity=TypeEvent::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $typeEvent;

    /**
     * @ORM\OneToMany(targetEntity=StudentEvent::class, mappedBy="event", orphanRemoval=true)
     */
    private $studentEvents;

    public function __construct()
    {
        $this->studentEvents = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getMaxcandidate(): ?int
    {
        return $this->maxcandidate;
    }

    public function setMaxcandidate(?int $maxcandidate): self
    {
        $this->maxcandidate = $maxcandidate;

        return $this;
    }

    public function getAllDay(): ?bool
    {
        return $this->all_day;
    }

    public function setAllDay(bool $all_day): self
    {
        $this->all_day = $all_day;

        return $this;
    }

    public function getIntervenant(): ?Intervenant
    {
        return $this->intervenant;
    }

    public function setIntervenant(?Intervenant $intervenant): self
    {
        $this->intervenant = $intervenant;

        return $this;
    }

    public function getTypeEvent(): ?TypeEvent
    {
        return $this->typeEvent;
    }

    public function setTypeEvent(?TypeEvent $typeEvent): self
    {
        $this->typeEvent = $typeEvent;

        return $this;
    }

    /**
     * @return Collection|StudentEvent[]
     */
    public function getStudentEvents(): Collection
    {
        return $this->studentEvents;
    }

    public function addStudentEvent(StudentEvent $studentEvent): self
    {
        if (!$this->studentEvents->contains($studentEvent)) {
            $this->studentEvents[] = $studentEvent;
            $studentEvent->setEvent($this);
        }

        return $this;
    }

    public function removeStudentEvent(StudentEvent $studentEvent): self
    {
        if ($this->studentEvents->removeElement($studentEvent)) {
            // set the owning side to null (unless already changed)
            if ($studentEvent->getEvent() === $this) {
                $studentEvent->setEvent(null);
            }
        }

        return $this;
    }

}
