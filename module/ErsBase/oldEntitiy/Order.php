<?php

/**
 * Auto generated by MySQL Workbench Schema Exporter.
 * Version 2.1.6-dev (doctrine2-zf2inputfilterannotation) on 2015-02-02
 * 21:38:10.
 * Goto https://github.com/johmue/mysql-workbench-schema-exporter for more
 * information.
 */

namespace ErsBase\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Entity\Order
 *
 * @ORM\Entity()
 * @ORM\Table(name="`Order`", indexes={
 *      @ORM\Index(name="fk_Order_User1_idx", columns={"Buyer_id"}), 
 *      @ORM\Index(name="fk_Order_PaymentType1_idx", columns={"PaymentType_id"}), 
 *      @ORM\Index(name="fk_Order_Code1_idx", columns={"Code_id"})
 * })
 * @ORM\HasLifecycleCallbacks()
 */
class Order implements InputFilterAwareInterface
{
    /**
     * Instance of InputFilterInterface.
     *
     * @var InputFilter
     */
    private $inputFilter;
    
    /**
     * define length of hashkey
     * 
     * @var length
     */
    private $length = 30;
    
    /**
     * session ids for packages
     * 
     * @var package_id
     */
    private $package_id = 0;
    
    /**
     * session ids for items
     * 
     * @var item_id
     */
    private $item_id = 0;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $Buyer_id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $PaymentType_id;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    protected $matchKey;
    
    /**
     * @ORM\Column(type="string", length=45)
     */
    protected $hashkey;

    /**
     * @ORM\Column(type="string", length=1500, nullable=true)
     */
    protected $invoiceDetail;
    
    /**
     * @ORM\Column(type="string", length=45)
     */
    protected $payment_status = 'unpaid';
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $Code_id;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $order_sum;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $total_sum;
    
    /**
     * @ORM\Column(type="float", nullable=true)
     */
    protected $refund_sum;
    
    /**
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\OneToMany(targetEntity="Match", mappedBy="order")
     * @ORM\JoinColumn(name="id", referencedColumnName="Order_id")
     */
    protected $matches;

    /**
     * @ORM\OneToMany(targetEntity="Package", mappedBy="order")
     * @ORM\JoinColumn(name="id", referencedColumnName="Order_id")
     */
    protected $packages;
    
    /**
     * @ORM\OneToMany(targetEntity="OrderStatus", mappedBy="order", cascade={"persist"})
     * @ORM\JoinColumn(name="id", referencedColumnName="Order_id")
     * @ORM\OrderBy({"created" = "DESC"})
     */
    protected $orderStatus;
    
    /**
     * @ORM\OneToMany(targetEntity="PaymentDetail", mappedBy="order")
     * @ORM\JoinColumn(name="id", referencedColumnName="Order_id")
     */
    protected $paymentDetail;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="orders")
     * @ORM\JoinColumn(name="Buyer_id", referencedColumnName="id")
     */
    protected $buyer;

    /**
     * @ORM\ManyToOne(targetEntity="PaymentType", inversedBy="orders")
     * @ORM\JoinColumn(name="PaymentType_id", referencedColumnName="id")
     */
    protected $paymentType;

    /**
     * @ORM\ManyToOne(targetEntity="Code", inversedBy="orders")
     * @ORM\JoinColumn(name="Code_id", referencedColumnName="id")
     */
    protected $code;

    public function __construct()
    {
        $this->matches = new ArrayCollection();
        $this->packages = new ArrayCollection();
        $this->orderStatus = new ArrayCollection();
        
        $this->genHash();
        
        $package = new Package();
        $unassigned = new User();
        $unassigned->setSessionId(0);
        $package->setSessionId(0);
        $package->setParticipant($unassigned);

        $this->addPackage($package);
    }
    
    /**
     * Generate hashkey
     */
    private function genHash() {
        $alphabet = "0123456789ACDFGHKMNPRUVWXY";
        $memory = '';
        $n = '';
        #srand(mktime()); 
        srand(rand()*mktime());
        for ($i = 0; $i < $this->length; $i++) {
            
            while($n == '' || $memory == $alphabet[$n]) {
                $n = rand(0, strlen($alphabet)-1);
            }
            $memory = $alphabet[$n];
            $code[$i] = $alphabet[$n];
        }
        
        $this->setHashkey(implode($code).$this->genChecksum(implode($code)));
    }
    
    /**
     * generate a two digits Checksum for a Code
     * 
     * @param type $code
     * @return type
     */
    private function genChecksum($code) {
        $chars = str_split($code);
        $nums = array();
        foreach($chars as $char) {
            $nums[] = ord($char);
        }
        $cross_sum = array_sum($nums);
        $checksum = $cross_sum % 100;
        return sprintf('%02d', $checksum);
    }
    
    /**
     * Check if hashkey checksum is valid
     * 
     * @return boolean
     */
    public function checkHashkey() {
        $checksum = substr($this->getHashkey(),$this->length);
        $code = substr($this->getHashkey(),0,$this->length);
        if($this->genChecksum($code) == $checksum) {
            return true;
        } else {
            return false;
        }
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
        #$this->order_sum = $this->getPrice();
        #$this->total_sum = $this->getSum();
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function PreUpdate()
    {
        $this->updated = new \DateTime();
        #$this->order_sum = $this->getPrice();
        #$this->total_sum = $this->getSum();
    }
    
    /**
     * Set id of this object to null if it's cloned
     */
    public function __clone() {
        $this->id = null;
    }

    /**
     * Set the value of id.
     *
     * @param integer $id
     * @return \Entity\Order
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
     * Set the value of Buyer_id.
     *
     * @param integer $Buyer_id
     * @return \Entity\Order
     */
    public function setBuyerId($Buyer_id)
    {
        $this->Buyer_id = $Buyer_id;

        return $this;
    }

    /**
     * Get the value of Buyer_id.
     *
     * @return integer
     */
    public function getBuyerId()
    {
        return $this->Buyer_id;
    }

    /**
     * Set the value of PaymentType_id.
     *
     * @param integer $PaymentType_id
     * @return \Entity\Order
     */
    public function setPaymentTypeId($PaymentType_id)
    {
        $this->PaymentType_id = $PaymentType_id;

        return $this;
    }

    /**
     * Get the value of PaymentType_id.
     *
     * @return integer
     */
    public function getPaymentTypeId()
    {
        return $this->PaymentType_id;
    }

    /**
     * Set the value of matchKey.
     *
     * @param string $matchKey
     * @return \Entity\Order
     */
    public function setMatchKey($matchKey)
    {
        $this->matchKey = $matchKey;

        return $this;
    }

    /**
     * Get the value of matchKey.
     *
     * @return string
     */
    public function getMatchKey()
    {
        return $this->matchKey;
    }

    /**
     * Set the value of hashkey.
     *
     * @param string $hashkey
     * @return \Entity\Order
     */
    public function setHashkey($hashkey)
    {
        $this->hashkey = $hashkey;

        return $this;
    }

    /**
     * Get the value of hashkey.
     *
     * @return string
     */
    public function getHashkey()
    {
        return $this->hashkey;
    }
    
    /**
     * Set the value of invoiceDetail.
     *
     * @param string $invoiceDetail
     * @return \Entity\Order
     */
    public function setInvoiceDetail($invoiceDetail)
    {
        $this->invoiceDetail = $invoiceDetail;

        return $this;
    }

    /**
     * Get the value of invoiceDetail.
     *
     * @return string
     */
    public function getInvoiceDetail()
    {
        return $this->invoiceDetail;
    }
    
    /**
     * Set the value of status.
     *
     * @param string $status
     * @return \Entity\Order
     */
    public function setPaymentStatus($status)
    {
        $this->payment_status = $status;

        return $this;
    }

    /**
     * Get the value of status.
     *
     * @return string
     */
    public function getPaymentStatus()
    {
        return $this->payment_status;
    }
    
    /*public function getPaymentStatus() {
        $payment_status = array(
            'paid',
            'unpaid',
            'refund',
        );
        $ret = null;
        foreach($this->getOrderStatus() as $status) {
            if(in_array($status->getValue(), $payment_status)) {
                if($ret == null || ($ret->getCreated()->getTimestamp() < $status->getCreated()->getTimestamp())) {
                    $ret = $status;
                }
            }
        }
        if($ret == null) {
            $ret = new OrderStatus();
            $ret->setValue('undefined');
        }
        return $ret;
    }*/

    /**
     * Set the value of updated.
     *
     * @param datetime $updated
     * @return \Entity\Order
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
     * @return \Entity\Order
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
    
    public function getSessionId($part) {
        switch($part) {
            case 'package':
                $this->package_id++;
                return $this->package_id;
                #return \count($this->getPackages())+1;
            case 'item':
                $this->item_id++;
                return $this->item_id;
                #return \count($this->getItems())+1;
        }
    }

    /**
     * Set the value of Code_id.
     *
     * @param integer $Code_id
     * @return \Entity\Order
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
     * Set the value of order_sum.
     *
     * @param integer $order_sum
     * @return \Entity\Order
     */
    public function setOrderSum($order_sum)
    {
        $this->order_sum = $order_sum;

        return $this;
    }

    /**
     * Get the value of order_sum.
     *
     * @return integer
     */
    public function getOrderSum()
    {
        if($this->order_sum == 0) {
            $this->order_sum = $this->getPrice();
        }
        return $this->order_sum;
    }
    
    /**
     * Set the value of total_sum.
     *
     * @param integer $total_sum
     * @return \Entity\Order
     */
    public function setTotalSum($total_sum)
    {
        $this->total_sum = $total_sum;

        return $this;
    }

    /**
     * Get the value of total_sum.
     *
     * @return integer
     */
    public function getTotalSum()
    {
        if($this->total_sum == 0) {
            $this->total_sum = $this->getSum();
        }
        return $this->total_sum;
    }
    
    /**
     * Set the value of refund_sum.
     *
     * @param integer $refund_sum
     * @return \Entity\Order
     */
    public function setRefundSum($refund_sum)
    {
        $this->refund_sum = $refund_sum;

        return $this;
    }

    /**
     * Get the value of refund_sum.
     *
     * @return integer
     */
    public function getRefundSum()
    {
        return $this->refund_sum;
    }
    
    /**
     * Add Match entity to collection (one to many).
     *
     * @param \Entity\Match $match
     * @return \Entity\Order
     */
    public function addMatch(Match $match)
    {
        $this->matches[] = $match;

        return $this;
    }

    /**
     * Remove Match entity from collection (one to many).
     *
     * @param \Entity\Match $match
     * @return \Entity\Order
     */
    public function removeMatch(Match $match)
    {
        $this->matches->removeElement($match);

        return $this;
    }

    /**
     * Get Match entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMatches()
    {
        return $this->matches;
    }

    /**
     * Add Package entity to collection (one to many).
     *
     * @param \Entity\Package $package
     * @return \Entity\Order
     */
    public function addPackage(Package $package)
    {
        if(!is_numeric($package->getSessionId())) {
            #$id = \count($this->getPackages())+1;
            #$package->setSessionId($id);
            $package->setSessionId($this->getSessionId('package'));
        }
        $this->packages[] = $package;

        return $this;
    }
    
    /**
     * Remove Package entity from collection (one to many).
     *
     * @param \Entity\Package $package
     * @return \Entity\Order
     */
    public function removePackage(Package $package)
    {
        $this->packages->removeElement($package);

        return $this;
    }

    /**
     * Get Package entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPackages()
    {
        return $this->packages;
    }
    
    /**
     * Get Package by session id.
     * 
     * @return Entity\Package
     */
    public function getPackageBySessionId($id) {
        foreach($this->getPackages() as $package) {
            if($package->getSessionId() == $id) {
                return $package;
            }
        }
        return false;
    }
    
    public function getPackageByItemId($item_id) {
        foreach($this->getPackages() as $package) {
            foreach($package->getItems() as $item) {
                if($item->getSessionId() == $item_id) {
                    return $package;
                }
            }
        }
        return false;
    }
    
    /**
     * Get Package by participant session id.
     * 
     * @return Entity\Package
     */
    public function getPackageByParticipantSessionId($id) {
        foreach($this->getPackages() as $package) {
            if($package->getParticipant()->getSessionId() == $id) {
                return $package;
            }
        }
        return false;
    }
    
    /**
     * Get Package by participant email.
     * 
     * @return Entity\Package
     */
    public function getPackageByParticipantEmail($email) {
        foreach($this->getPackages() as $package) {
            $participant = $package->getParticipant();
            if($participant->getEmail() == $email) {
                return $package;
            }
        }
        return false;
    }
    
    /**
     * Add Item to the according package with the correct Buyer_id
     * 
     * @param \Entity\Item $item
     * @param integer $Participant_id
     * @return \Entity\Order
     */
    public function addItem(Item $item, $Participant_id)
    {
        $package = $this->getPackageByParticipantSessionId($Participant_id);
        if($package) {
            $package->setOrder($this);
            $package->addItem($item);
        }
        
        return $this;
    }
    
    /**
     * get Item by participant_id and item_id
     * 
     * @param integer $participant_id
     * @param integer $item_id
     * 
     * @return \Entity\Item
     * @return false
     */
    public function getItem($item_id) {
    #public function getItem($participant_id, $item_id) {
        foreach($this->getPackages() as $package) {
            foreach($package->getItems() as $item) {
                if($item->getSessionId() == $item_id) {
                    return $item;
                }
            }
        }
        return false;
    }
    
    public function findPackageByItem(Item $item) {
        foreach($this->getPackages() as $package) {
            if($package->existItem($item)) {
                return $package;
            }
        }
        return false;
    }
    
    /**
     * get Items of this order
     * 
     * @return ArrayCollection
     */
    public function getItems() {
        $items = new ArrayCollection();
        foreach($this->getPackages() as $package) {
            $items = new ArrayCollection(array_merge($items->toArray(), $package->getItems()->toArray()));
        }
        
        return $items;
    }
    
    public function getItemsByStatus($status) {
        $items = new ArrayCollection();
        foreach($this->getItems() as $item) {
            if($item->getStatus() == $status) {
                $items[] = $item;
            }
        }
        return $items;
    }
    
    /**
     * remove Item by participant_id and item_id
     * 
     * @param integer $participant_id
     * @param integer $item_id
     * 
     * @return \Entity\Order
     */
    public function removeItem($item_id) {
    #public function removeItem($package_id, $item_id) {
        $package = $this->getPackageByItemId($item_id);
        #$package = $this->getPackageBySessionId($package_id);
        if($package) {
            $package->removeItemBySessionId($item_id);    
        }
        
        return $this;
    }
    
    /**
     * Add Package entity to collection (one to many).
     *
     * @param \Entity\OrderStatus $orderStatus
     * @return \Entity\Order
     */
    public function addOrderStatus(OrderStatus $orderStatus)
    {
        $this->orderStatus[] = $orderStatus;

        return $this;
    }
    
    /**
     * Remove OrderStatus entity from collection (one to many).
     *
     * @param \Entity\OrderStatus $orderStatus
     * @return \Entity\Order
     */
    public function removeOrderStatus(OrderStatus $orderStatus)
    {
        $this->orderStatus->removeElement($orderStatus);

        return $this;
    }

    /**
     * Get OrderStatus entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }
    
    /**
     * get the last set OrderStatus
     * 
     * @return Entity\OrderStatus
     */
    public function getLastOrderStatus() {
        return array_pop($this->orderStatus);
    }
    
    /**
     * find a status for this order
     * 
     * @param type $value
     * @return OrderStatus
     */
    public function findOrderStatus($value) {
        foreach($this->getOrderStatus() as $status) {
            if($status->getValue() == $value) {
                return $status;
            }
        }
        return new OrderStatus();
    }
    
    /**
     * check whether this order has this status or not
     * 
     * @param type $value
     * @return boolean
     */
    public function hasOrderStatus($value) {
        foreach($this->getOrderStatus() as $status) {
            if($status->getValue() == $value) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Add PaymentDetail entity to collection (one to many).
     *
     * @param \Entity\PaymentDetail $paymentDetail
     * @return \Entity\Order
     */
    public function addPaymentDetail(PaymentDetail $paymentDetail)
    {
        /*if(!is_numeric($paymentDetail->getSessionId())) {
            $id = \count($this->getPaymentDetails())+1;
            $paymentDetail->setSessionId($id);
        }*/
        $this->paymentDetails[] = $paymentDetail;

        return $this;
    }
    
    /**
     * Remove PaymentDetail entity from collection (one to many).
     *
     * @param \Entity\PaymentDetail $paymentDetail
     * @return \Entity\Order
     */
    public function removePaymentDetail(PaymentDetail $paymentDetail)
    {
        $this->paymentDetails->removeElement($paymentDetail);

        return $this;
    }

    /**
     * Get PaymentDetail entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPaymentDetails()
    {
        return $this->paymentDetails;
    }

    /**
     * Get Participants of Packages
     * 
     * @return array
     */
    public function getParticipants() {
        $participants = array();
        foreach($this->getPackages() as $package) {
            if($package->getParticipant()->getFirstname() != '' && $package->getParticipant()->getSurname() != '') {
                #$id = $package->getParticipant()->getSessionId();
                $participants[] = $package->getParticipant();
            }
        }
        
        return $participants;
    }
    
    /**
     * Get Participant by session_id
     * 
     * @return Entity\User
     * @return false
     */
    public function getParticipantBySessionId($id) {
        foreach($this->getParticipants() as $participant) {
            if($participant->getSessionId() == $id) {
                return $participant;
            }
        }
        return false;
    }
    
    /**
     * Get Participant by email
     * 
     * @return Entity\User
     * @return false
     */
    public function getParticipantByEmail($email) {
        foreach($this->getParticipants() as $participant) {
            if($participant->getEmail() == $email) {
                return $participant;
            }
        }
        return false;
    }
    
    /**
     * Get Participant by id
     * 
     * @return Entity\User
     * @return false
     */
    public function getParticipantById($id) {
        foreach($this->getParticipants() as $participant) {
            if($participant->getId() == $id) {
                return $participant;
            }
        }
        return false;
    }
    
    /**
     * Get Participant by item_id
     * 
     * @param type $item_id
     * @return boolean
     */
    public function getParticipantByItemId($item_id) {
        foreach($this->getPackages() as $package) {
            foreach($package->getItems() as $item) {
                if($item->getSessionId() == $item_id) {
                    return $package->getParticipant();
                }
            }
            /*if($package->getParticipant()->getSessionId() == $id) {
                return $package->getParticipant();
            }*/
        }
        return false;
    }
    
    /**
     * Set Participant by session_id
     * 
     * @return boolean
     */
    public function setParticipantBySessionId(User $user, $id) {
        foreach($this->getPackages() as $package) {
            if($package->getParticipant()->getSessionId() == $id) {
                $package->setParticipant($user);
                return true;
            }
        }
        return false;
    }
    
    /**
     * Add Participant (add new Package and set participant)
     * 
     * @param \Entity\User $participant
     * @return \Entity\Order
     */
    
    public function addParticipant(User $participant) {
        $package = new Package();
        $sessionId = $this->getSessionId('package');
        $participant->setSessionId($sessionId);
        $package->setParticipant($participant);
        $package->setSessionId($sessionId);
        
        $this->packages[] = $package;
        
        return $this;
    }
    
    /**
     * Remove Package entity by participant
     *
     * @param int
     * @return \Entity\Order
     */
    public function removeParticipantBySessionId($id)
    {
        foreach($this->getPackages() as $package) {
            if($package->getParticipant()->getSessionId() == $id) {
                $this->packages->removeElement($package);
            }
        }

        return $this;
    }
    
    /**
     * Set Buyer entity (many to one).
     *
     * @param \Entity\User $buyer
     * @return \Entity\Order
     */
    public function setBuyer(User $buyer = null)
    {
        $this->buyer = $buyer;

        return $this;
    }

    /**
     * Get Buyer entity (many to one).
     *
     * @return \Entity\User
     */
    public function getBuyer()
    {
        return $this->buyer;
    }

    /**
     * Set PaymentType entity (many to one).
     *
     * @param \Entity\PaymentType $paymentType
     * @return \Entity\Order
     */
    public function setPaymentType(PaymentType $paymentType = null)
    {
        $this->paymentType = $paymentType;
        if($paymentType) {
            $this->setPaymentTypeId($paymentType->getId());
        }

        return $this;
    }

    /**
     * Get PaymentType entity (many to one).
     *
     * @return \Entity\PaymentType
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * Set Code entity (many to one).
     *
     * @param \Entity\Code $code
     * @return \Entity\Order
     */
    public function setCode(Code $code = null)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get Code entity (many to one).
     *
     * @return \Entity\Code
     */
    public function getCode()
    {
        return $this->code;
    }
    
    /**
     * Get price of the complete order (excluding price fees)
     * 
     * @return float
     */
    public function getPrice($status=null) {
        $price = 0;
        foreach($this->getPackages() as $package) {
            foreach($package->getItems() as $item) {
                if($status == null) {
                    if($item->getStatus() != 'cancelled' && $item->getStatus() != 'transferred') {
                        $price += $item->getPrice();
                    }
                } else {
                    if($item->getStatus() != $status) {
                        $price += $item->getPrice();
                    }
                }
            }
        }
        
        return $price;
    }
    
    /**
     * Get total sum of the order (including price fees)
     * 
     * @return float
     */
    public function getSum() {
        $sum = $this->getPrice();
        $sum += $this->getPaymentType()->calcFee($sum);
        return $sum;
    }
    
    public function getStatementAmount() {
        $statement_amount = 0;
        foreach($this->getMatches() as $match) {
            $statement = $match->getBankStatement();
            $statement_amount += $statement->getAmount()->getValue();
        }
        return $statement_amount;
    }
    
    /**
     * get payment fees for this order
     * 
     * @return float
     */
    public function getPaymentFees() {
        return $this->getPaymentType()->calcFee($this->getPrice());
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
                'name' => 'Buyer_id',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'PaymentType_id',
                'required' => true,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'matchKey',
                'required' => false,
                'filters' => array(),
                'validators' => array(),
            ),
            array(
                'name' => 'invoiceDetail',
                'required' => false,
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
            array(
                'name' => 'Code_id',
                'required' => true,
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
    public function exchangeArray(array $data = array()) {
        $this->populate($data);
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
        $dataFields = array('id', 'Code_id', 'Buyer_id', 'buyer', 'PaymentType_id', 'matchKey', 'hashkey', 'invoiceDetail', 'updated', 'created');
        $relationFields = array('user', 'paymentType', 'code');
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
        return array(
            'id', 
            'Code_id',
            'package_id', 
            'item_id', 
            'Buyer_id',
            'buyer',
            'PaymentType_id', 
            'matchKey', 
            'hashkey', 
            'invoiceDetail', 
            'packages', 
            'updated', 
            'created', 
        );
    }
}