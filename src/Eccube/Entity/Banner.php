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

if (!class_exists('\Eccube\Entity\Banner')) {
    /**
     * Banner
     *
     * @ORM\Table(name="dtb_banner")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\BannerRepository")
     */
    class Banner extends \Eccube\Entity\AbstractEntity
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
         * @var string
         *
         * @ORM\Column(name="title", type="string", length=255, nullable=true)
         */
        private $title;

        /**
         * @var string
         *
         * @ORM\Column(name="url", type="string", length=255, nullable=true)
         */
        private $url;

        /**
         * @var string
         *
         * @ORM\Column(name="image", type="string", length=255, nullable=true)
         */
        private $image;

        /**
         * @var integer
         *
         * @ORM\Column(name="type", type="integer", length=1, nullable=true)
         */
        private $type;

        /**
         * @var boolean
         *
         * @ORM\Column(name="status", type="boolean", options={"default":false})
         */
        private $status = false;

        /**
         * @var integer
         *
         * @ORM\Column(name="device", type="integer", length=1, nullable=true)
         */
        private $device;

        /**
         * @var integer
         *
         * @ORM\Column(name="sort", type="integer", length=10, nullable=true)
         */
        private $sort;

        /**
         * Set id
         *
         * @return Banner
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
         * Set title.
         *
         * @param string $title
         *
         * @return Banner
         */
        public function setTitle($title)
        {
            $this->title = $title;

            return $this;
        }

        /**
         * Get title.
         *
         * @return string
         */
        public function getTitle()
        {
            return $this->title;
        }

        /**
         * Set url.
         *
         * @param string $url
         *
         * @return Banner
         */
        public function setUrl($url)
        {
            $this->url = $url;

            return $this;
        }

        /**
         * Get url.
         *
         * @return string
         */
        public function getUrl()
        {
            return $this->url;
        }

        /**
         * Set image.
         *
         * @param string|null $image
         *
         * @return Banner
         */
        public function setImage($image = null)
        {
            $this->image = $image;

            return $this;
        }

        /**
         * Get image.
         *
         * @return string|null
         */
        public function getImage()
        {
            return $this->image;
        }

        /**
         * Set type.
         *
         * @param integer|null $type
         *
         * @return Banner
         */
        public function setType($type = null)
        {
            $this->type = $type;

            return $this;
        }

        /**
         * Get type.
         *
         * @return integer|null
         */
        public function getType()
        {
            return $this->type;
        }

        /**
         * Set status.
         *
         * @param boolean $status
         *
         * @return Banner
         */
        public function setStatus($status)
        {
            $this->status = $status;

            return $this;
        }

        /**
         * Get status.
         *
         * @return boolean
         */
        public function isStatus()
        {
            return $this->status;
        }

        /**
         * Set device.
         *
         * @param integer|null $device
         *
         * @return Banner
         */
        public function setDevice($device = null)
        {
            $this->device = $device;

            return $this;
        }

        /**
         * Get device.
         *
         * @return integer|null
         */
        public function getDevice()
        {
            return $this->device;
        }

        /**
         * Set sort.
         *
         * @param integer|null $sort
         *
         * @return Banner
         */
        public function setSort($sort = null)
        {
            $this->sort = $sort;

            return $this;
        }

        /**
         * Get sort.
         *
         * @return integer|null
         */
        public function getSort()
        {
            return $this->sort;
        }
    }
}