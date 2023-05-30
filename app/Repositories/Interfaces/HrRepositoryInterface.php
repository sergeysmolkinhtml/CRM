<?php

namespace App\Repositories\Interfaces;


interface HrRepositoryInterface
{
    public function all();

    public function find($id, $columns = array('*'));

    public function create(array $attributes);

    public function createDTO(array $attributes);

    public function destroy($id);

    public function update($id, array $input);
    public function updateDTO($id, array $input);
}
