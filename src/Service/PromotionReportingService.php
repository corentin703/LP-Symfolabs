<?php

namespace App\Service;

use App\Entity\Promotion;
use App\Repository\UserRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class PromotionReportingService implements PromotionReportingServiceInterface
{
    private MailerInterface $mailer;
    private UserRepository $userRepository;

    /**
     * @param MailerInterface $mailer
     */
    public function __construct(MailerInterface $mailer, UserRepository $userRepository)
    {
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }

    public function reportToAdmins(Promotion $promotion) {
        $admins = $this->userRepository->findAllAdmins();

        $email = new Email();
        $email = $email->from('report@symfolabs.fr');

        foreach ($admins as $admin) {
            $email = $email->to($admin->getEmail());
        }

        $email
            ->subject('Promotion ' . $promotion->getId() . ' signalée')
            ->text('La promotion ' . $promotion->getId() . ' - ' . $promotion->getTitle() . ' a été signalée. ' . 'Lien d\'accès /promotion/' . $promotion->getId());

        $this->mailer->send($email);
    }

}