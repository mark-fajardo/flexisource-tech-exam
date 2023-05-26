<?php
declare(strict_types=1);

namespace App\Transformers\Customers;

use App\Transformers\BaseTransformer;
use Illuminate\Http\JsonResponse;

/**
 * Class FetchAllCustomersTransformer
 * @package App\Transformers\Customers
 * @author  Mark Joshua Fajardo <mjt.fajardo@gmail.com>
 * @since   2023.05.26
 */
class FetchAllCustomersTransformer extends BaseTransformer
{
    /**
     * Transform response
     * @return JsonResponse
     */
    public function transform(): JsonResponse
    {
        return $this->respond($this->createCollection()->transformWith(function ($data) {
            return [
                'name'    => sprintf('%s %s', $data['first_name'], $data['last_name']),
                'email'   => $data['email'],
                'country' => $data['country'],
            ];
        }));
    }
}
