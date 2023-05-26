<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Entities\Customers;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class CustomerRepository
 * @package App\Repositories
 * @author  Mark Joshua Fajardo <mjt.fajardo@gmail.com>
 * @since   2023.05.25
 */
class CustomerRepository
{
    /**
     * Entity Manager Interface
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * Entity name
     * @var string
     */
    private $entity = Customers::class;

    /**
     * CustomerRepository constructor
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Insert or update customers in the database
     * @param array $customers
     */
    public function insertOrUpdateCustomers(array $customers): void
    {
        foreach ($customers as $customer) {
            $currentEntity = $this->entityManager->getRepository($this->entity)->findByEmail($customer['email']);
    
            if (!$currentEntity) {
                $currentEntity = new Customers();
                $currentEntity->setEmail($customer['email']);
                $currentEntity->setCreatedAt(new DateTime());
            } else {
                $currentEntity = $currentEntity[0];
                $currentEntity->setUpdatedAt(new DateTime());
            }
    
            $currentEntity->setFirstName($customer['first_name']);
            $currentEntity->setLastName($customer['last_name']);
            $currentEntity->setGender($customer['gender']);

            $currentEntity->setUserName($customer['username']);
            // Hash the password using MD5 algorithm
            $hashedPassword = md5($customer['password']);
            $currentEntity->setPassword($hashedPassword);
            
            $currentEntity->setCountry($customer['country']);
            $currentEntity->setCity($customer['city']);
            $currentEntity->setPhone($customer['phone']);
    
            $this->entityManager->persist($currentEntity);
        }
    
        $this->entityManager->flush();
    }

    /**
     * Find all customers available in the database with specific fields to be fetched.
     * @return array
     */
    public function findAllCustomers(): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('c.' . implode(', c.', [
            'first_name',
            'last_name',
            'email',
            'country'
        ]))->from($this->entity, 'c');
        
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Find a customer in the database with all the fields to be fetched.
     * @return array
     */
    public function findCustomer(int $customerId): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('c.' . implode(', c.', [
            'first_name',
            'last_name',
            'email',
            'username',
            'gender',
            'country',
            'city',
            'phone'
        ]))->from($this->entity, 'c')
            ->where('c.id = ' . $customerId);
        
        return $queryBuilder->getQuery()->getResult();
    }
}
