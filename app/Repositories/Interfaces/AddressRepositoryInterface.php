<?php

namespace App\Repositories\Interfaces;

interface AddressRepositoryInterface
{
    /**
     * @return mixed
     */
    public function all() : mixed;

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, array $columns = array('*')) : mixed;

    /**
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes) : mixed;

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
    public function update($id, array $input) : mixed;
}
