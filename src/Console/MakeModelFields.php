<?php

namespace ZablockiBros\Jetpack\Console;

use Illuminate\Console\GeneratorCommand;
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
    protected $signature = 'jetpack:fields {name} {--f|force}';

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
        while ($field = $this->ask('Enter a field name (or empty to end)', false)) {
            $this->fields[] = $field;
            $this->askForFieldTypes(count($this->fields) - 1);
            $this->askForFieldDefaults(count($this->fields) - 1);
        }

        return;
    }

    /**
     * @return void
     */
    protected function askForFieldTypes($key)
    {
        //$this->line('Now time for field types');

        $this->types[$key] = $this->choice('Field type ['.$this->fields[$key].']', ModelManager::CAST_TYPES, 0);

        return;
    }

    /**
     * @return void
     */
    protected function askForFieldDefaults($key)
    {
        //$this->line('Now time for field default values');

        //foreach ($this->fields as $key => $field) {
            $this->defaults[$key] = $this->ask('Default ['.$this->fields[$key].'] (empty for null)', null) ?: null;
        //}

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

        $value = $this->defaults[$key] ?? 'null';
        $value = $value ?: 'null';

        $replace = [
            'DummyFieldName'    => $this->fields[$key] ?? 'missing',
            'DummyFieldType'    => $this->types[$key] ?? 'string',
            'DummyFieldDefault' => $value,
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

        return $this->laravel['path'].'/../config/models/fields/'.$name.'.php';
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
