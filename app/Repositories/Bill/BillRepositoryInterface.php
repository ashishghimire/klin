<?php

namespace App\Repositories\Bill;


interface BillRepositoryInterface
{

    public function save($data);

    public function all();
}
