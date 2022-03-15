<?php

namespace App\Repositories\Bill;


use App\Models\Bill;
use Illuminate\Support\Facades\DB;

class BillRepository implements BillRepositoryInterface
{
    /**
     * @var Bill
     */
    protected $bill;

    /**
     * BillRepository constructor.
     * @param Bill $bill
     */
    public function __construct(Bill $bill)
    {
        $this->bill = $bill;
    }

    public function save($data)
    {
        DB::beginTransaction();

        try {
            $bill = $this->bill->create($data);

            DB::commit();
            return $bill;

        } catch (\Exception $e) {
            dd($e);// This is for debugging purpose only. Remove it!!
            DB::rollback();
            return false;
        }
    }

    public function all()
    {
            return $this->bill->all();
    }
}
