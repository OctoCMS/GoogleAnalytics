<?php

/**
 * GaSummaryView base model for table: ga_summary_view
 */

namespace Octo\GoogleAnalytics\Model\Base;

use DateTime;
use Block8\Database\Query;
use Octo\Model;
use Octo\Store;
use Octo\GoogleAnalytics\Model\GaSummaryView;

/**
 * GaSummaryView Base Model
 */
abstract class GaSummaryViewBase extends Model
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

     public function getId() : int
     {
        $rtn = $this->data['id'];

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
     * @return GaSummaryView
     */
    public function setId(int $value) : GaSummaryView
    {

        if ($this->data['id'] !== $value) {
            $this->data['id'] = $value;
            $this->setModified('id');
        }

        return $this;
    }
    
    /**
     * Set the value of Updated / updated
     * @param $value DateTime
     * @return GaSummaryView
     */
    public function setUpdated($value) : GaSummaryView
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
     * @return GaSummaryView
     */
    public function setValue(?int $value) : GaSummaryView
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
     * @return GaSummaryView
     */
    public function setMetric(?string $value) : GaSummaryView
    {

        if ($this->data['metric'] !== $value) {
            $this->data['metric'] = $value;
            $this->setModified('metric');
        }

        return $this;
    }
    
    
}
