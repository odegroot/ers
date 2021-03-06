<?php

/**
 * Auto generated by MySQL Workbench Schema Exporter.
 * Version 2.1.6-dev (doctrine2-mappedsuperclass) on 2016-01-24 00:44:13.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace ErsBase\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ErsBase\Entity\Base\ProductVariant
 *
 * @ORM\MappedSuperclass
 * @ORM\Table(name="product_variant", indexes={@ORM\Index(name="fk_ProductVariant_Product1_idx", columns={"Product_id"})})
 * @ORM\HasLifecycleCallbacks
 */
class ProductVariant
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $Product_id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $statsname;

    /**
     * @ORM\Column(name="`position`", type="integer", nullable=true)
     */
    protected $position;

    /**
     * @ORM\Column(name="`name`", type="string", length=45, nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(name="`type`", type="string", nullable=true)
     */
    protected $type;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $preselection;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\OneToMany(targetEntity="ItemVariant", mappedBy="productVariant")
     * @ORM\JoinColumn(name="id", referencedColumnName="product_variant_id")
     */
    protected $itemVariants;

    /**
     * @ORM\OneToMany(targetEntity="ProductVariantValue", mappedBy="productVariant", cascade={"persist", "merge"})
     * @ORM\JoinColumn(name="id", referencedColumnName="ProductVariant_id")
     */
    protected $productVariantValues;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="productVariants", cascade={"persist", "merge"})
     * @ORM\JoinColumn(name="Product_id", referencedColumnName="id")
     */
    protected $product;

    public function __construct()
    {
        $this->itemVariants = new ArrayCollection();
        $this->productVariantValues = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     */
    public function PrePersist()
    {
        if(!isset($this->created)) {
            $this->created = new \DateTime();
        }
        $this->updated = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function PreUpdate()
    {
        $this->updated = new \DateTime();
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \ErsBase\Entity\Base\ProductVariant
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of Product_id.
     *
     * @param integer $Product_id
     * @return \ErsBase\Entity\Base\ProductVariant
     */
    public function setProductId($Product_id)
    {
        $this->Product_id = $Product_id;

        return $this;
    }

    /**
     * Get the value of Product_id.
     *
     * @return integer
     */
    public function getProductId()
    {
        return $this->Product_id;
    }

    /**
     * Set the value of statsname.
     *
     * @param string $statsname
     * @return \ErsBase\Entity\Base\ProductVariant
     */
    public function setStatsname($statsname)
    {
        $this->statsname = $statsname;

        return $this;
    }

    /**
     * Get the value of statsname.
     *
     * @return string
     */
    public function getStatsname()
    {
        return $this->statsname;
    }

    /**
     * Set the value of position.
     *
     * @param integer $position
     * @return \ErsBase\Entity\Base\ProductVariant
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get the value of position.
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set the value of name.
     *
     * @param string $name
     * @return \ErsBase\Entity\Base\ProductVariant
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of type.
     *
     * @param string $type
     * @return \ErsBase\Entity\Base\ProductVariant
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of preselection.
     *
     * @param string $preselection
     * @return \ErsBase\Entity\Base\ProductVariant
     */
    public function setPreselection($preselection)
    {
        $this->preselection = $preselection;

        return $this;
    }

    /**
     * Get the value of preselection.
     *
     * @return string
     */
    public function getPreselection()
    {
        return $this->preselection;
    }

    /**
     * Set the value of updated.
     *
     * @param \DateTime $updated
     * @return \ErsBase\Entity\Base\ProductVariant
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get the value of updated.
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set the value of created.
     *
     * @param \DateTime $created
     * @return \ErsBase\Entity\Base\ProductVariant
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get the value of created.
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Add ItemVariant entity to collection (one to many).
     *
     * @param \ErsBase\Entity\Base\ItemVariant $itemVariant
     * @return \ErsBase\Entity\Base\ProductVariant
     */
    public function addItemVariant(ItemVariant $itemVariant)
    {
        $this->itemVariants[] = $itemVariant;

        return $this;
    }

    /**
     * Remove ItemVariant entity from collection (one to many).
     *
     * @param \ErsBase\Entity\Base\ItemVariant $itemVariant
     * @return \ErsBase\Entity\Base\ProductVariant
     */
    public function removeItemVariant(ItemVariant $itemVariant)
    {
        $this->itemVariants->removeElement($itemVariant);

        return $this;
    }

    /**
     * Get ItemVariant entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItemVariants()
    {
        return $this->itemVariants;
    }

    /**
     * Add ProductVariantValue entity to collection (one to many).
     *
     * @param \ErsBase\Entity\Base\ProductVariantValue $productVariantValue
     * @return \ErsBase\Entity\Base\ProductVariant
     */
    public function addProductVariantValue(ProductVariantValue $productVariantValue)
    {
        $this->productVariantValues[] = $productVariantValue;

        return $this;
    }

    /**
     * Remove ProductVariantValue entity from collection (one to many).
     *
     * @param \ErsBase\Entity\Base\ProductVariantValue $productVariantValue
     * @return \ErsBase\Entity\Base\ProductVariant
     */
    public function removeProductVariantValue(ProductVariantValue $productVariantValue)
    {
        $this->productVariantValues->removeElement($productVariantValue);

        return $this;
    }

    /**
     * Get ProductVariantValue entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductVariantValues()
    {
        return $this->productVariantValues;
    }

    /**
     * Set Product entity (many to one).
     *
     * @param \ErsBase\Entity\Base\Product $product
     * @return \ErsBase\Entity\Base\ProductVariant
     */
    public function setProduct(Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get Product entity (many to one).
     *
     * @return \ErsBase\Entity\Base\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Populate entity with the given data.
     * The set* method will be used to set the data.
     *
     * @param array $data
     * @return boolean
     */
    public function populate(array $data = array())
    {
        foreach ($data as $field => $value) {
            $setter = sprintf('set%s', ucfirst(
                str_replace(' ', '', ucwords(str_replace('_', ' ', $field)))
            ));
            if (method_exists($this, $setter)) {
                $this->{$setter}($value);
            }
        }

        return true;
    }

    /**
     * Return a array with all fields and data.
     * Default the relations will be ignored.
     * 
     * @param array $fields
     * @return array
     */
    public function getArrayCopy(array $fields = array())
    {
        $dataFields = array('id', 'Product_id', 'statsname', 'position', 'name', 'type', 'preselection', 'updated', 'created');
        $relationFields = array('product');
        $copiedFields = array();
        foreach ($relationFields as $relationField) {
            $map = null;
            if (array_key_exists($relationField, $fields)) {
                $map = $fields[$relationField];
                $fields[] = $relationField;
                unset($fields[$relationField]);
            }
            if (!in_array($relationField, $fields)) {
                continue;
            }
            $getter = sprintf('get%s', ucfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $relationField)))));
            $relationEntity = $this->{$getter}();
            $copiedFields[$relationField] = (!is_null($map))
                ? $relationEntity->getArrayCopy($map)
                : $relationEntity->getArrayCopy();
            $fields = array_diff($fields, array($relationField));
        }
        foreach ($dataFields as $dataField) {
            if (!in_array($dataField, $fields) && !empty($fields)) {
                continue;
            }
            $getter = sprintf('get%s', ucfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $dataField)))));
            $copiedFields[$dataField] = $this->{$getter}();
        }

        return $copiedFields;
    }

    public function __sleep()
    {
        return array('id', 'Product_id', 'statsname', 'position', 'name', 'type', 'preselection', 'updated', 'created');
    }
}