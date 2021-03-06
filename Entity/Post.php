<?php

namespace Vlabs\CmsBundle\Entity;

use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Class Post
 * @package Vlabs\CmsBundle\Entity
 */
abstract class Post implements PostInterface
{
    use SoftDeleteableEntity;

    use TimestampableEntity;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \DateTime
     */
    protected $publishedAt;

    /**
     * @var \DateTime
     */
    protected $unpublishedAt;

    /**
     * @var integer
     */
    protected $position;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $slug;

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
     * Set publishedAt
     *
     * @param \DateTime $publishedAt
     *
     * @return $this
     */
    public function setPublishedAt($publishedAt)
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Get publishedAt
     *
     * @return \DateTime
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * Set unpublishedAt
     *
     * @param \DateTime $unpublishedAt
     *
     * @return $this
     */
    public function setUnpublishedAt($unpublishedAt)
    {
        $this->unpublishedAt = $unpublishedAt;

        return $this;
    }

    /**
     * Get unpublishedAt
     *
     * @return \DateTime
     */
    public function getUnpublishedAt()
    {
        return $this->unpublishedAt;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return $this
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}