<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\NewsletterNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Console\Concerns\InteractsWithIO;

class NotifyUnverifiedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:unverified-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send unverified users a notification to verify their emails';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $build = User::query();

        $count = $build->whereNull('email_verified_at')->count();

        if ($count > 0) {
            $this->_output()->progressStart($count);

            $build->whereNull('email_verified_at')->each(function (User $user) {
                $user->notify(new NewsletterNotification("Verify your email {$user->name}"));
                $this->_output()->progressAdvance();
            });

            $this->_output()->progressFinish();

            $this->info("Se " . ($count === 1 ? "envió correo de verificación al usuario." : "enviaron correos de verificación a {$count} usuarios."));
            $this->newLine();
            return;
        }

        $this->info('Todos los usuarios están verificados');
        $this->newLine();
    }

    private function _output() {
        /** @var InteractsWithIo $this->output */
        return $this->output;
    }
}
