<?php

namespace App\Entity\Newsletter;

use App\Entity\Customer\Customer;
use App\Interfaces\Newsletter\NewsletterInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_newsletter")
 */
class Newsletter implements NewsletterInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string|null
     * @ORM\Column(type="string")
     */
    protected $subject;

    /**
     * @var string|null
     * @ORM\Column(type="string")
     */
    protected $content;

    /**
     * @var Collection|Customer[]
     *
     * @psalm-var Collection<array-key, Customer>
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Customer\Customer", inversedBy="newsletters")
     * @ORM\JoinTable(name="newsletter_customer")
     */
    protected $subscribers;

    public function __construct()
    {
        $this->subscribers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): void
    {
        $this->subject = $subject;
    }

    public function getContent(): ?string
    {
        return  $this->content;
    }

    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function getSubscribers(): ?Collection
    {
        return $this->subscribers;
    }

    public function __toString()
    {
        return $this->getSubject();
    }
}
