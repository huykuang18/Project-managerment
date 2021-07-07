<?php
namespace App\Repositories\Project;

use App\Repositories\InterfaceBaseRepository;

interface InterfaceProjectRepository extends InterfaceBaseRepository
{
    public function all();

    public function create($data = []);

    public function update($id, $data = []);
 
    public function delete($id);
    
    public function find($id);
}