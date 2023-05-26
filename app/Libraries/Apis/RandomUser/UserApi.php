<?php
declare(strict_types=1);

namespace App\Libraries\Apis\RandomUser;

use App\Constants\ApiConstants;
use App\Libraries\Apis\BaseApi;

/**
 * Class UserApi
 * @package App\Libraries\Apis\RandomUser
 * @author  Mark Joshua Fajardo <mjt.fajardo@gmail.com>
 * @since   2023.05.25
 */
class UserApi extends BaseApi
{
    /**
     * UserApi constructor
     */
    public function __construct()
    {
        // Base URIs are better if it will be set on configs
        $this->setBaseUri('https://randomuser.me/api');
    }

    /**
     * Get random Australian users
     * @param int $results
     * @return array{success: bool, data: mixed}
     */
    public function getRandomAussieUsers(int $results = 100): array
    {
        return $this->request(ApiConstants::GET, '/', [
            ApiConstants::RESULTS     => $results,
            ApiConstants::NATIONALITY => 'AU',
            ApiConstants::INCLUDE     => 'name,email,login,gender,location,phone',
        ]);
    }
}
