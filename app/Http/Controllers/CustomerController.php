<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\CustomerRepository;
use App\Transformers\Customers\FetchAllCustomersTransformer;
use App\Transformers\Customers\FetchCustomerTransformer;
use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * Class CustomerController
 * @package App\Controllers
 * @author  Mark Joshua Fajardo <mjt.fajardo@gmail.com>
 * @since   2023.05.26
 */
class CustomerController extends BaseController
{
    /**
     * Contains the Customer Repository instance
     * @var CustomerRepository
     */
    private CustomerRepository $customerRepository;

    /**
     * CustomerController constructor
     */
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * Find all existing customers in the database
     * Transform response using fractal
     * @return JsonResponse
     */
    public function findAllCustomers(): JsonResponse
    {
        $customers = $this->customerRepository->findAllCustomers();
        $response = (new FetchAllCustomersTransformer())
            ->response($customers)
            ->transform();

        // Response can be logged in here
        return $response;
    }

    /**
     * Find an existing customer in the database
     * Transform response using fractal
     * @return JsonResponse
     */
    public function findCustomer(int $customerId): JsonResponse
    {
        $customer = $this->customerRepository->findCustomer($customerId);
        $response = (new FetchCustomerTransformer())
            ->response(data_get($customer, '0', []))
            ->transform();

        // Response can be logged in here
        return $response;
    }
}
