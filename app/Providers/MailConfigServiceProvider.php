<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;
use App\Models\Option;

class MailConfigServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->configureMail();
        $this->listenForOptionChanges();
    }

    protected function configureMail()
    {
        $smtp_email = \Options::getValue('smtp_email');
        $smtp_password = \Options::getValue('smtp_password');
        $smtp_port = \Options::getValue('smtp_port');
        $smtp_host = \Options::getValue('smtp_host');
        // $email_protocol = \Options::getValue('email_protocol');

        if($smtp_email) {
            config([
                'mail.from.address', $smtp_email
            ]);
        }

        if($smtp_password) {
            config([
                'mail.mailers.smtp.password', $smtp_password
            ]);
        }

        if($smtp_port) {
            config([
                'mail.mailers.smtp.port' => $smtp_port,
            ]);
        }

        if($smtp_host) {
            config([
                'mail.mailers.smtp.host' => $smtp_host,
            ]);
        }

        // Mail::refresh();
    }

    protected function listenForOptionChanges()
    {
        Option::updated(function ($option) {
            $smtp_options = ['smtp_email', 'smtp_password', 'smtp_port'. 'smtp_host'];
            if(in_array($option->name, $smtp_options)) {
        echo "yes";
                $this->configureMail();
            }
        });
    }
}
