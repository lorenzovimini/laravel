<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppPostInstallCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:post-install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Wta Admin Post Installer';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->setCore();

        return self::SUCCESS;
    }

    protected function setCore(): void
    {
        $this->task('Install Core', function () {
            $result = $this->call('exceptions:install');
            $result += $this->call('shield:install',['--minimal']);
            $result += $this->call('shield:generate', ['--minimal']);
            return $result;
        });
    }
}
