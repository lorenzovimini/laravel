<?php

namespace App\Console\Commands;

use App\Console\Commands\Concerns\WithStubTrait;
use Filament\Support\Commands\Concerns\CanValidateInput;
use Illuminate\Console\Command;
use Illuminate\Support\Pluralizer;

class WTAResourceCommand extends Command
{
    use WithStubTrait;
    use CanValidateInput;

    protected $signature = 'WTA:resource
                    {--resource= : The name of the resource}
                    {--model= : The name of the model}';

    protected $description = 'Command description';

    protected array $options;
    protected string $stubsBasePath = __DIR__ . '/../../stubs/';
    protected string $rootPath = __DIR__ . '/../../';

    public function handle(): void
    {
        $this->getData();
        $this->createResource();
        $this->info('Resource created successfully.');

    }

    /**
     * @return array
     */
    protected function getData(): array
    {
        $this->options = [
            'resource' => $this->askValidate('resource', 'Resource name', ['required', 'string', 'alpha_dash']),
            'model' => $this->askValidate('model', 'Model name', ['required', 'string'])
        ];
        $this->options = [$this->options, ...[
            'namespace' => 'App',
            'models' => strtolower(Pluralizer::plural($this->options['model']))]];
        return $this->options;
    }

    protected function createResource(): void
    {
        //$this->copyResourceStubToApp($this->options['resource']);
        //$this->createDirectory();
        //$this->createResourceFile();
        //$this->createResourcePages();
        //$this->createResourceRelationManagers();
        //$this->createResourceTable();
        //$this->createResourceForm();
    }

    protected function askValidate(string $field, string|null $ask = null, array $validation = []): string
    {
        return $this->validateInput(fn () => $this->options[$field] ?? fn () => $this->ask($ask), $field, $validation, fn () => $this->options[$field] = null);
    }
}
