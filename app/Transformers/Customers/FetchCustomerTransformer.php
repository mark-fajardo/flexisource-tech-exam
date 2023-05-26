<?php
declare(strict_types=1);

namespace App\Transformers\Customers;

use App\Transformers\BaseTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class FetchCustomerTransformer
 * @package App\Transformers\Customers
 * @author  Mark Joshua Fajardo <mjt.fajardo@gmail.com>
 * @since   2023.05.26
 */
class FetchCustomerTransformer extends BaseTransformer
{
    /**
     * Transform response
     * @return JsonResponse
     */
    public function transform(): JsonResponse
    {
        return $this->respond($this->createItem()->transformWith(function ($data) {
            return [
                'data' => [
                    'name'     => sprintf('%s %s', $data['first_name'], $data['last_name']),
                    'email'    => $data['email'],
                    'username' => $data['username'],
                    'gender'   => $data['gender'],
                    'country'  => $data['country'],
                    'city'     => $data['city'],
                    'phone'    => $data['phone'],
                ]
            ];
        }));
    }
}
