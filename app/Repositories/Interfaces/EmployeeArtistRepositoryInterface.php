<?php


namespace App\Repositories\Interfaces;



interface EmployeeArtistRepositoryInterface
{
    /**
     * @return mixed
     */
    public function all();

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, array $columns = array('*')) : mixed;

    /**
     * @param array $input
     * @return mixed
     */
    public function create(array $input) : mixed;

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id) : mixed;

    /**
     * @param $id
     * @param array $input
     * @return mixed
     */
    public function update($id, $employee_details_id, array $input) : mixed;

    public function findByProject($employee_details_id, $project_company_id);
}
