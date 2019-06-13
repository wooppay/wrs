<?php

namespace App\Repository;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Security;
use App\Enum\PermissionEnum;
use App\Enum\UserEnum;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    private $security;
    
    public function __construct(RegistryInterface $registry, Security $security)
    {
        parent::__construct($registry, User::class);
        
        $this->security = $security;
    }
    
    public function allExceptAdminAndOwner()
    {
        $collection = $this->findAll();
        $result = [];
        
        foreach ($collection as $item) {
            if (!$this->security->isGranted(PermissionEnum::CAN_BE_PRODUCT_OWNER, $item) && !$this->security->isGranted(PermissionEnum::CAN_BE_ADMIN, $item)) {
                $result[] = $item;
            }
        }
        
        return $result;
    }
    
    public function allApprovedExceptAdminAndOwnerAndCustomer()
    {
        $collection = $this->findBy([
            'status' => UserEnum::APPROVED,
        ]);

        $result = [];
        
        foreach ($collection as $item) {
            if (
                !$this->security->isGranted(PermissionEnum::CAN_BE_PRODUCT_OWNER, $item) &&
                !$this->security->isGranted(PermissionEnum::CAN_BE_ADMIN, $item) &&
                !$this->security->isGranted(PermissionEnum::CAN_BE_CUSTOMER, $item)
              ) {
                $result[] = $item;
            }
        }
        
        return $result;
    }

    public function byEmail(string $email) : User
    {
        return $this->findOneBy([
            'email' => $email, 
        ]);
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function detachRole(User $user, Role $role)
	{
		$qb = $this->_em->getConnection()->createQueryBuilder();

		return $qb->delete('user_role', 'ur')
			->where('ur.user_id = :user_id AND ur.role_id = :role_id')
			->setParameters([
				':user_id' => $user->getId(),
				':role_id' => $role->getId(),
			])->execute() > 0;
	}
}
