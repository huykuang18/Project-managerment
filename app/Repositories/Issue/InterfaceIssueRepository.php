<?php
namespace App\Repositories\Issue;

use App\Repositories\InterfaceBaseRepository;

interface InterfaceIssueRepository extends InterfaceBaseRepository
{
    public function all();

    public function create($data = []);

    public function update($id, $data = []);
 
    public function delete($id);
    
    public function find($id);
}