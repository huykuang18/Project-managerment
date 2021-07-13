<?php
namespace App\Repositories\User;

use App\Repositories\InterfaceBaseRepository;

interface InterfaceUserRepository extends InterfaceBaseRepository
{
    public function all();

    public function create($data = []);
    
    public function find($id);

    public function update($id, $data = []);
     
    public function delete($id);
}