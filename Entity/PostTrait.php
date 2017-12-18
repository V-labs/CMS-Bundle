<?php

namespace Vlabs\CmsBundle\Entity;

/**
 * Trait PostTrait
 * @package Vlabs\CmsBundle\Entity
 */
trait PostTrait
{
    /**
     * @var \Vlabs\CmsBundle\Entity\CategoryInterface
     */
    private $category;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $relatedPosts;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tags;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->relatedPosts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set category
     *
     * @param \Vlabs\CmsBundle\Entity\CategoryInterface $category
     *
     * @return $this
     */
    public function setCategory(\Vlabs\CmsBundle\Entity\CategoryInterface $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Vlabs\CmsBundle\Entity\CategoryInterface
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Get root category
     *
     * @return \Vlabs\CmsBundle\Entity\CategoryInterface
     */
    public function getRootCategory()
    {
        return $this->category->getRootParent();
    }

    /**
     * Add relatedPost
     *
     * @param \Vlabs\CmsBundle\Entity\PostInterface $relatedPost
     *
     * @return $this
     */
    public function addRelatedPost(\Vlabs\CmsBundle\Entity\PostInterface $relatedPost)
    {
        $this->relatedPosts[] = $relatedPost;

        return $this;
    }

    /**
     * Remove relatedPost
     *
     * @param \Vlabs\CmsBundle\Entity\PostInterface $relatedPost
     */
    public function removeRelatedPost(\Vlabs\CmsBundle\Entity\PostInterface $relatedPost)
    {
        $this->relatedPosts->removeElement($relatedPost);
    }

    /**
     * Get relatedPosts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRelatedPosts()
    {
        return $this->relatedPosts;
    }

    /**
     * Add tag
     *
     * @param \Vlabs\CmsBundle\Entity\TagInterface $tag
     *
     * @return $this
     */
    public function addTag(\Vlabs\CmsBundle\Entity\TagInterface $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \Vlabs\CmsBundle\Entity\TagInterface $tag
     */
    public function removeTag(\Vlabs\CmsBundle\Entity\TagInterface $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }
}