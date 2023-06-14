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
            ->setFont(base_path() . '/resources/console-fonts/standard.flf')
            ->render('WtaInstaller');
        $result = $this->setAppEnv();
        $result += $this->setCore();

        return $result;
    }

    /**
     * @return int
     */
    protected function setCore(): int
    {
        $this->task('Install Core', function () {

        });
        return self::SUCCESS;
    }
}
