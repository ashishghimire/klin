<?php

namespace App\Services;


use App\Repositories\Bill\BillRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class BillService
{
    /**
     * @var BillRepositoryInterface
     */
    protected $bill;
    /**
     * @var CustomerService
     */
    protected $customer;

    /**
     * BillService constructor.
     * @param BillRepositoryInterface $bill
     * @param CustomerService $customer
     */
    public function __construct(BillRepositoryInterface $bill, CustomerService $customer)
    {

        $this->bill = $bill;
        $this->customer = $customer;
    }

    public function save($customerId, $data)
    {
        $processedData = $this->processData($customerId, $data);


        $bill = $this->bill->save($processedData);

        if ($data['payment_mode'] != 'reward points') {
            $this->customer->giveRewardPoints($customerId, $data['amount']);
        } else {
            $this->customer->payWithRewardPoints($customerId, $data['amount']);
        }


        if (!$bill) {
            return false;
        }

        return $bill;
    }

    public function processData($customerId, $data)
    {
        $data['user_id'] = Auth::user()->id;
        $data['customer_id'] = $customerId;
        $data['amount'] = $this->calculateAmount($data['service_details']);

        if (!empty($data['payment_mode'])) {
            $data['payment_status'] = 'paid';
            $data['paid_amount'] = $data['amount'];
        } else {
            $data['payment_status'] = 'unpaid';
            $data['paid_amount'] = 0;
        }

//        if ($data['payment_mode'] == 'reward points') {
//            $data['payment_status'] = 'paid';
//            $data['paid_amount'] = $data['amount'];
//            return $data;
//        }
//
//        if (empty($data['paid_amount'])) {
//            $data['payment_status'] = 'unpaid';
//            $data['payment_mode'] = null;
//        } elseif ($data['paid_amount'] >= $data['amount']) {
//            $data['payment_status'] = 'paid';
//        } else {
//            $data['payment_status'] = 'partial';
//        }


        return $data;
    }

    public function calculateAmount($serviceDetails)
    {
//        dd($serviceDetails);
        $amount = 0.0;
        foreach ($serviceDetails as $detail) {
            $amount += $detail['quantity'] * $detail['rate'];
        }

        return $amount;
    }

    public function all()
    {
        return $this->bill->all();
    }

    public function update($bill, $data)
    {
        $customerId = $bill->customer->id;

        $previousAmount = $bill->amount;

        $processedData = $this->processData($customerId, $data);

        $updated = $this->bill->update($bill, $processedData);

        if ($updated) {
            $this->customer->updateRewardPoints($customerId, $previousAmount, $data['amount']);
        }

        return $updated;
    }


    public function get($number)
    {
        return $this->bill->get($number);
    }

    public function delete($bill)
    {
        $amount = $bill->amount;

        $customerId = $bill->customer->id;

        $deleted = $this->bill->delete($bill);

        if ($deleted) {
            $this->customer->removeRewardPoints($customerId, $amount);
        }

        return $deleted;
    }


    public function getCount($string, $total)
    {
        return $this->bill->getCount($string, $total);
    }
}
