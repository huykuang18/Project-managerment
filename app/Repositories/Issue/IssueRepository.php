<?php
namespace App\Repositories\Issue;

use App\Models\Issue;
use App\Repositories\BaseRepository;

class IssueRepository extends BaseRepository implements InterfaceIssueRepository
{
    protected $issue;
 
    public function __construct(Issue $issue)
    {
        $this->issue = $issue;
    }
 
    public function all()
    {
        return $this->issue->get();
    }

    public function create($data = [])
    {
        return $this->issue->create($data);
    }

    public function update($id, $data = [])
    {
        $record = $this->issue->findOrFail($id);
 
        return $record->update($data);
    }
 
    public function delete($id)
    {
        return $this->issue->destroy($id);
    }
 
    public function find($id)
    {
        return $this->issue->find($id);
    }
}