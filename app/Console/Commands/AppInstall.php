<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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
        sleep(2);
        $this->setCore();

        return self::SUCCESS;
    }

    protected function setCore(): void
    {
        $this->task('Install Core', function () {
            $result = $this->call('exceptions:install');
            $result += $this->call('shield:install');
            $result += $this->call('shield:generate');
            return $result;
        });
    }
}
