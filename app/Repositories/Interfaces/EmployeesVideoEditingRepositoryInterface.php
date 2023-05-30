<?php


namespace App\Repositories\Interfaces;


interface EmployeesVideoEditingRepositoryInterface
{
    public function all();

    public function find($id, $columns = array('*'));

    public function create(array $input);

    public function destroy($id);

    public function update($id, $employee_details_id, array $input);

    public function findByProject($employee_details_id, $project_company_id);

}
