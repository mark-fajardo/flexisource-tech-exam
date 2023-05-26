<?php

use App\Services\ImportCustomerDataService;
use App\Exceptions\RandomUserRequestException;
use App\Libraries\Apis\RandomUser\UserApi as RandomUserApi;
use App\Constants\ApiConstants;

/**
 * Class ImportCustomerDataServiceTest
 * @package Tests
 * @author  Mark Joshua Fajardo <mjt.fajardo@gmail.com>
 * @since   2023.05.26
 */
class ImportCustomerDataServiceTest extends TestCase
{
    /**
     * Test fetchCustomers method when the API response is successful
     * @return void
     */
    public function test_fetchCustomers_service_method_when_fetching_with_successful_response()
    {
        // Create a mock for the RandomUserApi class
        $mockApi = $this->createMock(RandomUserApi::class);
        
        // Define the expected API response
        $response = [
            ApiConstants::SUCCESS => true,
            ApiConstants::CODE    => 200,
            ApiConstants::DATA    => [
                [
                    'gender'   => 'male',
                    'name'     => [
                        'first_name' => 'First Name',
                        'last_name'  => 'Last Name',
                    ],
                    'location' => [
                        'city'    => 'City',
                        'country' => 'Australia',
                    ],
                    'email'    => 'test@gmail.com',
                    'login'    => [
                        'username' => 'username',
                        'password' => 'password',
                    ],
                    'phone'    => '0000-0000',
                ],
                [
                    'gender'   => 'female',
                    'name'     => [
                        'first_name' => 'First Name 2',
                        'last_name'  => 'Last Name 2',
                    ],
                    'location' => [
                        'city'    => 'City 2',
                        'country' => 'Australia',
                    ],
                    'email'    => 'test2@gmail.com',
                    'login'    => [
                        'username' => 'username2',
                        'password' => 'password2',
                    ],
                    'phone'    => '0000-0001',
                ],
            ]
        ];

        // Set up the mock to return the expected response
        $mockApi->expects($this->once())
            ->method('getRandomAussieUsers')
            ->with($this->equalTo(2)) // Assuming 2 as the input parameter
            ->willReturn($response);

        // Create an instance of the ImportCustomerDataService class with the mocked RandomUserApi
        $service = app()->make(ImportCustomerDataService::class, [
            'userApi' => $mockApi
        ]);

        // Call the fetchCustomers method
        $result = $service->fetchCustomers(2);

        // Assert that the result matches the expected response
        $this->assertEquals($response, $result);
    }

    /**
     * Test fetchCustomers method when the API response is unsuccessful.
     * @return void
     */
    public function test_fetchCustomers_service_method_when_fetching_with_error_response()
    {
        // Create a mock for the RandomUserApi class
        $mockApi = $this->createMock(RandomUserApi::class);
        
        // Define the expected API response with failure
        $response = [
            ApiConstants::SUCCESS => false,
            ApiConstants::CODE => 500,
        ];

        // Set up the mock to return the expected response
        $mockApi->expects($this->once())
            ->method('getRandomAussieUsers')
            ->with($this->equalTo(2)) // Assuming 2 as the input parameter
            ->willReturn($response);

        // Create an instance of the ImportCustomerDataService class with the mocked RandomUserApi
        $service = app()->make(ImportCustomerDataService::class, [
            'userApi' => $mockApi
        ]);

        // Assert that the fetchCustomers method throws the expected exception
        $this->expectException(RandomUserRequestException::class);
        $this->expectExceptionCode($response[ApiConstants::CODE]);

        // Call the fetchCustomers method
        $service->fetchCustomers(2);
    }

    /**
     * Test importCustomers method when processing the import then do no treturn any exceptions.
     * @return void
     */
    public function test_importCustomers_service_method_when_processing_the_import_then_do_not_return_any_exceptions()
    {
        // Create a mock for the RandomUserApi class
        $mockApi = $this->createMock(RandomUserApi::class);
    
        // Create a sample array of customers
        $customers = [
            ApiConstants::DATA    => [
                [
                    'gender'   => 'male',
                    'name'     => [
                        'first' => 'First Name',
                        'last'  => 'Last Name',
                    ],
                    'location' => [
                        'city'    => 'City',
                        'country' => 'Australia',
                    ],
                    'email'    => 'test@gmail.com',
                    'login'    => [
                        'username' => 'username',
                        'password' => 'password',
                    ],
                    'phone'    => '0000-0000',
                ],
                [
                    'gender'   => 'female',
                    'name'     => [
                        'first' => 'First Name 2',
                        'last'  => 'Last Name 2',
                    ],
                    'location' => [
                        'city'    => 'City 2',
                        'country' => 'Australia',
                    ],
                    'email'    => 'test2@gmail.com',
                    'login'    => [
                        'username' => 'username2',
                        'password' => 'password2',
                    ],
                    'phone'    => '0000-0001',
                ],
            ]
        ];
    
        // Create an instance of the ImportCustomerDataService class with the mocked RandomUserApi
        $service = app()->make(ImportCustomerDataService::class, [
            'userApi' => $mockApi
        ]);
    
        // Call the importCustomers method
        $service->importCustomers($customers);
    
        // Assert that there are no exceptions upon doing the process
        $this->assertTrue(true);
    }

    /**
     * Test importCustomers method when processing the import then do no treturn any exceptions.
     * @return void
     */
    public function test_importCustomers_service_method_when_processing_the_import_with_insufficient_data_then_return_an_exception()
    {
        // Create a mock for the RandomUserApi class
        $mockApi = $this->createMock(RandomUserApi::class);
    
        // Create a sample array of customers
        $customers = [
            ApiConstants::DATA    => [
                [
                    'gender'   => 'male',
                    'location' => [
                        'city'    => 'City',
                        'country' => 'Australia',
                    ],
                    'email'    => 'test@gmail.com',
                    'login'    => [
                        'username' => 'username',
                        'password' => 'password',
                    ],
                    'phone'    => '0000-0000',
                ],
                [
                    'gender'   => 'female',
                    'location' => [
                        'city'    => 'City 2',
                        'country' => 'Australia',
                    ],
                    'email'    => 'test2@gmail.com',
                    'login'    => [
                        'username' => 'username2',
                        'password' => 'password2',
                    ],
                    'phone'    => '0000-0001',
                ],
            ]
        ];
    
        // Create an instance of the ImportCustomerDataService class with the mocked RandomUserApi
        $service = app()->make(ImportCustomerDataService::class, [
            'userApi' => $mockApi
        ]);
    
        // Assert that there will be exceptions upon doing the process
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('Undefined array key "name"');

        // Call the importCustomers method
        $service->importCustomers($customers);
    }
}
