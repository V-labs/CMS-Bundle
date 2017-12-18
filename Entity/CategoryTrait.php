<?php

namespace Vlabs\CmsBundle\Entity;

/**
 * Trait CategoryTrait
 * @package Vlabs\CmsBundle\Entity
 */
trait CategoryTrait
{
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $posts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $children;

    /**
     * @var \Vlabs\CmsBundle\Entity\CategoryInterface
     */
    private $parent;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add post
     *
     * @param \Vlabs\CmsBundle\Entity\PostInterface $post
     *
     * @return $this
     */
    public function addPost(\Vlabs\CmsBundle\Entity\PostInterface $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param \Vlabs\CmsBundle\Entity\PostInterface $post
     */
    public function removePost(\Vlabs\CmsBundle\Entity\PostInterface $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Get publishedPosts
     *
     * @return array
     */
    public function getPublishedPosts()
    {
        $publishedPosts = [];
        /** @var PostInterface $post */
        foreach ($this->posts as $post) {
            if (is_null($post->getPublishedAt())) continue;
            if ($post->getPublishedAt() > new \DateTime()) continue;
            $publishedPosts [] = $post;
        }
        return $publishedPosts;
    }

    /**
     * Add child
     *
     * @param \Vlabs\CmsBundle\Entity\CategoryInterface $child
     *
     * @return $this
     */
    public function addChild(\Vlabs\CmsBundle\Entity\CategoryInterface $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \Vlabs\CmsBundle\Entity\CategoryInterface $child
     */
    public function removeChild(\Vlabs\CmsBundle\Entity\CategoryInterface $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \Vlabs\CmsBundle\Entity\CategoryInterface $parent
     *
     * @return $this
     */
    public function setParent(\Vlabs\CmsBundle\Entity\CategoryInterface $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Vlabs\CmsBundle\Entity\CategoryInterface
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Get root parent
     *
     * @return \Vlabs\CmsBundle\Entity\CategoryInterface
     */
    public function getRootParent()
    {
        if (is_null($this->getParent())) {
            return $this;
        }
        return $this->getParent()->getRootParent();
    }
}