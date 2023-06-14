<?php

namespace App\Console\Commands\Concerns;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ReflectionClass;

trait WithStubTrait
{
    protected int $sequence = 0;

    /**
     * @param string $source
     * @param string $destination
     * @param array $replacements
     * @return int
     */
    public function copyStub(string $source, string $destination, array $replacements = []): int
    {
        $stub = $this->getStubContent($source, $replacements);
        return $this->writeFile($destination, $stub);
    }

    /**
     * @param string $name
     * @return int
     * @throws \Exception
     */
    public function copyResourceStubToApp(string $name): int
    {
        $result = 0;
        $result += $this->copyFolderStubToApp("WtaInstaller/app/Filament/Resources/{$name}Resource");
        $result += $this->copyStubToApp("WtaInstaller/app/Filament/Resources/{$name}Resource.stub");

        if (File::exists(base_path() . "/stubs/WtaInstaller/app/Models/{$name}.stub")) {
            $result += $this->copyStubToApp("WtaInstaller/app/Models/{$name}.stub");
        }

        return $result;
    }

    /**
     * @param string $relativeFolderFile
     * @param array $replacements
     * @param string|null $destination
     *
     * @return int
     * @throws \Exception
     */
    public function copyStubToApp(
        string $relativeFolderFile,
        array $replacements = [],
        string|null $destination = null
    ): int {
        $source = base_path() . '/stubs/' . $relativeFolderFile;
        $destination = $destination ?? $this->rootPath . Str::of($relativeFolderFile)
            ->replace('.stub', '')
            ->append('.php');
        $file = new \SplFileInfo($source);
        if (File::exists($destination) && $this->option('force')) {
            if($this->option('debug')) {
                $this->newLine();
                $this->info("\nFile {$destination} already exists and will be deleted [Force mode] = enabled");
            }
            File::delete($destination);
        } else if(File::exists($destination)){
            if($this->option('debug')) {
                $this->newLine();
                $this->info("\nFile {$destination} already exists and will be skipped [Force mode] = disabled");
            }
            return 0;
        }
        return $this->copyStub(
            source: $file->getPathname(),
            destination: $destination,
            replacements: ['namespace' => app()->getNamespace(), ...$replacements],
        );
    }

    /**
     * @param  string[]  $excludes
     * @param  string[]  $includes
     *
     * @throws \Exception
     */
    public function copyFolderStubToApp(
        string $relativeFolderPath,
        array $replacements = [],
        array $excludes = [],
        array $includes = ['*']
    ): int {
        $result = 0;
        $listFiles = collect(File::allFiles($this->localPath.'/stubs/'.$relativeFolderPath, true));
        $listFiles
            ->each(function (\SplFileInfo $file) use ($includes, $excludes, $relativeFolderPath, $replacements, &$result) {
                $included = $includes === ['*'] || collect($includes)
                    ->filter(static function ($item) use ($file) {
                        return Str::of($file->getRelativePathname())->lower()->contains(strtolower($item));
                    })
                    ->count() > 0;
                $excluded = collect($excludes)
                    ->filter(static function ($item) use ($file) {
                        return Str::of($file->getRelativePathname())->lower()->contains(strtolower($item));
                    })
                    ->count() > 0;
                if ($file->getExtension() === 'stub'
                    && $included
                    && !$excluded
                ) {
                    $result += $this->copyStub(
                        source: $file->getPathname(),
                        destination: Str::of($this->rootPath . $relativeFolderPath . '/'. $file->getRelativePathname())
                            ->replace('.stub', '.php'),
                        replacements: ['namespace' => app()->getNamespace(), ...$replacements],
                    );
                }
            });

        return $result;
    }

    /**
     * @param string $stubName
     * @param array $replacements
     * @return int
     * @throws \Exception
     */
    public function copyConfigStubToApp(string $stubName, array $replacements = []): int
    {
        $stubName = Str::of($stubName)
            ->replace('.stub', '')
            ->append('.stub');
        $destination = $this->rootPath . Str::of('config/' . $stubName)->replace('.stub', '.php');
        return $this->copyStubToApp(
            relativeFolderFile: 'config/' . $stubName,
            replacements: $replacements,
            destination: $destination,
        );
    }

    /**
     * @param string $path
     * @param string $contents
     * @return int
     */
    private function writeFile(string $path, string $contents): int
    {
        $pathFolder = Str::of($path)->beforeLast('/');
        File::ensureDirectoryExists($pathFolder);
        if(File::exists($path) && $this->option('force')){
            File::delete($path);
        }
        File::put($path, $contents);
        return 0;
    }

    /**
     * @param string $stubPathAndName
     * @param array $replacements
     * @return string
     */
    private function getStubContent(string $stubPathAndName, array $replacements = []): string
    {
        $content = File::get($stubPathAndName);
        $stub = Str::of($content);
        foreach ($replacements as $key => $replacement) {
            $stub = $stub->replace("{{ {$key} }}", $replacement);
        }

        return (string) $stub;
    }
}