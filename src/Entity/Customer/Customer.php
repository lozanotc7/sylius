<?php

declare(strict_types=1);

namespace App\Entity\Customer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Customer as BaseCustomer;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Currency\Model\CurrencyInterface;
use App\Interfaces\Newsletter\NewsletterInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_customer")
 */
class Customer extends BaseCustomer
{
    /**
     * @var Collection|NewsletterInterface[]
     *
     * @psalm-var Collection<array-key, NewsletterInterface>
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Newsletter\Newsletter", inversedBy="subscribers")
     * @ORM\JoinTable(name="newsletter_customer")
     */
    protected $newsletters;

    public function __construct()
    {
        parent::__construct();

        /** @var ArrayCollection<array-key, NewsletterInterface> $this->newsletters */
        $this->newsletters = new ArrayCollection();
    }

    public function getNewsletters(): Collection
    {
        return $this->newsletters;
    }

    public function addNewsletter(NewsletterInterface $newsletter): void
    {
        if (!$this->hasNewsletter($newsletter)) {
            $this->newsletters->add($newsletter);
        }
    }

    public function removeNewsletter(NewsletterInterface $newsletter): void
    {
        if ($this->hasNewsletter($newsletter)) {
            $this->newsletters->removeElement($newsletter);
        }
    }

    public function hasNewsletter(NewsletterInterface $newsletter): bool
    {
        return $this->newsletters->contains($newsletter);
    }
}
