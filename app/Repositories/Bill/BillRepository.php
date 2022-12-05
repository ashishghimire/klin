<?php

namespace App\Repositories\Bill;


use App\Models\Bill;
use Carbon\Carbon;
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

    public function update($bill, $data)
    {
        DB::beginTransaction();

        try {

            $updated = $bill->update($data);

            DB::commit();

        } catch (\Exception $e) {
            dd($e);// This is for debugging purpose only. Remove it!!
            DB::rollback();
            return false;
        }
        return $updated;
    }

    public function get($number)
    {
        return $this->bill->orderBy('created_at', 'desc')->take($number)->get();
    }

    public function delete($bill)
    {
        DB::beginTransaction();

        try {

            $deleted = $bill->delete();

            DB::commit();

        } catch (\Exception $e) {
            dd($e);// This is for debugging purpose only. Remove it!!
            DB::rollback();
            return false;
        }
        return $deleted;
    }

    public function getCount($string, $total = false)
    {
        if ($total) {
            return $this->bill->where('laundry_status', $string)->count();
        }

        else {
            return $this->bill->where('laundry_status', $string)->whereDate('created_at', Carbon::now())->count();
        }
    }
}
