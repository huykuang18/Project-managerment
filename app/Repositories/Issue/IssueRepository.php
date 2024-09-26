<?php

namespace App\Repositories\Issue;

use App\Models\Issue;
use App\Models\Item;
use App\Models\User;
use App\Repositories\BaseRepository;

class IssueRepository extends BaseRepository implements InterfaceIssueRepository
{
    private $issue;
    private $user;

    public function __construct(Issue $issue, User $user)
    {
        $this->issue = $issue;
        $this->user = $user;
    }

    public function all()
    {
        return $this->issue->get();
    }

    public function index($params)
    {
        $projectId = $params["project_id"];
        $items = Item::where([['project_id', $projectId], ['parent_id', '!=', null]])->with(['issue.users'])->orderBy('parent_id','asc')->orderBy('order','asc');
    
        return $items->get();
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

    public function members($params)
    {
        $projectId = $params["project_id"];
        $members = $this->user::join('project_user','users.id','=','project_user.user_id')->where('project_user.project_id',$projectId);

        return $members->orderBy('role','asc')->get();
    }
}
