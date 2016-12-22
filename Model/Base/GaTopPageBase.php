<?php

/**
 * GaTopPage base model for table: ga_top_page
 */

namespace Octo\GoogleAnalytics\Model\Base;

use DateTime;
use Block8\Database\Query;
use Octo\Model;
use Octo\Store;
use Octo\GoogleAnalytics\Model\GaTopPage;

/**
 * GaTopPage Base Model
 */
abstract class GaTopPageBase extends Model
{
    protected function init()
    {
        $this->table = 'ga_top_page';
        $this->model = 'GaTopPage';

        // Columns:
        
        $this->data['id'] = null;
        $this->getters['id'] = 'getId';
        $this->setters['id'] = 'setId';
        
        $this->data['updated'] = null;
        $this->getters['updated'] = 'getUpdated';
        $this->setters['updated'] = 'setUpdated';
        
        $this->data['pageviews'] = null;
        $this->getters['pageviews'] = 'getPageviews';
        $this->setters['pageviews'] = 'setPageviews';
        
        $this->data['unique_pageviews'] = null;
        $this->getters['unique_pageviews'] = 'getUniquePageviews';
        $this->setters['unique_pageviews'] = 'setUniquePageviews';
        
        $this->data['uri'] = null;
        $this->getters['uri'] = 'getUri';
        $this->setters['uri'] = 'setUri';
        
        $this->data['page_id'] = null;
        $this->getters['page_id'] = 'getPageId';
        $this->setters['page_id'] = 'setPageId';
        
        // Foreign keys:
        
        $this->getters['Page'] = 'getPage';
        $this->setters['Page'] = 'setPage';
        
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
     * Get the value of Pageviews / pageviews
     * @return int
     */

     public function getPageviews() : ?int
     {
        $rtn = $this->data['pageviews'];

        return $rtn;
     }
    
    /**
     * Get the value of UniquePageviews / unique_pageviews
     * @return int
     */

     public function getUniquePageviews() : ?int
     {
        $rtn = $this->data['unique_pageviews'];

        return $rtn;
     }
    
    /**
     * Get the value of Uri / uri
     * @return string
     */

     public function getUri() : ?string
     {
        $rtn = $this->data['uri'];

        return $rtn;
     }
    
    /**
     * Get the value of PageId / page_id
     * @return string
     */

     public function getPageId() : ?string
     {
        $rtn = $this->data['page_id'];

        return $rtn;
     }
    
    
    /**
     * Set the value of Id / id
     * @param $value int
     * @return GaTopPage
     */
    public function setId(int $value) : GaTopPage
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
     * @return GaTopPage
     */
    public function setUpdated($value) : GaTopPage
    {
        $this->validateDate('Updated', $value);

        if ($this->data['updated'] !== $value) {
            $this->data['updated'] = $value;
            $this->setModified('updated');
        }

        return $this;
    }
    
    /**
     * Set the value of Pageviews / pageviews
     * @param $value int
     * @return GaTopPage
     */
    public function setPageviews(?int $value) : GaTopPage
    {

        if ($this->data['pageviews'] !== $value) {
            $this->data['pageviews'] = $value;
            $this->setModified('pageviews');
        }

        return $this;
    }
    
    /**
     * Set the value of UniquePageviews / unique_pageviews
     * @param $value int
     * @return GaTopPage
     */
    public function setUniquePageviews(?int $value) : GaTopPage
    {

        if ($this->data['unique_pageviews'] !== $value) {
            $this->data['unique_pageviews'] = $value;
            $this->setModified('unique_pageviews');
        }

        return $this;
    }
    
    /**
     * Set the value of Uri / uri
     * @param $value string
     * @return GaTopPage
     */
    public function setUri(?string $value) : GaTopPage
    {

        if ($this->data['uri'] !== $value) {
            $this->data['uri'] = $value;
            $this->setModified('uri');
        }

        return $this;
    }
    
    /**
     * Set the value of PageId / page_id
     * @param $value string
     * @return GaTopPage
     */
    public function setPageId(?string $value) : GaTopPage
    {

        // As this column is a foreign key, empty values should be considered null.
        if (empty($value)) {
            $value = null;
        }


        if ($this->data['page_id'] !== $value) {
            $this->data['page_id'] = $value;
            $this->setModified('page_id');
        }

        return $this;
    }
    
    
    /**
     * Get the Page model for this  by Id.
     *
     * @uses \Octo\Pages\Store\PageStore::getById()
     * @uses \Octo\Pages\Model\Page
     * @return \Octo\Pages\Model\Page
     */
    public function getPage()
    {
        $key = $this->getPageId();

        if (empty($key)) {
           return null;
        }

        return Store::get('Page')->getById($key);
    }

    /**
     * Set Page - Accepts an ID, an array representing a Page or a Page model.
     * @throws \Exception
     * @param $value mixed
     */
    public function setPage($value)
    {
        // Is this a scalar value representing the ID of this foreign key?
        if (is_scalar($value)) {
            return $this->setPageId($value);
        }

        // Is this an instance of Page?
        if (is_object($value) && $value instanceof \Octo\Pages\Model\Page) {
            return $this->setPageObject($value);
        }

        // Is this an array representing a Page item?
        if (is_array($value) && !empty($value['id'])) {
            return $this->setPageId($value['id']);
        }

        // None of the above? That's a problem!
        throw new \Exception('Invalid value for Page.');
    }

    /**
     * Set Page - Accepts a Page model.
     *
     * @param $value \Octo\Pages\Model\Page
     */
    public function setPageObject(\Octo\Pages\Model\Page $value)
    {
        return $this->setPageId($value->getId());
    }

}
