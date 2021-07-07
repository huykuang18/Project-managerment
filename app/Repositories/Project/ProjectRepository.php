<?php
namespace App\Repositories\Project;

use App\Models\Project;
use App\Repositories\BaseRepository;

class ProjectRepository extends BaseRepository implements InterfaceProjectRepository
{
    protected $project;
 
    public function __construct(Project $project)
    {
        $this->project = $project;
    }
 
    public function all()
    {
        return $this->project->get();
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
 
    public function find($id)
    {
        return $this->project->find($id);
    }
}