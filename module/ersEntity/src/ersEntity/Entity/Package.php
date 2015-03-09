<?php

/**
 * Auto generated by MySQL Workbench Schema Exporter.
 * Version 2.1.6-dev (doctrine2-zf2inputfilterannotation) on 2015-02-02
 * 21:38:10.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace ersEntity\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Entity\Package
 *
 * @ORM\Entity()
 * @ORM\Table(name="Package", indexes={
 *   @ORM\Index(name="fk_Package_Order1_idx", columns={"Order_id"}), 
 *   @ORM\Index(name="fk_Package_User1_idx", columns={"Participant_id"}), 
 *   @ORM\Index(name="fk_Package_Code1_idx", columns={"Code_id"})
 * })
 * @ORM\HasLifecycleCallbacks()
 */
class Package implements InputFilterAwareInterface
{
    /**
     * Instance of InputFilterInterface.
     *
     * @var InputFilter
     */
    private $inputFilter;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     *
     * @var int
     * variable to keep an id which is only used while this entity is hold in 
     * a session container.
     */
    protected $session_id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $Order_id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $Participant_id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $Code_id;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\OneToMany(targetEntity="Item", mappedBy="package",cascade={"persist"})
     * @ORM\JoinColumn(name="id", referencedColumnName="Package_id")
     */
    protected $items;

    /**
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="packages")
     * @ORM\JoinColumn(name="Order_id", referencedColumnName="id")
     */
    protected $order;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="packages")
     * @ORM\JoinColumn(name="Participant_id", referencedColumnName="id")
     */
    protected $participant;

    /**
     * @ORM\OneToOne(targetEntity="Code", inversedBy="package",cascade={"persist"})
     * @ORM\JoinColumn(name="Code_id", referencedColumnName="id")
     */
    protected $code;

    public function __construct()
    {
        $this->session_id = null;
        $this->items = new ArrayCollection();
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
     * Set id of this object to null if it's cloned
     */
    public function __clone() {
        $this->id = null;
    }
    
    /**
     * implement __toString for debuggin
     */
    public function __toString() {
        return $this->getParticipant()->__toString();
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \Entity\Package
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
     * Get session_id
     * 
     * @return int
     */
    public function getSessionId()
    {
        return $this->session_id;
    }
    
    /**
     * Set session_id.
     *
     * @param int $id
     *
     * @return void
     */
    public function setSessionId($id) {
        $this->session_id = $id;
    }

    /**
     * Set the value of Order_id.
     *
     * @param integer $Order_id
     * @return \Entity\Package
     */
    public function setOrderId($Order_id)
    {
        $this->Order_id = $Order_id;

        return $this;
    }

    /**
     * Get the value of Order_id.
     *
     * @return integer
     */
    public function getOrderId()
    {
        return $this->Order_id;
    }

    /**
     * Set the value of Participant_id.
     *
     * @param integer $Participant_id
     * @return \Entity\Package
     */
    public function setParticipantId($Participant_id)
    {
        $this->Participant_id = $Participant_id;

        return $this;
    }

    /**
     * Get the value of Participant_id.
     *
     * @return integer
     */
    public function getParticipantId()
    {
        return $this->Participant_id;
    }

    /**
     * Set the value of Code_id.
     *
     * @param integer $Code_id
     * @return \Entity\Package
     */
    public function setCodeId($Code_id)
    {
        $this->Code_id = $Code_id;

        return $this;
    }

    /**
     * Get the value of Code_id.
     *
     * @return integer
     */
    public function getCodeId()
    {
        return $this->Code_id;
    }

    /**
     * Set the value of updated.
     *
     * @param datetime $updated
     * @return \Entity\Package
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get the value of updated.
     *
     * @return datetime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set the value of created.
     *
     * @param datetime $created
     * @return \Entity\Package
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get the value of created.
     *
     * @return datetime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Add Item entity to collection (one to many).
     *
     * @param \Entity\Item $item
     * @return \Entity\Package
     */
    public function addItem(Item $item)
    {
        if(!is_numeric($item->getSessionId())) {
            $id = \count($this->getItems())+1;
            $item->setSessionId($id);
        }
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove Item entity from collection (one to many).
     *
     * @param \Entity\Item $item
     * @return \Entity\Package
     */
    public function removeItem(Item $item)
    {
        $this->items->removeElement($item);

        return $this;
    }
    
    /**
     * Remove Item entity by session id
     * 
     * @param type $id
     */
    public function removeItemBySessionId($id) {
        $item = $this->getItemBySessionId($id);
        return $this->removeItem($item);
    }

    /**
     * Get Item entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }
    
    /**
     * Get Item entity by array id.
     *
     * @return \Entity\Item
     */
    public function getItemById($id) {
        if(isset($this->items[$id])) {
            return $this->items[$id];
        }
        return false;
    }
    
    /**
     * Get Item entity by session id
     * 
     * @return \Entity\Item
     * @return false
     */
    public function getItemBySessionId($id) {
        foreach($this->getItems() as $item) {
            if($item->getSessionId() == $id) {
                return $item;
            }
        }
        return false;
    }
    
    /**
     * Check if item exists
     * 
     * @param \ersEntity\Entity\Item $item
     * @return boolean
     */
    public function existItem(Item $item) {
        $key = array_search($item, $this->getItems()->toArray(), true);

        if ($key !== false) {
            return true;
        }
        return false;
    }
    
    /**
     * Check if this package already contains a personalized ticket
     * 
     * @return boolean
     */
    public function hasPersonalizedItem() {
        foreach($this->getItems() as $item) {
            if($item->getPersonalized()) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Set Order entity (many to one).
     *
     * @param \Entity\Order $order
     * @return \Entity\Package
     */
    public function setOrder(Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get Order entity (many to one).
     *
     * @return \Entity\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set Participant entity (many to one).
     *
     * @param \Entity\User $participant
     * @return \Entity\Package
     */
    public function setParticipant(User $participant = null)
    {
        $this->participant = $participant;

        return $this;
    }

    /**
     * Get Participant entity (many to one).
     *
     * @return \Entity\User
     */
    public function getParticipant()
    {
        return $this->participant;
    }

    /**
     * Set Code entity (one to one).
     *
     * @param \Entity\Code $code
     * @return \Entity\Package
     */
    public function setCode(Code $code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get Code entity (one to one).
     *
     * @return \Entity\Code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Not used, Only defined to be compatible with InputFilterAwareInterface.
     * 
     * @param \Zend\InputFilter\InputFilterInterface $inputFilter
     * @throws \Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used.");
    }

    /**
     * Return a for this entity configured input filter instance.
     *
     * @return InputFilterInterface
     */
    public function getInputFilter()
    {
        if ($this->inputFilter instanceof InputFilterInterface) {
            return $this->inputFilter;
        }
        $factory = new InputFactory();
        $filters = array(
            array(
                'name' => 'id',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'Order_id',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'Participant_id',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'Code_id',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'updated',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'created',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
        );
        $this->inputFilter = $factory->createInputFilter($filters);

        return $this->inputFilter;
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
        $dataFields = array('id', 'session_id', 'Order_id', 'Participant_id', 'Code_id', 'updated', 'created');
        $relationFields = array('order', 'user', 'code');
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
        return array('id', 'session_id', 'Order_id', 'Participant_id', 'Code_id', 'participant', 'items', 'updated', 'created');
    }
}