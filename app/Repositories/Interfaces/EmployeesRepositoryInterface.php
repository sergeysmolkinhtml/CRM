<?php


namespace App\Repositories\Interfaces;


interface EmployeesRepositoryInterface
{
    public function all();

    public function find($id, $columns = array('*'));

    public function create(array $attributes);

    public function createDTO(array $attributes);

    public function destroy($id);

    public function update($id, $user_id, array $input);

    public function updateDTO($id, $user_id, array $input);
}
