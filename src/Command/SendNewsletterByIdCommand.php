<?php

namespace App\Command;

use App\Factory\NewsletterMailFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Swift_Mailer;
use Swift_Message;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Mailer\Model\EmailInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mime\Address;
use Twig\Environment;

class SendNewsletterByIdCommand extends Command
{
    const SENDER_EMAIL = 'jonh.doe@localhost',
        SENDER_NAME = 'Netenders';

    /*
     * Mailer should come with symfony
     */
    private Swift_Mailer $mailer;
    private EntityManagerInterface $em;
    private NewsletterMailFactory $factory;

    public function __construct(
        EntityManagerInterface $em,
        Swift_Mailer $mailer,
        NewsletterMailFactory $factory
    ) {
        parent::__construct();
        $this->em     = $em;
        $this->mailer = $mailer;
        $this->factory   = $factory;
    }

    protected function configure()
    {
        $this
            ->setName('netenders:newsletter:send')
            ->setDescription('Sends a newsletter by id')
            ->setDefinition(
                new InputDefinition(
                    [
                        new InputOption('id', null, InputOption::VALUE_REQUIRED),
                    ]
                )
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $idNewsletter = $input->getOption('id');
        $newsletter   = $this->em->getRepository('App:Newsletter\Newsletter')->find($idNewsletter);

        /*
         * Maybe MailChimp API or something similar should be used instead of mailer
         */
        foreach ($newsletter->getSubscribers() as $subscriber) {
            $email = $this->factory->build(
                $subscriber,
                $newsletter,
                $this->em->getRepository('Sylius\Component\Channel\Model\Channel')->find(1),
                'en_UK'
            );

            $this->mailer->send($email);
        }
    }
}
