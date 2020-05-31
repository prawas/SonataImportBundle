<?php


namespace Doctrs\SonataImportBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrs\SonataImportBundle\Entity\UploadFile;

class DefaultRepository extends EntityRepository {

    public function pagerfanta(Request $request, ?UploadFile $uploadFile = null) {
        $sql = $this->createQueryBuilder('data');
        $sql
            ->select('data')
            ->orderBy('data.id', 'DESC')
        ;

        if ($uploadFile) {
            $sql
                ->andWhere('data.uploadFile = :file')
                ->setParameter('file', $uploadFile)
            ;
        }
        switch ($request->get('type', 'all')) {
            case 'success':
                $sql->andWhere('data.status = 1 or data.status = 2');
                break;
            case 'new':
                $sql->andWhere('data.status = 1');
                break;
            case 'update':
                $sql->andWhere('data.status = 2');
                break;
            case 'error':
                $sql->andWhere('data.status = 3');
                break;
        }
        return $sql->getQuery();
    }

    public function count(array $where = []) {
        $sql = $this->createQueryBuilder('data');
        $sql->select('COUNT(data)');
        if (sizeof($where)) {
            foreach ($where as $key => $value) {
                $sql->andWhere('data.' . $key . ' = :' . $key);
                $sql->setParameter($key, $value);
            }
        }

        return $sql->getQuery()->getSingleScalarResult();
    }

}
