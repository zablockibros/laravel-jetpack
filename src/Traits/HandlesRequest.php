<?php

namespace ZablockiBros\Jetpack\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait HandlesRequest
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var \Illuminate\Database\Eloquent\Model|null
     */
    protected $model;

    /**
     * HandlesRequest constructor.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return $this
     */
    public function request(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return $this->request->validated();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(): ?Model
    {
        return $this->model ?? null;
    }

    /**
     * @param string $modelClass
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected function store()
    {
        $this->model->fill($this->data());
        $this->model->save();

        return $this->model;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected function update()
    {
        $this->model->save($this->data());

        return $this->model;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    protected function destroy()
    {
        $this->model->delete();

        return $this->model;
    }
}
