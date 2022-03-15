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
     * BillService constructor.
     * @param BillRepositoryInterface $bill
     */
    public function __construct(BillRepositoryInterface $bill)
    {

        $this->bill = $bill;
    }

    public function save($customerId, $data)
    {
        $data['user_id'] = Auth::user()->id;
        $data['customer_id'] = $customerId;
        $data['amount'] = $this->calculateAmount($data['service_details']);


        if (empty($data['paid_amount'])) {
            $data['payment_status'] = 'unpaid';
        } elseif ($data['paid_amount'] >= $data['amount']) {
            $data['payment_status'] = 'paid';
        } else {
            $data['payment_status'] = 'partial';
        }

        $bill = $this->bill->save($data);


        if (!$bill) {
            return false;
        }

        return $bill;
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
}
