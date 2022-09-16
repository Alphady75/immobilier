<?php

namespace App\Service;

use App\Entity\Immobilier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;

class MailerService {

	public function __construct(private MailerInterface $mailer){

	}

	public function sendEmailForImmobilier(
		$from, 
		$to,
		$message, 
		$username, 
		Immobilier $immobilier
	){
		// Envoie de mail
		$email = (new TemplatedEmail())
			->from($from)
			->to($to)
			->subject('Mail de contact depuis le site')
			->htmlTemplate('mails/_immobilier.html.twig')
			->context([
				'user' => $username,
				'useremail'  =>  $from,
				'message'   =>  $message,
				'immobilier' => $immobilier
			])
		;

		return $this->mailer->send($email);
	}

	public function sendMail($from, $to, $username, $message){


		$email = (new TemplatedEmail())
			->from($from)
			->to($to)
			->subject('Mail de contact depuis le site')
			->htmlTemplate('mails/_contact.html.twig')
			->context([
				'user' => $username,
				'useremail'  =>  $from,
				'message'   =>  $message
			])
		;

		return $this->mailer->send($email);
	}

}