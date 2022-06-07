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

    public function remove($id)
    {
        try {
            $this->repo->destroy($id);
            return true;
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
                } elseif ($key == 'name') {
                    $query = $query->where($key, 'like', '%' . $value . '%');
                } elseif ($key == 'field') {
                    $query = $query->where($key, 'like', '%' . $value . '%');
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

    public function search($params, $limit, $userId){
        $query = $this->repo;
        if (!empty($params['name'])) {
            $query = $query->where('name', 'like', '%' . $params['name'] . '%');
        }
        $query = $query->where('status',$params['status']);
        if (!empty($params['field'])) {
            $query = $query->where('field', 'like', '%' . $params['field'] . '%');
        }
        $query = $query->where(function ($query) use ($userId) {
            $query->whereHas('admin', function ($query) use ($userId) {
                $query->where('admin_id', $userId);
            })->orwhere('planer_id', $userId);
        });
        $query = $query->with(['planer', 'executive', 'admin']);
        $query = $query->orderBy('status', 'ASC');
        $query = $query->orderBy('id', 'DESC');
        $query = $query->paginate($limit);
        return $query;
    }

    public function getProjectByAdmin($admin_id)
    {
        $query = $this->repo;
        $query = $query->whereHas('admin', function ($query) use ($admin_id) {
            $query->where('id', $admin_id);
        });
        $data = $query->get();
        return $data;
    }

    public function getProjectByRole($role_id)
    {
        $query = $this->repo;
        $query = $query->whereHas('admin', function ($query) use ($role_id) {
            $query->whereHas('roles', function ($query) use ($role_id) {
                $query->where('id', $role_id);
            });
        });
        $data = $query->get();
        return $data;
    }

    public function getProjectReport($params){
        $query = $this->repo;
        if(isset($params['start_time']) && $params['start_time'] > 0){
            $query = $query->where('accept_time', '<=', $params['start_time'])->where('expired_time', '>=', $params['start_time']);
        }
        $query = $query->with('admin','group.phaseGroup');
        
        return $query->get();

    }
}