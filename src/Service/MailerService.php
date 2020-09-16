<?php
namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

class MailerService
{
    private $mailer;

    private $logger;

    private $emailSendFrom;
    
    public function __construct(string $emailSendFrom, \Swift_Mailer $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->emailSendFrom = $emailSendFrom;
    }
    
    public function sendMessage(string $topic, string $mailTo, string $body): bool
    {
        $message = (new \Swift_Message($topic))
            ->setFrom($this->emailSendFrom)
            ->setTo($mailTo)
            ->setBody($body, 'text/html')
        ;

        try {
            $this->mailer->send($message);
        } catch (\Exception $e) {
            $this->logger->log('info', $e->getMessage());
            throw new Exception();
        }

        return true;
    }
}

