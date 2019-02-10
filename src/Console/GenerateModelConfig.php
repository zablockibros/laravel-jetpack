<?php

namespace ZablockiBros\Jetpack\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class GenerateModelConfig extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jetpack-generate:model {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates model configs for a given model';

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

        return;
    }

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

        $replace = $this->buildModelReplacements($replace);

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
    protected function buildModelReplacements(array $replace)
    {
        $modelClass = $this->parseModel($this->option('model'));

        if (! class_exists($modelClass)) {
            if ($this->confirm("A {$modelClass} model does not exist. Do you want to generate it?", true)) {
                $this->call('make:model', ['name' => $modelClass]);
            }
        }

        return array_merge($replace, [
            'DummyRequestPath'     => $this->getRequestNamespace(class_basename($modelClass)),
            'DummyResource'        => class_basename($modelClass) . 'Resource',
            'DummyFullResource'    => $this->getResourceClass(class_basename($modelClass)),
            'DummyFullModelClass'  => $modelClass,
            'DummyModelClass'      => class_basename($modelClass),
            'DummyModelVariable'   => lcfirst(class_basename($modelClass)),
            'DummyModelSnakeName'  => snake_case(class_basename($modelClass)),
            'DummyModelsSnakeName' => snake_case(str_plural(class_basename($modelClass))),
        ]);
    }

    /**
     * @return string
     */
    protected function getStub()
    {
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
            //['all', 'a', InputOption::VALUE_NONE, 'Generate a migration, factory, and resource controller for the model'],
        ];
    }
}
