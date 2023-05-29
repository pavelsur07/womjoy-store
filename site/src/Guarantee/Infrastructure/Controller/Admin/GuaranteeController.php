<?php

declare(strict_types=1);

namespace App\Guarantee\Infrastructure\Controller\Admin;

use App\Guarantee\Domain\Repository\GuaranteeRepositoryInterface;
use DateTime;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/guarantee', name: 'guarantee.admin')]
class GuaranteeController extends AbstractController
{
    #[Route(path: '/', name: '.index')]
    public function index(Request $request, GuaranteeRepositoryInterface $guarantees): Response
    {
        return $this->render(
            'guarantee/admin/index.html.twig',
            [
                'pagination'=> $guarantees->list(),
            ]
        );
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route(path: '/new', name: '.new')]
    public function new(Request $request, MailerInterface $mailer): Response
    {
        $email = new TemplatedEmail();
        $email->from(Address::create('Pavel Novikov <from@example.ru>'));
        $email->to(Address::create('Fabien Potencier <to@example.ru>'));
        $email->subject('new subject');
        $email->htmlTemplate('emails/test.html.twig');
        $email->context(
            [
                'message'=> 'Message hello world',
                'expiration_date'=>  new DateTime('+7 days'),
            ]
        );

        $mailer->send($email);
        return $this->redirectToRoute('guarantee.admin.index');
        /*return $this->render(
            'guarantee/admin/index.html.twig',
            [
                'form'=> [],
            ]
        );*/
    }
}
