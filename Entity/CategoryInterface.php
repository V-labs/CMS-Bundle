<?php

namespace Vlabs\CmsBundle\Entity;

/**
 * Interface CategoryInterface
 * @package Vlabs\CmsBundle\Entity
 */
interface CategoryInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set name
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name);

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Set section
     *
     * @param string $section
     *
     * @return $this
     */
    public function setSection($section);

    /**
     * Get section
     *
     * @return string
     */
    public function getSection();

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
}