<?php

namespace App\Factory;

use Sylius\Component\Channel\Model\Channel;
use App\Entity\Customer\Customer;
use App\Entity\Newsletter\Newsletter;

use Swift_Message;
use Twig\Environment;

final class NewsletterMailFactory
{
    const SENDER_EMAIL = 'jonh.doe@localhost',
        SENDER_NAME = 'Netenders';

    private $twig,
        $subscriber,
        $newsletter,
        $locale,
        $channel;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function build(Customer $subscriber, Newsletter $newsletter, Channel $channel, string $locale)
    {
        return (new Swift_Message())
            ->setFrom(static::SENDER_EMAIL, static::SENDER_NAME)
            ->setTo($subscriber->getEmail(), $subscriber->getFirstName())
            ->setSubject($newsletter->getSubject())
            ->setBody(
                $this->twig->render(
                    'email/newsletter.html.twig',
                    [
                        'channel'    => $channel,
                        'localeCode' => $locale,
                        'subject'    => $newsletter->getSubject(),
                        'content'    => $newsletter->getContent(),
                        'firstname'  => $subscriber->getFirstName(),
                    ]
                )
            );
    }
}
