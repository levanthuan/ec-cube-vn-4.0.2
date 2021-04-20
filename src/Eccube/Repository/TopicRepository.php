<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eccube\Repository;

use Eccube\Entity\Topic;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * TopicRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TopicRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Topic::class);
    }

    /**
     * トピック覧を取得する.
     *
     * @return Topic[] トピックの配列
     */
    public function getList()
    {
        $qb = $this->createQueryBuilder('tp')->addOrderBy('tp.sort', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * トピック覧を取得する.
     *
     * @return Topic[] トピックの配列
     */
    public function getListTopicOn()
    {
        $qb = $this->createQueryBuilder('tp')->where('tp.status = 1')->addOrderBy('tp.sort', 'ASC');

        return $qb->getQuery()->getResult();
    }
}