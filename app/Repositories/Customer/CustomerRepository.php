<?php


namespace App\Repositories\Customer;


use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CustomerRepository implements CustomerRepositoryInterface
{
    /**
     * @var Customer
     */
    protected $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }

    public function save($data)
    {
        DB::beginTransaction();

        try {
            $customer = $this->customer->create($data);
            DB::commit();
            return $customer;

        } catch (\Exception $e) {
            dd($e);// This is for debugging purpose only. Remove it!!
            DB::rollback();
            return false;
        }
    }

    public function find($id)
    {
        return $this->customer->findOrFail($id);
    }

    /**
     * @param $customer
     * @param $data
     * @return bool
     */
    public function update($customer, $data)
    {
        DB::beginTransaction();

        try {

            $updated = $customer->update($data);

            DB::commit();

        } catch (\Exception $e) {
            dd($e);// This is for debugging purpose only. Remove it!!
            DB::rollback();
            return false;
        }
        return $updated;
    }

    public function all()
    {
        return $this->customer->all();
    }

    public function delete($customer)
    {
        DB::beginTransaction();

        try {

            $deleted = $customer->delete();

            DB::commit();

        } catch (\Exception $e) {
            dd($e);// This is for debugging purpose only. Remove it!!
            DB::rollback();
            return false;
        }
        return $deleted;

    }

    public function count()
    {
        return $this->customer->count();
    }
}
