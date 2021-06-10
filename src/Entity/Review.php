<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 */
class Review
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="isbn")
     */
    private $codLibro;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     */
    private $codUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getCodLibro(): ?Book
    {
        return $this->codLibro;
    }

    public function setCodLibro(?Book $codLibro): self
    {
        $this->codLibro = $codLibro;

        return $this;
    }

    public function getCodUser(): ?User
    {
        return $this->codUser;
    }

    public function setCodUser(?User $codUser): self
    {
        $this->codUser = $codUser;

        return $this;
    }
}
