<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
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

    protected string|null $appName = 'WTA';
    protected string|null $dbHost = '127.0.0.1';
    protected string|null $dbPort = '3306';
    protected string|null $dbName = 'wtadmin';
    protected string|null $dbUser = 'admin';
    protected string|null $dbPassword = 'password01!';

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

        return $result;
    }
}
