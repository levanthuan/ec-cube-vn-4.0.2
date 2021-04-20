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

namespace Eccube\Entity;

use Doctrine\ORM\Mapping as ORM;

if (!class_exists('\Eccube\Entity\Holiday')) {
    /**
     * Holiday
     *
     * @ORM\Table(name="dtb_holiday")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\HolidayRepository")
     */
    class Holiday extends \Eccube\Entity\AbstractEntity
    {
        /**
         * @var int
         *
         * @ORM\Column(name="id", type="integer", options={"unsigned":true})
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;

        /**
         * 日
         *
         * @var \Date
         *
         * @ORM\Column(name="date", type="date")
         */
        private $date;

        /**
         * @var string
         *
         * @ORM\Column(name="name", type="string", length=255, nullable=true)
         */
        private $name;

        /**
         * Set id
         *
         * @return Holiday
         */
        public function setId($id)
        {
            $this->id = $id;

            return $this;
        }

        /**
         * Get id
         *
         * @return integer
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * Set name.
         *
         * @param string $name
         *
         * @return Holiday
         */
        public function setName($name)
        {
            $this->name = $name;

            return $this;
        }

        /**
         * Get name.
         *
         * @return string
         */
        public function getName()
        {
            return $this->name;
        }

        /**
         * Set date.
         *
         * @param string $date
         *
         * @return Holiday
         */
        public function setDate($date)
        {
            $this->date = $date;

            return $this;
        }

        /**
         * Get date.
         *
         * @return string
         */
        public function getDate()
        {
            return $this->date;
        }
    }
}