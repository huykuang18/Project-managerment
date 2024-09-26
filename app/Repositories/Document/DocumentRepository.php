<?php
namespace App\Repositories\Document;

use App\Models\Document;
use App\Models\Project;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

class DocumentRepository extends BaseRepository implements InterfaceDocumentRepository
{
    private $document;
    private $project;
 
    public function __construct(Document $document, Project $project)
    {
        $this->document = $document;
        $this->project = $project;
    }
 
    public function all()
    {
        return $this->document->get();
    }

    public function getDocs()
    {
        $user_id = Auth::id();
        $docs = $this->project::join('project_user', 'projects.id', '=', 'project_user.project_id')
            ->Where('project_user.user_id', $user_id)
            ->orwhere('manager_id', $user_id)
            ->select('projects.*')
            ->groupBy('projects.id')
            ->with(['documents.user']);
        
        return $docs->get();
    }

    public function create($data = [])
    {
        return $this->document->create($data);
    }

    public function update($id, $data = [])
    {
        $record = $this->document->findOrFail($id);
 
        return $record->update($data);
    }
 
    public function delete($id)
    {
        return $this->document->destroy($id);
    }
 
    public function find($id)
    {
        return $this->document->find($id);
    }
}