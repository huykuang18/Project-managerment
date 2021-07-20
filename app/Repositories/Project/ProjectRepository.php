<?php

namespace App\Repositories\Project;

use App\Models\Project;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Request;

class ProjectRepository extends BaseRepository implements InterfaceProjectRepository
{
    protected $project;
    protected $user;

    public function __construct(Project $project, User $user)
    {
        $this->project = $project;
        $this->user = $user;
    }

    public function all()
    {
        return $this->project->get();
    }

    public function filter($params)
    {
        $manager_id = $params["manager_id"];
        $projects = $this->project->where('manager_id', $manager_id);
        return $projects->get();
    }

    public function detail($params)
    {
        $project_id = $params["project_id"];
        $members = $this->project->where('id', $project_id)->with('users');
        return $members->get();
    }

    public function create($data = [])
    {
        return $this->project->create($data);
    }

    public function update($params, $data = [])
    {
        $id = $params["id"];
        $record = $this->project->findOrFail($id);

        return $record->update($data);
    }

    public function delete($id)
    {
        return $this->project->destroy($id);
    }

    public function find($params)
    {
        $id = $params["id"];
        return $this->project->find($id);
    }
}
