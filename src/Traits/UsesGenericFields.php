<?php

namespace ZablockiBros\Jetpack\Traits;

use ZablockiBros\Jetpack\Models\Field;
use Illuminate\Support\Collection;

trait UsesGenericFields
{
    /**
     * These are set dynamically
     *
     * @var array
     */
    protected $newFields = [];

    /**
     * Default values
     *
     * @var array
     */
    protected $fieldAttributes = [];

    /**
     * @return void
     */
    public static function bootUsesGenericFields()
    {
        static::saved(function ($model) {
            // save the field columns
            $model->allFields()
                ->each(function (Field $field) use ($model) {
                    $field->fieldable_id = $model->id; // needed for saving ID
                    $field->save();
                });
        });

        return;
    }

    /**
     * @return mixed
     */
    public function fields()
    {
        return $this->morphMany(Field::class, 'fieldable');
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        $value = $this->getAttribute($key);

        if (
            isset($value)
            || ! in_array($key, $this->fieldColumns())
        ) {
            return $value;
        }

        return $this->getFieldValue($key);
    }

    /**
     * @param $key
     * @param $value
     */
    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->fieldColumns())) {
            $defaultField         = $this->getOrCreateField();
            $defaultField->{$key} = $value;

            return;
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * @override
     *
     * @return array
     */
    public function existingColumns(): array
    {
        return array_merge(
            [$this->getKeyName()],
            $this->guarded ?? [],
            $this->fillable ?? [],
            $this->timestamps ? ['created_at', 'updated_at'] : [],
            method_exists($this, 'getDeletedAtColumn') ? [$this->getDeletedAtColumn()] : []
        );
    }

    /**
     * @override
     *
     * @return array
     */
    public function fieldColumns(): array
    {
        return array_keys(
            $this->fieldAttributes ?? []
        );
    }

    /**
     * @return array
     */
    public function fieldset(): array
    {
        if (isset($this->fieldset) && is_array($this->fieldset)) {
            return $this->fieldset;
        }

        return [];
    }

    /**
     * @return array
     */
    public function fieldsetKeys(): array
    {
        return array_keys($this->fieldset());
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        return collect($this->attributes ?? [])
            ->only(
                array_diff(
                    array_keys($this->attributes),
                    array_keys($this->fieldAttributes ?? [])
                )
            )
            ->toArray();
    }

    /**
     * @param array $attributes
     */
    public function setFieldAttributes(array $attributes)
    {
        $this->fieldAttributes = $attributes;
    }

    /**
     * @param string $key
     */
    private function getFieldValue(string $key)
    {
        if (! $this->relationLoaded('fields')) {
            $this->load('fields');
        }

        $fieldModel = $this->fieldModelByName();

        // check the default field for value
        if (! $fieldModel) {
            $fieldModel = $this->getOrCreateField();
        }

        if (! $fieldModel) {
            return null;
        }

        $value = $fieldModel->{$key};

        return $value
            ?? $this->defaultFieldValue($key)
            ?? null;
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    private function defaultFieldValue(string $key)
    {
        $defaults = $this->fieldAttributes ?? [];

        return $defaults[$key] ?? null;
    }

    /**
     * @param string $name
     *
     * @return \App\Models\Field
     */
    private function getOrCreateField(string $name = 'default'): Field
    {
        if ($field = $this->fieldModelByName($name)) {
            return $field;
        }

        $field = $this->fields()->firstOrNew([
            'fieldable_type' => get_class($this),
            'fieldable_id'   => $this->id,
            'name'           => $name,
        ]);

        $this->newFields = collect($this->newFields)
            ->push($field)
            ->unique('name')
            ->all();

        return $field;
    }

    /**
     * @return Collection
     */
    private function allFields(): Collection
    {
        return collect($this->newFields)
            ->merge($this->fields)
            ->unique('name');
    }

    /**
     * @param string $name
     *
     * @return \App\Models\Field|null
     */
    private function fieldModelByName(string $name = 'default'): ?Field
    {
        return $this->allFields()
            ->first(function (Field $field) use ($name) {
                return $field->name === $name;
            });
    }
}
