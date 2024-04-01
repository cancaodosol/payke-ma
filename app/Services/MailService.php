<?php

namespace App\Services;

use App\Mail\ErrorMail;
use App\Services\UserService;

use Illuminate\Contracts\Mail\Mailer;

class MailService
{
    public Mailer $mailer;
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    function send_to_admin(string $title, string $message, $jsondata = [], array $log = [])
    {
        $service = new UserService();
        $admins = $service->find_admin_users();
        foreach($admins as $admin)
        {
            $this->mailer->to($admin->email)
               ->send(new ErrorMail($title, $message, $jsondata, $log));
        }
    }
}