<?php

namespace Pim\Bundle\CatalogBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Pim\Bundle\CatalogBundle\Entity\AssociationType;

/**
 * Abstract association entity
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2014 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 * @ExclusionPolicy("all")
 */
abstract class AbstractAssociation implements AssociationInterface
{
    /** @var int|string */
    protected $id;

    /** @var AssociationType */
    protected $associationType;

    /** @var ProductInterface */
    protected $owner;

    /** @var ProductInterface[] */
    protected $products;

    /** @var GroupInterface[] */
    protected $groups;

    /** @var array */
    protected $groupIds = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->groups = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setAssociationType(AssociationType $associationType)
    {
        $this->associationType = $associationType;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAssociationType()
    {
        return $this->associationType;
    }

    /**
     * {@inheritdoc}
     */
    public function setOwner(ProductInterface $owner)
    {
        if (!$this->owner) {
            $this->owner = $owner;
            $owner->addAssociation($this);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * {@inheritdoc}
     */
    public function setProducts($products)
    {
        $this->products = $products;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * {@inheritdoc}
     */
    public function addProduct(ProductInterface $product)
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasProduct(ProductInterface $product)
    {
        return $this->products->contains($product);
    }

    /**
     * {@inheritdoc}
     */
    public function removeProduct(ProductInterface $product)
    {
        $this->products->removeElement($product);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * {@inheritdoc}
     */
    public function addGroup(GroupInterface $group)
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeGroup(GroupInterface $group)
    {
        $this->groups->removeElement($group);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getReference()
    {
        return $this->owner ? $this->owner->getIdentifier() . '.' . $this->associationType->getCode() : null;
    }
}
