<?php

namespace App\Repositories\Bill;


interface BillRepositoryInterface
{

    public function save($data);

    public function all();

    public function update($bill, $data);

    public function get($number);

    public function delete($bill);

    public function getCount($string);
}
