<?php

/**
 * GaSummaryView base model for table: ga_summary_view
 */

namespace Octo\GoogleAnalytics\Model\Base;

use DateTime;
use Octo\Model;
use Octo\Store;

/**
 * GaSummaryView Base Model
 */
class GaSummaryViewBase extends Model
{
    protected function init()
    {
        $this->table = 'ga_summary_view';
        $this->model = 'GaSummaryView';

        // Columns:
        
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        
        $this->data['updated'] = null;
        $this->getters['updated'] = 'getUpdated';
        $this->setters['updated'] = 'setUpdated';
        
        $this->data['value'] = null;
        $this->getters['value'] = 'getValue';
        $this->setters['value'] = 'setValue';
        
        $this->data['metric'] = null;
        $this->getters['metric'] = 'getMetric';
        $this->setters['metric'] = 'setMetric';
        
        // Foreign keys:
        
    }

    
    /**
     * Get the value of Id / id
     * @return int
     */

     public function getId()
     {
        $rtn = $this->data['id'];

        return $rtn;
     }
    
    /**
     * Get the value of Updated / updated
     * @return DateTime
     */

     public function getUpdated()
     {
        $rtn = $this->data['updated'];

        if (!empty($rtn)) {
            $rtn = new \DateTime($rtn);
        }

        return $rtn;
     }
    
    /**
     * Get the value of Value / value
     * @return int
     */

     public function getValue()
     {
        $rtn = $this->data['value'];

        return $rtn;
     }
    
    /**
     * Get the value of Metric / metric
     * @return string
     */

     public function getMetric()
     {
        $rtn = $this->data['metric'];

        return $rtn;
     }
    
    
    /**
     * Set the value of Id / id
     * @param $value int
     */
    public function setId(int $value)
    {

        $this->validateNotNull('Id', $value);

        if ($this->data['id'] === $value) {
            return;
        }

        $this->data['id'] = $value;
        $this->setModified('id');
    }
    
    /**
     * Set the value of Updated / updated
     * @param $value DateTime
     */
    public function setUpdated($value)
    {
        $this->validateDate('Updated', $value);


        if ($this->data['updated'] === $value) {
            return;
        }

        $this->data['updated'] = $value;
        $this->setModified('updated');
    }
    
    /**
     * Set the value of Value / value
     * @param $value int
     */
    public function setValue($value)
    {



        if ($this->data['value'] === $value) {
            return;
        }

        $this->data['value'] = $value;
        $this->setModified('value');
    }
    
    /**
     * Set the value of Metric / metric
     * @param $value string
     */
    public function setMetric($value)
    {



        if ($this->data['metric'] === $value) {
            return;
        }

        $this->data['metric'] = $value;
        $this->setModified('metric');
    }
    
    }
