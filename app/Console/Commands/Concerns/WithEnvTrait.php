<?php

namespace App\Console\Commands\Concerns;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

trait WithEnvTrait
{
    protected string|null $appName = 'WTA';

    protected string|null $dbHost = '127.0.0.1';

    protected string|null $dbPort = '3306';

    protected string|null $dbName = 'wtadmin';

    protected string|null $dbUser = 'admin';

    protected string|null $dbPassword = 'password01!';

    protected array $currentEnvData = [
        'APP_NAME' => 'WTA',
        'APP_URL' => 'http://localhost',
        'DB_HOST' => '127.0.0.1',
        'DB_PORT' => '3306',
        'DB_DATABASE' => 'wtadmin',
        'DB_USERNAME' => 'admin',
        'DB_PASSWORD' => 'password01!',
        'LOG_CHANNEL' => 'daily',
    ];

    //protected array $newEnvData =[];

    protected function setAppEnv(): int
    {
        $result = self::SUCCESS;
        if ($this->option('force') || ! File::exists(base_path().'/.env')) {
            $result = self::FAILURE;
            if (File::exists(base_path().'/.env')) {
                $resultOverWrite = $this->confirm('Do you want to overwrite your .env file?') ? self::SUCCESS : self::FAILURE;
                if ($resultOverWrite === self::SUCCESS) {
                    $this->setEnv();
                    $this->callSilent('key:generate');
                    $this->callSilent('optimize:clear');
                }
            } else {
                $this->setEnv();
            }
        }

        return $result;
    }

    protected function setEnv(): int
    {
        $result = self::FAILURE;
        while ($result === self::FAILURE) {
            $currentEnvData = $this->envToArray(base_path().'/.env');
            $this->appName = $this->ask('What is the name of your application?', $currentEnvData['APP_NAME'] ?? $this->appName);
            $this->dbHost = $this->ask('What is the db host of your application?', $currentEnvData['DB_HOST'] ?? $this->dbHost);
            $this->dbName = $this->ask('What is the db name of your application?', $currentEnvData['DB_DATABASE'] ?? $this->dbName);
            $this->dbPort = $this->ask('What is the db port of your application?', $currentEnvData['DB_PORT'] ?? $this->dbPort);
            $this->dbUser = $this->ask('What is the db user of your application?', $currentEnvData['DB_USER'] ?? $this->dbUser);
            $this->dbPassword = $this->ask('What is the db password of your application?', $currentEnvData['DB_PASSWORD'] ?? $this->dbPassword);
            $result = $this->validateAppEnvData();
        }

        $envData = $this->envToArray(base_path().'/.env.example');
        $envData['APP_NAME'] = $this->appName;
        $envData['DB_HOST'] = $this->dbHost;
        $envData['DB_PORT'] = $this->dbPort;
        $envData['DB_DATABASE'] = $this->dbName;
        $envData['DB_USERNAME'] = $this->dbUser;
        $envData['DB_PASSWORD'] = $this->dbPassword;

        $result += $this->saveEnvFromArray($envData);

        return $result;
    }

    /**
     * @return array<string, string|null>
     */
    protected function envToArray(string $file): ?array
    {
        if (! File::exists($file)) {
            return null;
        }
        $string = file_get_contents($file);
        $string = preg_split('/\n+/', $string);
        $returnArray = [];

        foreach ($string as $one) {
            if (preg_match('/^(#\s)/', $one) === 1 || preg_match('/^([\\n\\r]+)/', $one)) {
                continue;
            }
            $entry = explode('=', $one, 2);
            $returnArray[$entry[0]] = $entry[1] ?? null;
        }

        return array_filter(
            $returnArray,
            static function ($key) {
                return ! empty($key);
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * @param  array<string, string|null>  $array
     */
    protected function saveEnvFromArray(array $array): int
    {
        $newArray = [];
        $keyWithNewLine = ['APP_URL', 'LOG_LEVEL', 'DB_PASSWORD', 'SESSION_LIFETIME', 'MEMCACHED_HOST', 'REDIS_PORT', 'MAIL_FROM_NAME', 'AWS_USE_PATH_STYLE_ENDPOINT', 'PUSHER_APP_CLUSTER', 'VITE_PUSHER_APP_CLUSTER'];
        foreach ($array as $key => $value) {
            if (preg_match('/\s/', $value) > 0 && strpos($value, '"') !== 0 && strrpos($value, '"') != (strlen($value) - 1)) {
                $value = '"'.$value.'"';
            }

            $newArray[] = $key.'='.$value;
            if (in_array($key, $keyWithNewLine)) {
                $newArray[] = '';
            }
        }
        $newArray = implode("\n", $newArray);
        file_put_contents(base_path().'/.env', $newArray);

        return self::SUCCESS;
    }

    protected function validateAppEnvData(): int
    {
        $validator = Validator::make([
            'name' => $this->appName,
            'dbHost' => $this->dbHost,
            'dbName' => $this->dbName,
            'dbPort' => $this->dbPort,
            'dbUser' => $this->dbUser,
            'dbPassword' => $this->dbPassword,
        ], [
            'name' => 'required|string|max:255',
            'dbHost' => 'required|string|max:255',
            'dbName' => 'required|string|max:255',
            'dbPort' => 'required|digits_between:1,8',
            'dbUser' => 'required|string|max:255',
            'dbPassword' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            $this->line('App env not create. See error messages below:');
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
