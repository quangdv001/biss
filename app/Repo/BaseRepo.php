<?php
namespace App\Repo;

use Exception;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\DB;

abstract class BaseRepo
{
    protected $app, $model;
    public function __construct(Container $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    public function getModel()
    {
        return $this->model;
    }

    public function resetModel()
    {
        $this->makeModel();
    }

    abstract public function model();

    public function makeModel()
    {
        $model = $this->app->make($this->model());
        return $this->model = $model;
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function all()
    {
        return $this->model->all();
    }

    public function first($condition, $order = [], $with = [])
    {
        $query = $this->model;
        if (!empty($with)) {
            $query = $query->with($with);
        }
        if (!empty($condition)) {
            foreach ($condition as $k => $v) {
                if (is_string($k)) {
                    if (is_array($v)){
                        $query = $query->whereIn($k, $v);
                    }else{
                        $query = $query->where($k, $v);
                    }
                } else {
                    if (!empty($v)) {
                        $query = $query->where($v[0], $v[1], $v[2]);
                    }
                }
            }
        }
        if (!empty($order)) {
            foreach ($order as $k => $v) {
                $query = $query->orderBy($k, $v);
            }
        }

        $this->resetModel();
        return $query->first();
    }

    public function get($condition, $order = [], $with = [])
    {
        $query = $this->model;
        if (!empty($with)) {
            $query = $query->with($with);
        }
        if (!empty($condition)) {
            foreach ($condition as $k => $v) {
                if (is_string($k)) {
                    if (is_array($v)){
                        $query = $query->whereIn($k, $v);
                    }else{
                        $query = $query->where($k, $v);
                    }
                } else {
                    if (!empty($v)) {
                        $query = $query->where($v[0], $v[1], $v[2]);
                    }
                }
            }
        }
        if (!empty($order)) {
            foreach ($order as $k => $v) {
                $query = $query->orderBy($k, $v);
            }
        }

        $this->resetModel();
        return $query->get();
    }

    public function paginate($condition, $limit, $order = [], $with = [])
    {
        $query = $this->model;
        if (!empty($with)) {
            $query = $query->with($with);
        }
        if (!empty($condition)) {
            foreach ($condition as $k => $v) {
                if (is_string($k)) {
                    if (is_array($v)){
                        $query = $query->whereIn($k, $v);
                    }else{
                        $query = $query->where($k, $v);
                    }
                } else {
                    if (!empty($v)) {
                        $query = $query->where($v[0], $v[1], $v[2]);
                    }
                }
            }
        }
        if (!empty($order)) {
            foreach ($order as $k => $v) {
                $query = $query->orderBy($k, $v);
            }
        }

        $this->resetModel();
        return $query->paginate($limit);
    }

    public function searchPaginate($data){
        $query = $this->model;
        if (isset($data['name']) && $data['name'] != '') {
            $query = $query->where('name', 'like', '%' . $data['name'] . '%');
        }
        if (isset($data['key']) && $data['key'] != '') {
            $query = $query->where('name', 'like', '%' . $data['name'] . '%');
        }
        if (isset($data['sortBy']) && $data['sortBy'] != '') {
            $query = $query->orderBy($data['sortBy'], isset($data['sortOrder']) ? $data['sortOrder'] : 'DESC');
        } else {
            $query = $query->orderBy('id', 'DESC');
        }
        $admin = $query->paginate(isset($data['limit']) ? (int)$data['limit'] : 30);
        $this->resetModel();
        return $admin;
    }

    public function pluck($condition, $column, $key = null, $order = [], $with = [])
    {
        $query = $this->model;
        if (!empty($with)) {
            $query = $query->with($with);
        }
        if (!empty($condition)) {
            foreach ($condition as $k => $v) {
                if (is_string($k)) {
                    if (is_array($v)){
                        $query = $query->whereIn($k, $v);
                    }else{
                        $query = $query->where($k, $v);
                    }
                } else {
                    if (!empty($v)) {
                        $query = $query->where($v[0], $v[1], $v[2]);
                    }
                }
            }
        }
        if (!empty($order)) {
            foreach ($order as $k => $v) {
                $query = $query->orderBy($k, $v);
            }
        }

        $this->resetModel();
        return $query->pluck($column, $key);
    }

    public function count($condition, $order = [], $with = [])
    {
        $query = $this->model;
        if (!empty($with)) {
            $query = $query->with($with);
        }
        if (!empty($condition)) {
            foreach ($condition as $k => $v) {
                if (is_string($k)) {
                    if (is_array($v)){
                        $query = $query->whereIn($k, $v);
                    }else{
                        $query = $query->where($k, $v);
                    }
                } else {
                    if (!empty($v)) {
                        $query = $query->where($v[0], $v[1], $v[2]);
                    }
                }
            }
        }
        if (!empty($order)) {
            foreach ($order as $k => $v) {
                $query = $query->orderBy($k, $v);
            }
        }

        $this->resetModel();
        return $query->count();
    }

    public function create($params)
    {
        $query = new $this->model;
        if (!empty($params)) {
            foreach ($params as $k => $v) {
                $query->$k = $v;
            }
        }
        $query->save();

        $this->resetModel();
        return $query;
    }

    public function update($obj, $params, $isMulti = false) {
        if (!$isMulti) {
            $query = is_array($obj) ? $this->first($obj) : $obj;
            if (!empty($params)) {
                foreach ($params as $k => $v) {
                    $query->$k = $v;
                }
            }
            $query->save();

            $this->resetModel();
            return $query;
        } else {
            $query = $this->model;
            if (is_array($obj) && !empty($obj)) {
                foreach ($obj as $k => $v) {
                    if (is_string($k)) {
                        if (is_array($v)){
                            $query = $query->whereIn($k, $v);
                        }else{
                            $query = $query->where($k, $v);
                        }
                    } else {
                        if (!empty($v)) {
                            $query = $query->where($v[0], $v[1], $v[2]);
                        }
                    }
                }
            }
            $query->update($params);

            $this->resetModel();
            return true;
        }
    }

    public function insert($params)
    {
        try {
            DB::beginTransaction();
            $this->model->insert($params);

            $this->resetModel();
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function upsert($conditions, $params)
    {
        try {
            DB::beginTransaction();

            if (!empty($conditions)) {
                foreach ($conditions as $condition) {
                    $this->model->updateOrCreate($condition, $params);

                    $this->resetModel();
                }
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy($id)
    {
        try {
            $this->model->destroy($id);
            $this->resetModel();
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }

}
