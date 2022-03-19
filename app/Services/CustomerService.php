<?php


namespace App\Services;


use App\Models\Setting;
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

        return $customer;
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

    public function update($customer, $data)
    {
        return $this->customer->update($customer, $data);
    }

    public function all()
    {
        return $this->customer->all();
    }

    public function delete($customer)
    {
        return $this->customer->delete($customer);
    }

    public function total()
    {
        return $this->customer->count();
    }

    public function giveRewardPoints($customerId, $amount)
    {
        $customer = $this->find($customerId);

        $rewardKey = Setting::first()->rewards_key;

        $rewardPoints = floatval($amount) * floatval($rewardKey);


        $customer->reward_points += floatval($rewardPoints);

        $customer->save();
    }

    public function payWithRewardPoints($customerId, $amount)
    {
        $customer = $this->find($customerId);

        $customer->reward_points -= $amount;

        $customer->save();

    }

    public function updateRewardPoints($customerId, $previousAmount, $amount)
    {
        $customer = $this->find($customerId);

        $rewardKey = Setting::first()->rewards_key;

        $previousRewardPoints = floatval($previousAmount) * floatval($rewardKey);

        $changedRewardPoints = floatval($amount) * floatval($rewardKey);

        $customer->reward_points = $customer->reward_points - $previousRewardPoints + $changedRewardPoints;

        $customer->save();
    }

    public function removeRewardPoints($customerId, $amount)
    {
        $customer = $this->find($customerId);

        $rewardKey = Setting::first()->rewards_key;

        $rewardPoints = floatval($amount) * floatval($rewardKey);

        $customer->reward_points -= $rewardPoints;

        $customer->save();
    }

}
