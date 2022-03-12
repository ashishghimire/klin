<?php


namespace App\Services;


use App\Repositories\Customer\CustomerRepositoryInterface;

class CustomerService
{

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customer;

    public function __construct(CustomerRepositoryInterface $customer)
    {
        $this->customer = $customer;
    }

    public function save($data)
    {

        $customer = $this->customer->save($data);

        if (!$customer) {
            return false;
        }

        return true;
    }


    public function find($id)
    {
        try {
            $customer = $this->customer->find($id);
        } catch (\Exception $e) {
            return null;
        }

        return $customer;
    }

    public function update($id, $data)
    {
        $customer = $this->customer->find($id);

        return $this->customer->update($customer, $data);
    }

    public function all()
    {
        return $this->customer->all();
    }

    public function delete($id)
    {
        $customer = $this->customer->find($id);

        return $this->customer->delete($customer);
    }

}
