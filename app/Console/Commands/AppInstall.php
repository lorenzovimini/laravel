<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Env;
use Laminas\Text\Figlet\Figlet;

class AppInstall extends Command
{
    use Concerns\WithStubTrait;
    use Concerns\WithEnvTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install
        {--force : Overwrite existing files}
        {--seed : Install seeds}
        {--debug : Debug mode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Wta Admin Installer';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $figlet = new Figlet();
        echo $figlet
            ->setFont(base_path().'/resources/console-fonts/standard.flf')
            ->render('WtaInstaller');
        $this->task('Install Env', function () {
            return $this->setAppEnv();
        });
        $this->callSilent('key:generate');
        $this->callSilent('optimize:clear');
        $this->callSilent('config:clear');
        $this->callSilent('cache:clear');
        return self::SUCCESS;
    }
}
