<?php
declare(strict_types=1);

use App\Repositories\CustomerRepository;

/**
 * Class CustomerControllersTest
 * @package Tests
 * @author  Mark Joshua Fajardo <mjt.fajardo@gmail.com>
 * @since   2023.05.26
 */
class CustomerControllersTest extends TestCase
{
    /**
     * Test findAllCustomers method when the response is successful
     * @return void
     */
    public function test_findAllCustomers_API_when_the_request_is_correct_then_return_a_successful_response()
    {
        // Mock the Laravel Doctrine repository
        $mockRepository = $this->getMockBuilder(CustomerRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
    
        // Create a sample response from the repository
        $customers = [
            [
                'first_name' => 'First Name',
                'last_name'  => 'Last Name',
                'email'      => 'test@gmail.com',
                'country'    => 'Australia',
            ],
            [
                'first_name' => 'First Name 2',
                'last_name'  => 'Last Name 2',
                'email'      => 'test2@gmail.com',
                'country'    => 'Australia',
            ]
        ];
        $mockRepository->expects($this->once())
            ->method('findAllCustomers')
            ->willReturn($customers);
    
        // Replace the repository instance in the controller with the mocked instance
        $this->app->instance(CustomerRepository::class, $mockRepository);
    
        // Make a GET request to the API endpoint
        $response = $this->get('/api/customers');
    
        // Assert the response status code is 200 (OK)
        $response->assertResponseStatus(200);
    
        // Assert the response structure or content based on the transformer
        $response->seeJsonStructure([
            'data' => [
                '*' => [
                    'name',
                    'email',
                    'country',
                ]
            ],
        ]);
    
        // Assert JSON response body if correct
        $response->seeJson([
            'data' => [
                [
                    'name'    => 'First Name Last Name',
                    'email'   => 'test@gmail.com',
                    'country' => 'Australia',
                ],
                [
                    'name'    => 'First Name 2 Last Name 2',
                    'email'   => 'test2@gmail.com',
                    'country' => 'Australia',
                ]
            ],
        ]);
    }

    /**
     * Test findAllCustomers method when the response is empty
     * @return void
     */
    public function test_findAllCustomers_API_when_the_request_is_correct_then_return_an_empty_response()
    {
        // Mock the Laravel Doctrine repository
        $mockRepository = $this->getMockBuilder(CustomerRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
    
        // Create a sample response from the repository
        $customers = [];
        $mockRepository->expects($this->once())
            ->method('findAllCustomers')
            ->willReturn($customers);
    
        // Replace the repository instance in the controller with the mocked instance
        $this->app->instance(CustomerRepository::class, $mockRepository);
    
        // Make a GET request to the API endpoint
        $response = $this->get('/api/customers');
    
        // Assert the response status code is 200 (OK)
        $response->assertResponseStatus(200);
    
        // Assert the response structure or content based on the transformer
        $response->seeJsonStructure([
            'data',
        ]);
    
        // Assert JSON response body if correct
        $response->seeJson([
            'data' => [],
        ]);
    }

    /**
     * Test findAllCustomers method when the customer is existing
     * @return void
     */
    public function test_findCustomer_API_when_the_customer_is_existing_then_return_a_successful_response()
    {
        // Mock the Laravel Doctrine repository
        $mockRepository = $this->getMockBuilder(CustomerRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
    
        // Create a sample response from the repository
        $customers = [
            'first_name' => 'First Name',
            'last_name'  => 'Last Name',
            'email'      => 'test@gmail.com',
            'username'   => 'username',
            'gender'     => 'male',
            'country'    => 'Australia',
            'city'       => 'City',
            'phone'      => '0000-0000',
        ];
        $mockRepository->expects($this->once())
            ->method('findCustomer')
            ->willReturn($customers);
    
        // Replace the repository instance in the controller with the mocked instance
        $this->app->instance(CustomerRepository::class, $mockRepository);
    
        // Make a GET request to the API endpoint
        $response = $this->get('/api/customers/1');
    
        // Assert the response status code is 200 (OK)
        $response->assertResponseStatus(200);
    
        // Assert the response structure or content based on the transformer
        $response->seeJsonStructure([
            'data' => [
                'name',
                'email',
                'username',
                'gender',
                'country',
                'city',
                'phone',
            ],
        ]);
    
        // Assert JSON response body if correct
        $response->seeJson([
            'data' => [
                'name'     => 'First Name Last Name',
                'email'    => 'test@gmail.com',
                'username' => 'username',
                'gender'   => 'male',
                'country'  => 'Australia',
                'city'     => 'City',
                'phone'    => '0000-0000',
            ],
        ]);
    }

    /**
     * Test findAllCustomers method when the customer is not existing
     * @return void
     */
    public function test_findCustomer_API_when_the_customer_is_not_existing_then_return_an_error_response()
    {
        // Mock the Laravel Doctrine repository
        $mockRepository = $this->getMockBuilder(CustomerRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
    
        // Create a sample response from the repository
        $customers = [];
        $mockRepository->expects($this->once())
            ->method('findCustomer')
            ->willReturn($customers);
    
        // Replace the repository instance in the controller with the mocked instance
        $this->app->instance(CustomerRepository::class, $mockRepository);
    
        // Make a GET request to the API endpoint
        $response = $this->get('/api/customers/2');
    
        // Assert the response status code is 200 (OK)
        $response->assertResponseStatus(404);
    
        // Assert the response structure or content based on the transformer
        $response->seeJsonStructure([
            'error',
        ]);
    
        // Assert JSON response body if correct
        $response->seeJson([
            'error' => [
                'status'  => 404,
                'message' => 'Not Found'
            ],
        ]);
    }

    /**
     * Test an unknown API route then return an error response
     * @return void
     */
    public function test_unknown_API_route_then_return_an_error_response()
    {
        // Make a GET request to the API endpoint
        $response = $this->get('/api/test');
    
        // Assert the response status code is 200 (OK)
        $response->assertResponseStatus(404);
    
        // Assert the response structure or content based on the transformer
        $response->seeJsonStructure([
            'error',
        ]);
    
        // Assert JSON response body if correct
        $response->seeJson([
            'error' => [
                'status'  => 404,
                'message' => 'Not Found'
            ],
        ]);
    }
}
