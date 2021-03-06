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
 * ErsBase\Entity\Base\Order
 *
 * @ORM\MappedSuperclass
 * @ORM\Table(name="`order`", indexes={@ORM\Index(name="fk_Order_User1_idx", columns={"buyer_id"}), @ORM\Index(name="fk_order_payment_type1_idx", columns={"payment_type_id"}), @ORM\Index(name="fk_order_code1_idx", columns={"code_id"}), @ORM\Index(name="fk_order_status1_idx", columns={"status_id"})})
 * @ORM\HasLifecycleCallbacks
 */
class Order
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
    protected $status_id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $code_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $buyer_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $payment_type_id;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    protected $hashkey;

    /**
     * @ORM\Column(type="string", length=1500, nullable=true)
     */
    protected $invoice_detail;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    protected $payment_status;

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
     * @ORM\OneToMany(targetEntity="Log", mappedBy="order")
     * @ORM\JoinColumn(name="id", referencedColumnName="order_id")
     */
    protected $logs;

    /**
     * @ORM\OneToMany(targetEntity="Match", mappedBy="order")
     * @ORM\JoinColumn(name="id", referencedColumnName="order_id")
     */
    protected $matches;

    /**
     * @ORM\OneToMany(targetEntity="Package", mappedBy="order", cascade={"persist", "merge", "remove"})
     * @ORM\JoinColumn(name="id", referencedColumnName="order_id", onDelete="CASCADE")
     */
    protected $packages;

    /**
     * @ORM\OneToMany(targetEntity="PaymentDetail", mappedBy="order")
     * @ORM\JoinColumn(name="id", referencedColumnName="order_id")
     */
    protected $paymentDetails;

    /**
     * @ORM\ManyToOne(targetEntity="Status", inversedBy="orders", cascade={"persist", "merge"})
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    protected $status;

    /**
     * @ORM\ManyToOne(targetEntity="Code", inversedBy="orders", cascade={"persist", "merge"})
     * @ORM\JoinColumn(name="code_id", referencedColumnName="id")
     */
    protected $code;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="orders")
     * @ORM\JoinColumn(name="buyer_id", referencedColumnName="id", nullable=true)
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="PaymentType", inversedBy="orders")
     * @ORM\JoinColumn(name="payment_type_id", referencedColumnName="id", nullable=true)
     */
    protected $paymentType;

    public function __construct()
    {
        $this->logs = new ArrayCollection();
        $this->matches = new ArrayCollection();
        $this->packages = new ArrayCollection();
        $this->paymentDetails = new ArrayCollection();
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
     * @return \ErsBase\Entity\Base\Order
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
     * Set the value of status_id.
     *
     * @param integer $status_id
     * @return \ErsBase\Entity\Base\Order
     */
    public function setStatusId($status_id)
    {
        $this->status_id = $status_id;

        return $this;
    }

    /**
     * Get the value of status_id.
     *
     * @return integer
     */
    public function getStatusId()
    {
        return $this->status_id;
    }

    /**
     * Set the value of code_id.
     *
     * @param integer $code_id
     * @return \ErsBase\Entity\Base\Order
     */
    public function setCodeId($code_id)
    {
        $this->code_id = $code_id;

        return $this;
    }

    /**
     * Get the value of code_id.
     *
     * @return integer
     */
    public function getCodeId()
    {
        return $this->code_id;
    }

    /**
     * Set the value of buyer_id.
     *
     * @param integer $buyer_id
     * @return \ErsBase\Entity\Base\Order
     */
    public function setBuyerId($buyer_id)
    {
        $this->buyer_id = $buyer_id;

        return $this;
    }

    /**
     * Get the value of buyer_id.
     *
     * @return integer
     */
    public function getBuyerId()
    {
        return $this->buyer_id;
    }

    /**
     * Set the value of payment_type_id.
     *
     * @param integer $payment_type_id
     * @return \ErsBase\Entity\Base\Order
     */
    public function setPaymentTypeId($payment_type_id)
    {
        $this->payment_type_id = $payment_type_id;

        return $this;
    }

    /**
     * Get the value of payment_type_id.
     *
     * @return integer
     */
    public function getPaymentTypeId()
    {
        return $this->payment_type_id;
    }

    /**
     * Set the value of hashkey.
     *
     * @param string $hashkey
     * @return \ErsBase\Entity\Base\Order
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
     * Set the value of invoice_detail.
     *
     * @param string $invoice_detail
     * @return \ErsBase\Entity\Base\Order
     */
    public function setInvoiceDetail($invoice_detail)
    {
        $this->invoice_detail = $invoice_detail;

        return $this;
    }

    /**
     * Get the value of invoice_detail.
     *
     * @return string
     */
    public function getInvoiceDetail()
    {
        return $this->invoice_detail;
    }

    /**
     * Set the value of payment_status.
     *
     * @param string $payment_status
     * @return \ErsBase\Entity\Base\Order
     */
    public function setPaymentStatus($payment_status)
    {
        $this->payment_status = $payment_status;

        return $this;
    }

    /**
     * Get the value of payment_status.
     *
     * @return string
     */
    public function getPaymentStatus()
    {
        return $this->payment_status;
    }

    /**
     * Set the value of order_sum.
     *
     * @param float $order_sum
     * @return \ErsBase\Entity\Base\Order
     */
    public function setOrderSum($order_sum)
    {
        $this->order_sum = $order_sum;

        return $this;
    }

    /**
     * Get the value of order_sum.
     *
     * @return float
     */
    public function getOrderSum()
    {
        return $this->order_sum;
    }

    /**
     * Set the value of total_sum.
     *
     * @param float $total_sum
     * @return \ErsBase\Entity\Base\Order
     */
    public function setTotalSum($total_sum)
    {
        $this->total_sum = $total_sum;

        return $this;
    }

    /**
     * Get the value of total_sum.
     *
     * @return float
     */
    public function getTotalSum()
    {
        return $this->total_sum;
    }

    /**
     * Set the value of refund_sum.
     *
     * @param float $refund_sum
     * @return \ErsBase\Entity\Base\Order
     */
    public function setRefundSum($refund_sum)
    {
        $this->refund_sum = $refund_sum;

        return $this;
    }

    /**
     * Get the value of refund_sum.
     *
     * @return float
     */
    public function getRefundSum()
    {
        return $this->refund_sum;
    }

    /**
     * Set the value of updated.
     *
     * @param \DateTime $updated
     * @return \ErsBase\Entity\Base\Order
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
     * @return \ErsBase\Entity\Base\Order
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
     * Add Log entity to collection (one to many).
     *
     * @param \ErsBase\Entity\Base\Log $log
     * @return \ErsBase\Entity\Base\Order
     */
    public function addLog(Log $log)
    {
        $this->logs[] = $log;

        return $this;
    }

    /**
     * Remove Log entity from collection (one to many).
     *
     * @param \ErsBase\Entity\Base\Log $log
     * @return \ErsBase\Entity\Base\Order
     */
    public function removeLog(Log $log)
    {
        $this->logs->removeElement($log);

        return $this;
    }

    /**
     * Get Log entity collection (one to many).
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * Add Match entity to collection (one to many).
     *
     * @param \ErsBase\Entity\Base\Match $match
     * @return \ErsBase\Entity\Base\Order
     */
    public function addMatch(Match $match)
    {
        $this->matches[] = $match;

        return $this;
    }

    /**
     * Remove Match entity from collection (one to many).
     *
     * @param \ErsBase\Entity\Base\Match $match
     * @return \ErsBase\Entity\Base\Order
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
     * @param \ErsBase\Entity\Base\Package $package
     * @return \ErsBase\Entity\Base\Order
     */
    public function addPackage(Package $package)
    {
        $this->packages[] = $package;

        return $this;
    }

    /**
     * Remove Package entity from collection (one to many).
     *
     * @param \ErsBase\Entity\Base\Package $package
     * @return \ErsBase\Entity\Base\Order
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
     * Add PaymentDetail entity to collection (one to many).
     *
     * @param \ErsBase\Entity\Base\PaymentDetail $paymentDetail
     * @return \ErsBase\Entity\Base\Order
     */
    public function addPaymentDetail(PaymentDetail $paymentDetail)
    {
        $this->paymentDetails[] = $paymentDetail;

        return $this;
    }

    /**
     * Remove PaymentDetail entity from collection (one to many).
     *
     * @param \ErsBase\Entity\Base\PaymentDetail $paymentDetail
     * @return \ErsBase\Entity\Base\Order
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
     * Set Status entity (many to one).
     *
     * @param \ErsBase\Entity\Base\Status $status
     * @return \ErsBase\Entity\Base\Order
     */
    public function setStatus(Status $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get Status entity (many to one).
     *
     * @return \ErsBase\Entity\Base\Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set Code entity (many to one).
     *
     * @param \ErsBase\Entity\Base\Code $code
     * @return \ErsBase\Entity\Base\Order
     */
    public function setCode(Code $code = null)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get Code entity (many to one).
     *
     * @return \ErsBase\Entity\Base\Code
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set User entity (many to one).
     *
     * @param \ErsBase\Entity\Base\User $user
     * @return \ErsBase\Entity\Base\Order
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get User entity (many to one).
     *
     * @return \ErsBase\Entity\Base\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set PaymentType entity (many to one).
     *
     * @param \ErsBase\Entity\Base\PaymentType $paymentType
     * @return \ErsBase\Entity\Base\Order
     */
    public function setPaymentType(PaymentType $paymentType = null)
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    /**
     * Get PaymentType entity (many to one).
     *
     * @return \ErsBase\Entity\Base\PaymentType
     */
    public function getPaymentType()
    {
        return $this->paymentType;
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
        $dataFields = array('id', 'status_id', 'code_id', 'buyer_id', 'payment_type_id', 'hashkey', 'invoice_detail', 'payment_status', 'order_sum', 'total_sum', 'refund_sum', 'updated', 'created');
        $relationFields = array('user', 'paymentType', 'code', 'status');
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
        return array('id', 'status_id', 'code_id', 'buyer_id', 'payment_type_id', 'hashkey', 'invoice_detail', 'payment_status', 'order_sum', 'total_sum', 'refund_sum', 'updated', 'created');
    }
}