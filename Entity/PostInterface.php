<?php

namespace Vlabs\CmsBundle\Entity;

interface PostInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set publishedAt
     *
     * @param \DateTime $publishedAt
     *
     * @return $this
     */
    public function setPublishedAt($publishedAt);

    /**
     * Get publishedAt
     *
     * @return \DateTime
     */
    public function getPublishedAt();

    /**
     * Set unpublishedAt
     *
     * @param \DateTime $unpublishedAt
     *
     * @return $this
     */
    public function setUnpublishedAt($unpublishedAt);

    /**
     * Get unpublishedAt
     *
     * @return \DateTime
     */
    public function getUnpublishedAt();

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return $this
     */
    public function setPosition($position);

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition();

    /**
     * Set title
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title);

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set content
     *
     * @param string $content
     *
     * @return $this
     */
    public function setContent($content);

    /**
     * Get content
     *
     * @return string
     */
    public function getContent();

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return $this
     */
    public function setSlug($slug);

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug();
}
