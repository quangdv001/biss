<?php
namespace App\Repo;

use App\Models\Project;
use Exception;

class ProjectRepo
{
    private $repo;
    public function __construct(Project $repo)
    {
        $this->repo = $repo;
    }

    public function create($data)
    {
        try {
            $repo = new Project();
            foreach ($data as $key => $value) {
                $repo->$key = $value;
            }
            $repo->save();
            return $repo;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update($repo, $data)
    {
        try {
            foreach ($data as $key => $value) {
                $repo->$key = $value;
            }
            $repo->save();
            return $repo;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function first($condition = [], $order = [], $with = [])
    {
        $query = $this->repo;
        if(!empty($condition)){
            foreach ($condition as $key => $value) {
                if (is_array($value)) {
                    $query = $query->whereIn($key, $value);
                } else {
                    $query = $query->where($key, $value);
                }
            }
        }

        if (!empty($with)) {
            $query = $query->with($with);
        }

        if (!empty($order)) {
            foreach ($order as $key => $value) {
                $query = $query->orderBy($key, $value);
            }
        }
        $data = $query->first();
        return $data;
    }

    public function get($condition = [], $order = [], $with = [])
    {
        $query = $this->repo;
        if(!empty($condition)){
            foreach ($condition as $key => $value) {
                if (is_array($value)) {
                    $query = $query->whereIn($key, $value);
                } else {
                    $query = $query->where($key, $value);
                }
            }
        }

        if (!empty($with)) {
            $query = $query->with($with);
        }

        if (!empty($order)) {
            foreach ($order as $key => $value) {
                $query = $query->orderBy($key, $value);
            }
        }

        $data = $query->get();
        return $data;
    }

    public function paginate($condition = [], $limit = 10, $order = [], $with = [])
    {
        $query = $this->repo;
        if(!empty($condition)){
            foreach ($condition as $key => $value) {
                if (is_array($value)) {
                    $query = $query->whereIn($key, $value);
                } else {
                    $query = $query->where($key, $value);
                }
            }
        }

        if (!empty($with)) {
            $query = $query->with($with);
        }

        if (!empty($order)) {
            foreach ($order as $key => $value) {
                $query = $query->orderBy($key, $value);
            }
        }

        $data = $query->paginate($limit);
        return $data;
    }

    public function pluck($condition = [], $field, $key = '')
    {
        $query = $this->repo;
        if(!empty($condition)){
            foreach ($condition as $key => $value) {
                if (is_array($value)) {
                    $query = $query->whereIn($key, $value);
                } else {
                    $query = $query->where($key, $value);
                }
            }
        }
        if ($key != '') {
            $data = $query->pluck($field, $key);
        } else {
            $data = $query->pluck($field);
        }
        return $data;
    }

}