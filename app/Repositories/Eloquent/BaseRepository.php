<?php


namespace App\Repositories\Eloquent;


abstract class BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = $this->resolveModel();
    }

    /**
     * Makes the bind
     * @return \Illuminate\Contracts\Foundation\Application|mixed
     */
    protected function resolveModel()
    {
        return app($this->model);
    }

    /**
     * Returns a list of the resource
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Stores the data
     * @param array $data
     * @return mixed
     */
    public function store(array $data = [])
    {
        return $this->model->create($data);
    }

    /**
     * Search for a given Id
     * @param int $id
     * @return mixed
     */
    public function show(int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Updates the model with the given data
     * @param array $data
     * @return mixed
     */
    public function update(int $id,array $data = [])
    {
        return $this->show($id)->update($data);
    }

    /**
     * Delese given model by id
     * @param int $id
     * @return mixed
     */
    public function delete(int $id)
    {
        return $this->show($id)->delete();
    }
}
