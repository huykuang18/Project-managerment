<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;

class UserRepository extends BaseRepository implements InterfaceUserRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function all()
    {
        return $this->user->get();
    }

    public function create($data = [])
    {
        return $this->user->create($data);
    }

    public function update($id, $data = [])
    {
        $record = $this->user->findOrFail($id);

        return $record->update($data);
    }

    public function find($id)
    {
        return $this->user->find($id);
    }

    public function delete($id)
    {
        return $this->user->destroy($id);
    }

    public function filter($params)
    {   
        $limit = $params['limit'];
        $sort = $params['sort'] ?? null;
        $name = $params['name'] ?? null;
        $role = $params['role'] ?? null;
        $users = $this->user::Where([['name', 'like', "%{$name}%"],['role','like',"%{$role}%"]]);
        if ($sort) {
            $odb = substr($sort, -3, 1);
            $column = substr($sort, 1);
            if ($odb == '+') {
                $users = $users->orderBy($column, 'asc');
            } else {
                $users = $users->orderBy($column, 'desc');
            }
            
        }
        return $users->paginate($limit);
    }
}
