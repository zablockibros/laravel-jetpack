<?php

namespace ZablockiBros\Jetpack\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class MakeModel extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jetpack:model {name} {--a|all} {--c|controller} {--o|ownable} {--api} {--f|factory} {--m|migration} {--r|resource} {--p|pivot} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an Eloquent model and related factories, migrations, and resources';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (parent::handle() === false && ! $this->option('force')) {
            return;
        }

        if ($this->option('all')) {
            $this->input->setOption('factory', true);
            $this->input->setOption('migration', true);
            $this->input->setOption('controller', true);
            $this->input->setOption('resource', true);
        }

        // todo: make work for multiple models
        $models = [$this->argument('name')];

        collect($models)->each(function ($model) {
            $this->buildModel($model);

            if ($this->option('factory')) {
                $this->createFactory($model);
            }

            if ($this->option('migration')) {
                // todo: implement in separate command
                //$this->createMigration($model);
            }

            if ($this->option('resource')) {
                $this->createResource($model);
            }

            if ($this->option('controller') || $this->option('resource')) {
                $this->createRequests($model);
            }

            $this->createPolicy($model);

            if ($this->option('controller')) {
                $this->createController($model);
            }

            if ($this->option('controller') && ($this->option('all') || $this->option('api'))) {
                $this->createApiController($model);
            }
        });

        return;
    }

    /**
     * @param $model
     */
    protected function buildModel($model)
    {
        Artisan::call('make:model', [
            'name' => $model,
        ]);
    }

    /**
     * @return void
     */
    protected function createFactory($model)
    {
        $factory = Str::studly($model);

        $this->call('make:factory', [
            'name'    => "{$factory}Factory",
            '--model' => $model,
        ]);
    }

    /**
     * @return void
     */
    protected function createMigration($model)
    {
        $table = Str::plural(Str::snake(class_basename($model)));

        if ($this->option('pivot')) {
            $table = Str::singular($table);
        }

        $this->call('make:migration', [
            'name' => "create_{$table}_table",
            '--create' => $table,
        ]);
    }

    /**
     * @return void
     */
    protected function createResource($model)
    {
        $resource = Str::studly(class_basename($model));

        $this->call('make:resource', [
            'name' => "{$resource}Resource",
        ]);
    }

    /**
     * @return void
     */
    protected function createRequests($model)
    {
        $resource = Str::studly(class_basename($model));

        $this->call('make:request', [
            'name' => "{$resource}/IndexRequest",
        ]);
        $this->call('make:request', [
            'name' => "{$resource}/StoreRequest",
        ]);
        $this->call('make:request', [
            'name' => "{$resource}/UpdateRequest",
        ]);
    }

    /**
     * @return void
     */
    protected function createController($model)
    {
        $controller = Str::studly(class_basename($model));
        $name       = "{$controller}Controller";

        $this->call('jetpack:controller', [
            'name'    => $name,
            '--model' => $model,
            '--api'   => false,
        ]);
    }

    /**
     * @return void
     */
    protected function createApiController($model)
    {
        $controller = Str::studly(class_basename($model));
        $name       = "{$controller}Controller";

        $this->call('jetpack:controller', [
            'name'    => $name,
            '--model' => $model,
            '--api'   => true,
        ]);
    }

    /**
     * @return void
     */
    protected function createPolicy($model)
    {
        $class = Str::studly(class_basename($model));
        $name  = "{$class}Policy";

        $this->call('make:policy', [
            'name'    => $name,
            '--model' => $model,
        ]);
    }

    /**
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('ownable')) {
            return __DIR__.'/stubs/models/model.ownable.stub';
        }

        if ($this->option('pivot')) {
            return __DIR__.'/stubs/models/pivot.model.stub';
        }

        return __DIR__.'/stubs/models/model.stub';
    }

    /**
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace;
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['all', 'a', InputOption::VALUE_NONE, 'Generate a migration, factory, and resource controller for the model'],
            ['controller', 'c', InputOption::VALUE_NONE, 'Create a new controller for the model'],
            ['ownable', 'o', InputOption::VALUE_NONE, 'Create ownership relationship to user model'],
            ['factory', 'f', InputOption::VALUE_NONE, 'Create a new factory for the model'],
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the model already exists'],
            ['migration', 'm', InputOption::VALUE_NONE, 'Create a new migration file for the model'],
            ['pivot', 'p', InputOption::VALUE_NONE, 'Indicates if the generated model should be a custom intermediate table model'],
            ['resource', 'r', InputOption::VALUE_NONE, 'Indicates if the generated controller should be a resource controller'],
        ];
    }
}
