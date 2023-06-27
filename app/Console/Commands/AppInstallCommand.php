<?php

namespace App\Console\Commands;

use Database\Seeders\CountrySeeder;
use Database\Seeders\ZoneSeeder;
use Illuminate\Console\Command;
use Laminas\Text\Figlet\Figlet;

class AppInstallCommand extends Command
{
    use Concerns\WithStubTrait;
    use Concerns\WithEnvTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wta:install
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
            $this->setAppEnv();

            return self::SUCCESS;
        });

        if ($this->option('seed')) {
            $this->task('Install Env', function () {
                $this->call('db:seed', ['--class' => CountrySeeder::class]);
                $this->call('db:seed', ['--class' => ZoneSeeder::class]);

                return self::SUCCESS;
            });
        }

        $this->callSilent('key:generate');
        $this->callSilent('optimize:clear');
        $this->callSilent('config:clear');
        $this->callSilent('cache:clear');

        return self::SUCCESS;
    }
}
