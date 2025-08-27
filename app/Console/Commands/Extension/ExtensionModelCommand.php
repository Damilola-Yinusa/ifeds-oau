<?php

namespace App\Console\Commands\Extension;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExtensionModelCommand extends Command
{
    protected $signature = 'ext:model {name} {--f|for= : Extension Name} {--m : Also create a migration}';

    protected $description = 'Create a model for a specific extension';

    public function handle()
    {
        $extension = Str::studly($this->option('for'));

        if (! $extension) {
            $this->error('You must specify an extension name using --for or -f.');

            return;
        }

        $modelName = Str::studly($this->argument('name'));

        $basePath = base_path("app/Extensions/{$extension}/System/Models");

        // Klasör yoksa oluştur
        if (! is_dir($basePath)) {
            Storage::disk('extension')->makeDirectory("{$extension}/System/Models");
        }

        $modelPath = "{$basePath}/{$modelName}.php";

        if (file_exists($modelPath)) {
            $this->error("Model already exists at: {$modelPath}");

            return;
        }

        // Basit bir model template’i
        $namespace = "App\\Extensions\\{$extension}\\System\\Models";

        $content = file_put_contents($modelPath, $content);

        $this->info("Model created at: {$modelPath}");

        // Check if -m flag is present, then create migration
        if ($this->option('m')) {
            $migrationName = 'create_' . Str::snake(Str::pluralStudly($modelName)) . '_table';

            Artisan::call('make:migration', [
                'name'     => $migrationName,
                '--create' => 'ext_' . Str::snake(Str::pluralStudly($modelName)),
                '--path'   => "app/Extensions/{$extension}/database/migrations",
            ]);

            $this->info("Migration created for model: {$modelName}");
        }
    }
}
