<?php

namespace App\Repositories\Project;

use App\Models\Project;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

class ProjectRepository extends BaseRepository implements InterfaceProjectRepository
{
    private $project;
    private $user;

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
        $sort = $params["sort"] ?? null;
        $projectName = $params["project_name"] ?? null;
        $user_id = Auth::id();
        $projects = $this->project::join('project_user', 'projects.id', '=', 'project_user.project_id')
            ->Where([['project_user.user_id', $user_id],['project_name', 'like', "%{$projectName}%"]])
            ->orwhere([['manager_id', $user_id],['project_name', 'like', "%{$projectName}%"]])
            ->select('projects.*')
            ->groupBy('projects.id');
        $odb = substr($sort, -3, 1);
        $column = substr($sort, 1);
        if ($odb === '+') {
            $projects = $projects->orderBy($column, 'asc');
        } else {
            $projects = $projects->orderBy($column, 'desc');
        }
        return $projects->get();
    }

    public function detail($projectId)
    {
        $members = $this->project->where('id', $projectId)->with(['users' => function ($query) {
            $query->orderby('role', 'asc');
        }]);
        return $members->get();
    }

    public function create($data = [])
    {
        return $this->project->create($data);
    }

    public function update($id, $data = [])
    {
        $record = $this->project->findOrFail($id);

        return $record->update($data);
    }

    public function delete($id)
    {
        return $this->project->destroy($id);
    }

    public function new()
    {
        $product = $this->project->orderBy('created_at', 'desc');

        return $product->first();
    }

    public function find($id)
    {
        return $this->project->find($id);
    }

    
}
