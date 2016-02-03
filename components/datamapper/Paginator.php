<?php

namespace pwf\components\datamapper;

class Paginator extends \pwf\components\datapaginator\abstraction\Paginator
{
    /**
     * Data source
     *
     * @var interfaces\Getter
     */
    private $dataSource;

    /**
     *
     * @var mixed
     */
    private $condition;

    /**
     * Set condition
     *
     * @param mixed $condition
     * @return \pwf\components\datamapper\Paginator
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;
        return $this;
    }

    /**
     * Get condition
     *
     * @return mixed
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * Set data source
     *
     * @param \pwf\components\datamapper\interfaces\Getter $dataSource
     * @return \pwf\components\datamapper\Paginator
     */
    public function setDataSource(interfaces\Getter $dataSource)
    {
        $this->dataSource = $dataSource;
        return $this;
    }

    /**
     * Get data source
     *
     * @return \pwf\components\datamapper\interfaces\Getter
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }

    /**
     * Get data
     *
     * @return mixed[]
     */
    public function getData()
    {
        $limit = $this->getLimit();
        $this->setHeaders();
        return $this->getDataSource()->getAll($this->getCondition(), $limit,
                $limit * $this->getPage());
    }

    /**
     * Set headers
     *
     * @return \pwf\components\datamapper\Paginator
     */
    protected function setHeaders()
    {
        $cnt   = $this->getDataSource()->count($this->getCondition());
        $limit = $this->getLimit();
        if ($limit == 0) {
            $limit = 1;
        }
        \pwf\basic\Application::$instance
            ->getResponse()
            ->setHeaders([
                'x-pagination-current-page: '.$this->getPage(),
                'x-pagination-page-count'.ceil($cnt / $limit),
                'x-pagination-per-page'.$limit,
                'x-pagination-total-count'.$cnt
        ]);
        return $this;
    }
}