<?php
/**
 * @file
 * Contains \LushDigital\MicroServiceCore\Pagination\Response.
 */

namespace LushDigital\MicroServiceCore\Pagination;

use Illuminate\Support\Str;

/**
 * Class to represent a pagination response.
 *
 * @package LushDigital\MicroServiceCore\Pagination
 */
class Response
{
    /**
     * The total number of items.
     *
     * @var int
     */
    protected $total;

    /**
     * Number of items displayed per page.
     *
     * @var int
     */
    protected $perPage;

    /**
     * The current page number.
     *
     * @var int
     */
    protected $currentPage;

    /**
     * The number of the last possible page.
     *
     * @var int
     */
    protected $lastPage;

    /**
     * The number of the next page (if possible).
     *
     * @var int|null
     */
    protected $nextPage = null;

    /**
     * The number of the previous page (if possible).
     * @var int|null
     */
    protected $prevPage = null;

    /**
     * Response constructor.
     *
     * @param int $total
     *     The total number of items.
     * @param int $perPage
     *     The number of items to display per page.
     * @param int $currentPage
     *     The current page.
     * @param int $lastPage
     *     The number of the last possible page.
     */
    public function __construct($total, $perPage, $currentPage, $lastPage)
    {
        // Set the basic response values.
        $this->setTotal($total);
        $this->setPerPage($perPage);
        $this->setCurrentPage($currentPage);
        $this->setLastPage($lastPage);

        // Work out the prev/next page values.
        $this->nextPage = ($this->lastPage > $this->currentPage) ? $this->currentPage + 1 : null;
        $this->prevPage = ($this->currentPage > 1) ? $this->currentPage - 1 : null;
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param int $total
     */
    public function setTotal($total)
    {
        if (!is_int($total)) {
            throw new \RuntimeException('Total value must be an integer.');
        }

        $this->total = $total;
    }

    /**
     * @return int
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * @param int $perPage
     */
    public function setPerPage($perPage)
    {
        if (!is_int($perPage)) {
            throw new \RuntimeException('Per page value must be an integer.');
        }

        $this->perPage = $perPage;
    }

    /**
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @param int $currentPage
     */
    public function setCurrentPage($currentPage)
    {
        if (!is_int($currentPage)) {
            throw new \RuntimeException('Current page value must be an integer.');
        }

        $this->currentPage = $currentPage;
    }

    /**
     * @return int
     */
    public function getLastPage()
    {
        return $this->lastPage;
    }

    /**
     * @param int $lastPage
     */
    public function setLastPage($lastPage)
    {
        if (!is_int($lastPage)) {
            throw new \RuntimeException('Last page value must be an integer.');
        }

        $this->lastPage = $lastPage;
    }

    /**
     * @return int|null
     */
    public function getNextPage()
    {
        return $this->nextPage;
    }

    /**
     * @param int|null $nextPage
     */
    public function setNextPage($nextPage = null)
    {
        if (!empty($nextPage) && !is_int($nextPage)) {
            throw new \RuntimeException('Next page value must be an integer or null.');
        }

        $this->nextPage = $nextPage;
    }

    /**
     * @return int|null
     */
    public function getPrevPage()
    {
        return $this->prevPage;
    }

    /**
     * @param int|null $prevPage
     */
    public function setPrevPage($prevPage)
    {
        if (!empty($prevPage) && !is_int($prevPage)) {
            throw new \RuntimeException('Previous page value must be an integer or null.');
        }

        $this->prevPage = $prevPage;
    }

    /**
     * Format the response object with snake case properties.
     *
     * @return \stdClass
     */
    public function snakeFormat()
    {
        $snake = new \stdClass;
        $rawValues = get_object_vars($this);

        foreach ($rawValues as $property => $value){
            $snake->{Str::snake($property)} = $value;
        }

        return $snake;
    }
}