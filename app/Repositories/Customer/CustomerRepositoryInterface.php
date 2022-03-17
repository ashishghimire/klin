<?php


namespace App\Repositories\Customer;


interface CustomerRepositoryInterface
{
    public function save($data);

    public function find($id);

    public function update($customer, $data);

    public function all();

    public function delete($customer);

    public function count();
}
