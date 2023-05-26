<?php
declare(strict_types=1);

namespace App\Services;

use App\Constants\ApiConstants;
use App\Exceptions\RandomUserRequestException;
use App\Libraries\Apis\RandomUser\UserApi as RandomUserApi;
use App\Repositories\CustomerRepository;

/**
 * Class ImportCustomerDataService
 * @package App\Services
 * @author  Mark Joshua Fajardo <mjt.fajardo@gmail.com>
 * @since   2023.05.25
 */
class ImportCustomerDataService
{
    /**
     * Contains the RandomUser User API instance
     * @var RandomUserApi
     */
    private RandomUserApi $userApi;
    
    /**
     * Contains the Customer Repository instance
     * @var CustomerRepository
     */
    private CustomerRepository $customerRepository;

    /**
     * ImportCustomerDataService constructor
     */
    public function __construct(RandomUserApi $userApi, CustomerRepository $customerRepository)
    {
        $this->userApi = $userApi;
        $this->customerRepository = $customerRepository;
    }

    /**
     * Fetch all customer data
     * @param int $results
     * @throws RandomUserRequestException
     * @return array
     */
    public function fetchCustomers(int $results): array
    {
        $response = $this->userApi->getRandomAussieUsers($results);
        if ($response[ApiConstants::SUCCESS] === false) {
            throw new RandomUserRequestException($response[ApiConstants::CODE]);
        }

        return $response;
    }

    /**
     * Import customers data
     * 1. Prepare the customers' data
     * 2. Insert/Update data in the database.
     * @param array $customers
     */
    public function importCustomers(array $customers)
    {
        $customers = $this->prepareCustomersData($customers);
        $this->customerRepository->insertOrUpdateCustomers($customers);
    }

    /**
     * Prepare the customers' data to be imported
     * @param array $customers
     * @return array
     */
    private function prepareCustomersData(array $customers): array
    {
        return array_map(function ($item) {
            return [
                'first_name' => $item['name']['first'],
                'last_name'  => $item['name']['last'],
                'email'      => $item['email'],
                'username'   => $item['login']['username'],
                'password'   => $item['login']['password'],
                'gender'     => $item['gender'],
                'country'    => $item['location']['country'],
                'city'       => $item['location']['city'],
                'phone'      => $item['phone'],
            ];
        }, $customers[ApiConstants::DATA]);
    }
}
