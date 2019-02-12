<?php

namespace ZablockiBros\Jetpack\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class MakeResourceJob extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jetpack:resource-job {name} {--model=} {--action=} {--sync} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a job for a given model for specific action type';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Job';

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $replace = [];

        if ($this->option('action')) {
            $replace = $this->buildReplacements($replace);
        }

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }

    /**
     * Build the model replacement values.
     *
     * @param  array  $replace
     * @return array
     */
    protected function buildReplacements(array $replace)
    {
        //$modelClass = $this->parseModel($this->option('model'));
        //
        //if (! class_exists($modelClass)) {
        //    if ($this->confirm("A {$modelClass} model does not exist. Do you want to generate it?", true)) {
        //        $this->call('make:model', ['name' => $modelClass]);
        //    }
        //}

        $action = $this->option('action') ?? 'store';

        return array_merge($replace, [
            'DummyActionName'  => snake_case($action),
        ]);
    }

    /**
     * Get the fully-qualified model class name.
     *
     * @param  string  $model
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function parseModel($model)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new \InvalidArgumentException('Model name contains invalid characters.');
        }

        $model = trim(str_replace('/', '\\', $model), '\\');

        if (! Str::startsWith($model, $rootNamespace = $this->laravel->getNamespace())) {
            $model = $rootNamespace.$model;
        }

        return $model;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $action = $this->option('action') ?? 'store';

        return $this->option('sync')
            ? __DIR__.'/stubs/jobs/job.stub'
            : __DIR__.'/stubs/jobs/job-queued.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Jobs\\' . Str::studly(class_basename($this->option('model')));
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['sync', null, InputOption::VALUE_NONE, 'Indicates that job should be synchronous'],
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Generate a resource controller for the given model.'],
            ['action', 'a', InputOption::VALUE_OPTIONAL, 'The desired action to generate'],
        ];
    }
}
