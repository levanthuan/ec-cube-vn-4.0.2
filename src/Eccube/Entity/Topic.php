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

if (!class_exists('\Eccube\Entity\Topic')) {
    /**
     * Topic
     *
     * @ORM\Table(name="dtb_topic")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\TopicRepository")
     */
    class Topic extends \Eccube\Entity\AbstractEntity
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
         * @ORM\Column(name="image", type="string", length=255, nullable=true)
         */
        private $image;

        /**
         * @var string
         *
         * @ORM\Column(name="url", type="string", length=255, nullable=true)
         */
        private $url;

        /**
         * @var string
         *
         * @ORM\Column(name="tag", type="string", length=255, nullable=true)
         */
        private $tag;

        /**
         * @var string|null
         *
         * @ORM\Column(name="content", type="string", length=4000, nullable=true)
         */
        private $content;

        /**
         * @var integer
         *
         * @ORM\Column(name="sort", type="integer", length=10, nullable=true)
         */
        private $sort;

        /**
         * @var boolean
         *
         * @ORM\Column(name="status", type="boolean", options={"default":false})
         */
        private $status = false;

        /**
         * Get id.
         *
         * @return int
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
         * @return Topic
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
         * Set image.
         *
         * @param string $image
         *
         * @return Topic
         */
        public function setImage($image)
        {
            $this->image = $image;

            return $this;
        }

        /**
         * Get image.
         *
         * @return string
         */
        public function getImage()
        {
            return $this->image;
        }

        /**
         * Set url.
         *
         * @param string $url
         *
         * @return Topic
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
         * Set tag.
         *
         * @param string $tag
         *
         * @return Topic
         */
        public function setTag($tag)
        {
            $this->tag = $tag;

            return $this;
        }

        /**
         * Get tag.
         *
         * @return string
         */
        public function getTag()
        {
            return $this->tag;
        }

        /**
         * Set content.
         *
         * @param string $content
         *
         * @return Topic
         */
        public function setContent($content)
        {
            $this->content = $content;

            return $this;
        }

        /**
         * Get content.
         *
         * @return string
         */
        public function getContent()
        {
            return $this->content;
        }

        /**
         * Set sort.
         *
         * @param integer|null $sort
         *
         * @return Topic
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

        /**
         * Set status.
         *
         * @param boolean $status
         *
         * @return Topic
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
    }
}