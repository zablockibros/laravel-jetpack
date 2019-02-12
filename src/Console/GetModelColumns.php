<?php

namespace ZablockiBros\Jetpack\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use ZablockiBros\Jetpack\Support\ModelManager;

class GetModelColumns extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jetpack-model:columns {name} {--f|force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get columns for model from table';

    /**
     * @var array
     */
    protected $columns = [];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $model = str_replace('/', '\\', $this->argument('name'));
        $table = snake_case(str_plural(class_basename($model)));

        $this->columns = ModelManager::getTableColumns($table);

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

        $replace = $this->buildTable($replace);

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
    protected function buildTable(array $replace)
    {
        $replace['DummyColumns'] = '';

        foreach ($this->columns as $key => $column) {
            $replace['DummyColumns'] .= $this->buildColumns($column);
        }

        return $replace;
    }

    /**
     * @return string
     */
    protected function buildColumns($column)
    {
        $stub = $this->files->get($this->getColumnStub());

        $replace = [
            'DummyColumn' => $column ?? 'missing',
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
        return __DIR__.'/stubs/models/table.stub';
    }

    /**
     * @return string
     */
    protected function getColumnStub()
    {
        return __DIR__.'/stubs/models/column.stub';
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

        return $this->laravel['path'].'/../config/models/table/'.$name.'.php';
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
