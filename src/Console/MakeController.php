<?php

namespace ZablockiBros\Jetpack\Console;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class MakeController extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'jetpack:controller {name} {--m|model} {--r|resource} {--jobs} {--p|parent} {--api}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new controller class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $stub = null;

        if ($this->option('parent')) {
            $stub = '/stubs/controllers/controller.nested.stub';
        } elseif ($this->option('model')) {
            $stub = '/stubs/controllers/controller.model.stub';
        } elseif ($this->option('resource')) {
            $stub = '/stubs/controllers/controller.stub';
        }

        if ($this->option('api') && is_null($stub)) {
            $stub = '/stubs/controllers/controller.api.stub';
        } elseif ($this->option('api') && ! is_null($stub)) {
            $stub = str_replace('.stub', '.api.stub', $stub);
        }

        $stub = $stub ?? '/stubs/controllers/controller.plain.stub';

        return __DIR__.$stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = $rootNamespace . '\Http\Controllers';
        $namespace .= $this->option('api') ? '\Api' : '';

        return $namespace;
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
        $controllerNamespace = $this->getNamespace($name);

        $replace = [];

        if ($this->option('parent')) {
            $replace = $this->buildParentReplacements();
        }

        if ($this->option('model')) {
            $replace = $this->buildModelReplacements($replace);
        }

        $replace["use {$controllerNamespace}\Controller;\n"] = '';

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * Build the replacements for a parent controller.
     *
     * @return array
     */
    protected function buildParentReplacements()
    {
        $parentModelClass = $this->parseModel($this->option('parent'));

        if (! class_exists($parentModelClass)) {
            if ($this->confirm("A {$parentModelClass} model does not exist. Do you want to generate it?", true)) {
                $this->call('make:model', ['name' => $parentModelClass]);
            }
        }

        return [
            'ParentDummyFullModelClass' => $parentModelClass,
            'ParentDummyModelClass'     => class_basename($parentModelClass),
            'ParentDummyModelVariable'  => lcfirst(class_basename($parentModelClass)),
        ];
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
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        $model = trim(str_replace('/', '\\', $model), '\\');

        if (! Str::startsWith($model, $rootNamespace = $this->laravel->getNamespace())) {
            $model = $rootNamespace.$model;
        }

        return $model;
    }

    /**
     * @param $modelName
     *
     * @return string
     */
    protected function getRequestNamespace($modelName)
    {
        return $this->rootNamespace().'Http\Requests'. '\\' . $modelName;
    }

    /**
     * @param $modelName
     *
     * @return string
     */
    protected function getResourceClass($modelName)
    {
        return $this->rootNamespace().'Http\Resources'. '\\' . "{$modelName}Resource";
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Generate a resource controller for the given model.'],
            ['resource', 'r', InputOption::VALUE_NONE, 'Generate a resource controller class.'],
            ['jobs', 'j', InputOption::VALUE_NONE, 'Use jobs for crud operations'],
            ['parent', 'p', InputOption::VALUE_OPTIONAL, 'Generate a nested resource controller class.'],
            ['api', null, InputOption::VALUE_NONE, 'Exclude the create and edit methods from the controller.'],
        ];
    }
}
