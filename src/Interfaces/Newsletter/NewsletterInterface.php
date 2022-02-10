<?php
declare(strict_types=1);

namespace App\Interfaces\Newsletter;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\ResourceInterface;

interface NewsletterInterface extends ResourceInterface
{
    public function getSubject(): ?string;
    public function setSubject(?string $subject): void;

    public function getContent(): ?string;
    public function setContent(?string $content): void;

    public function getSubscribers(): ?Collection;
}
