<?php

namespace ZablockiBros\Jetpack\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use ZablockiBros\Jetpack\Support\ModelManager;

class MakeModelFields extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jetpack-make:fields {name} {--f|force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate fields for a given model';

    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @var array
     */
    protected $types = [];

    /**
     * @var array
     */
    protected $defaults = [];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->askForFields();
        $this->askForFieldTypes();
        $this->askForFieldDefaults();

        if (parent::handle() === false && ! $this->option('force')) {
            return;
        }

        return;
    }

    /**
     * @return void
     */
    protected function askForFields()
    {
        $fields = $this->ask('What fields do you want to generate? (separate with commas)', '');

        $this->fields = explode(',', str_replace(' ', '', $fields));

        return;
    }

    /**
     * @return void
     */
    protected function askForFieldTypes()
    {
        if (! $this->confirm('Set field types?', true)) {
            return;
        }

        $this->line('Types: ' . implode(', ', ModelManager::CAST_TYPES));

        foreach ($this->fields as $key => $field) {
            $this->types[$key] = $this->ask('Field type for ['.$field.']', 'string');
        }

        return;
    }

    /**
     * @return void
     */
    protected function askForFieldDefaults()
    {
        if (! $this->confirm('Set field defaults?', true)) {
            return;
        }

        foreach ($this->fields as $key => $field) {
            $this->defaults[$key] = $this->ask('Field defaults for ['.$field.']', null) ?: null;
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

        $replace = $this->buildFields($replace);

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
    protected function buildFields(array $replace)
    {
        $replace['DummyFields'] = '';

        foreach ($this->fields as $key => $field) {
            $replace['DummyFields'] .= $this->buildField($key);
        }

        return $replace;
    }

    /**
     * @return string
     */
    protected function buildField($key)
    {
        $stub = $this->files->get($this->getFieldStub());

        $replace = [
            'DummyFieldName'    => $this->fields[$key],
            'DummyFieldType'    => $this->types[$key],
            'DummyFieldDefault' => $this->defaults[$key] ? "${$this->defaults[$key]}" : 'null',
        ];

        return str_replace(
            array_keys($replace),
            array_values($replace),
            $stub
        );
    }

    /**
     * @return string
     */
    protected function modelClassName()
    {
        return Str::studly(class_basename($this->getNameInput()));
    }

    /**
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/models/fields.stub';
    }

    /**
     * @return string
     */
    protected function getFieldStub()
    {
        return __DIR__.'/stubs/models/field.stub';
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = snake_case(class_basename($name));

        return $this->laravel['path'].'/../jetpack/models/fields/'.str_replace('\\', '/', $name).'.php';
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
            ['force', 'f', InputOption::VALUE_OPTIONAL, 'Force update'],
        ];
    }
}
