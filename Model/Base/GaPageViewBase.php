<?php

/**
 * GaPageView base model for table: ga_page_view
 */

namespace Octo\GoogleAnalytics\Model\Base;

use DateTime;
use Block8\Database\Query;
use Octo\Model;
use Octo\Store;
use Octo\GoogleAnalytics\Model\GaPageView;
use Octo\GoogleAnalytics\Store\GaPageViewStore;

/**
 * GaPageView Base Model
 */
abstract class GaPageViewBase extends Model
{
    protected $table = 'ga_page_view';
    protected $model = 'GaPageView';
    protected $data = [
        'id' => null,
        'date' => null,
        'updated' => null,
        'value' => null,
        'metric' => null,
    ];

    protected $getters = [
        'id' => 'getId',
        'date' => 'getDate',
        'updated' => 'getUpdated',
        'value' => 'getValue',
        'metric' => 'getMetric',
    ];

    protected $setters = [
        'id' => 'setId',
        'date' => 'setDate',
        'updated' => 'setUpdated',
        'value' => 'setValue',
        'metric' => 'setMetric',
    ];

    /**
     * Return the database store for this model.
     * @return GaPageViewStore
     */
    public static function Store() : GaPageViewStore
    {
        return GaPageViewStore::load();
    }

    /**
     * Get GaPageView by primary key: id
     * @param int $id
     * @return GaPageView|null
     */
    public static function get(int $id) : ?GaPageView
    {
        return self::Store()->getById($id);
    }

    /**
     * @throws \Exception
     * @return GaPageView
     */
    public function save() : GaPageView
    {
        $rtn = self::Store()->save($this);

        if (empty($rtn)) {
            throw new \Exception('Failed to save GaPageView');
        }

        if (!($rtn instanceof GaPageView)) {
            throw new \Exception('Unexpected ' . get_class($rtn) . ' received from save.');
        }

        $this->data = $rtn->toArray();

        return $this;
    }


    /**
     * Get the value of Id / id
     * @return int
     */

     public function getId() : int
     {
        $rtn = $this->data['id'];

        return $rtn;
     }
    
    /**
     * Get the value of Date / date
     * @return DateTime
     */

     public function getDate() : ?DateTime
     {
        $rtn = $this->data['date'];

        if (!empty($rtn)) {
            $rtn = new DateTime($rtn);
        }

        return $rtn;
     }
    
    /**
     * Get the value of Updated / updated
     * @return DateTime
     */

     public function getUpdated() : ?DateTime
     {
        $rtn = $this->data['updated'];

        if (!empty($rtn)) {
            $rtn = new DateTime($rtn);
        }

        return $rtn;
     }
    
    /**
     * Get the value of Value / value
     * @return int
     */

     public function getValue() : ?int
     {
        $rtn = $this->data['value'];

        return $rtn;
     }
    
    /**
     * Get the value of Metric / metric
     * @return string
     */

     public function getMetric() : ?string
     {
        $rtn = $this->data['metric'];

        return $rtn;
     }
    
    
    /**
     * Set the value of Id / id
     * @param $value int
     * @return GaPageView
     */
    public function setId(int $value) : GaPageView
    {

        if ($this->data['id'] !== $value) {
            $this->data['id'] = $value;
            $this->setModified('id');
        }

        return $this;
    }
    
    /**
     * Set the value of Date / date
     * @param $value DateTime
     * @return GaPageView
     */
    public function setDate($value) : GaPageView
    {
        $this->validateDate('Date', $value);

        if ($this->data['date'] !== $value) {
            $this->data['date'] = $value;
            $this->setModified('date');
        }

        return $this;
    }
    
    /**
     * Set the value of Updated / updated
     * @param $value DateTime
     * @return GaPageView
     */
    public function setUpdated($value) : GaPageView
    {
        $this->validateDate('Updated', $value);

        if ($this->data['updated'] !== $value) {
            $this->data['updated'] = $value;
            $this->setModified('updated');
        }

        return $this;
    }
    
    /**
     * Set the value of Value / value
     * @param $value int
     * @return GaPageView
     */
    public function setValue(?int $value) : GaPageView
    {

        if ($this->data['value'] !== $value) {
            $this->data['value'] = $value;
            $this->setModified('value');
        }

        return $this;
    }
    
    /**
     * Set the value of Metric / metric
     * @param $value string
     * @return GaPageView
     */
    public function setMetric(?string $value) : GaPageView
    {

        if ($this->data['metric'] !== $value) {
            $this->data['metric'] = $value;
            $this->setModified('metric');
        }

        return $this;
    }
    
    
}
