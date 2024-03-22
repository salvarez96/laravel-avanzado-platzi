<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\NewsletterNotification;
use Illuminate\Console\Command;
use Illuminate\Console\Concerns\InteractsWithIO;

class SendNewsletterCommand extends Command
{
    // protected InteractsWithIO $output;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:newsletter {emails?*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a newsletter to any specified email(s)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $emails = $this->argument('emails');

        $builder = User::query();

        if ($emails) {
            $builder->whereIn('email', $emails);
        }

        $count = $builder->count();

        if ($count) {
            if (User::whereNotNull('email_verified_at')->count() > 0) {
                $userCount = User::whereNotNull('email_verified_at')->count();

                $this->_output()->progressStart($userCount);

                User::whereNotNull('email_verified_at')
                    ->each(function (User $user) {
                        $user->notify(new NewsletterNotification());
                        $this->_output()->progressAdvance();
                    });

                $this->_output()->progressFinish();
                $this->info("Se ". ($userCount === 1 ? "envió {$userCount} correo al usuario verificado." : "enviaron {$userCount} correos a usuarios verificados."));
                $this->newLine();
            }

            if (isset($emails[0])) {
                $this->_output()->progressStart($count);

                $builder->whereNull('email_verified_at')
                    ->each(function (User $user) {
                        $user->notify(new NewsletterNotification("Welcome {$user->name} :)"));
                        $this->_output()->progressAdvance();
                    });

                $this->_output()->progressFinish();
                $this->info("Se ". ($count === 1 ? "envió {$count} correo al usuario no verificado." : "enviaron {$count} correos a los usuarios no verificados."));
                $this->newLine();
            }
        } else {
            $this->info('No se envió ningún correo.');
            $this->newLine();
        }

    }

    /**
     * Use a defined version of `$this->output` to avoid IDE mistakenly marking it as a warning.
     */
    private function _output() {
        /** @var InteractsWithIo $this->output */
        return $this->output;
    }
}
