<?php
/**
 * @file
 * Contains \LushDigital\MicroServiceCore\Pagination\Paginator.
 */

namespace LushDigital\MicroServiceCore\Pagination;

/**
 * Class to manage pagination.
 *
 * @package LushDigital\MicroServiceCore\Pagination
 */
class Paginator
{
    /**
     * Number of items to display per page.
     *
     * @var int
     */
    protected $perPage;

    /**
     * Which page are we on?
     *
     * @var int
     */
    protected $page;

    /**
     * The current offset to pass to the query.
     *
     * @var int
     */
    protected $offset;

    /**
     * The total number of items.
     *
     * @var int
     */
    protected $total;

    /**
     * The number of the last page.
     *
     * @var int
     */
    protected $lastPage;

    /**
     * Paginator constructor.
     *
     * @param int $total
     *     The total number of items.
     * @param int $perPage
     *     The number of items to display per page.
     * @param int $page
     *     The current page.
     */
    public function __construct($total, $perPage, $page)
    {
        // Set up the basic pagination values.
        $this->setTotal($total);
        $this->setPerPage($perPage);
        $this->setPage($page);

        // Calculate the offset and last possible page based on these values.
        $this->setOffset(($this->page - 1) * $this->perPage);
        $this->setLastPage(ceil($total / $this->perPage));
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
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage($page)
    {
        if (!is_int($page)) {
            throw new \RuntimeException('Page value must be an integer.');
        }

        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     */
    public function setOffset($offset)
    {
        if (!is_int($offset)) {
            throw new \RuntimeException('Offset value must be an integer.');
        }

        $this->offset = $offset;
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
     * Prepare the pagination response.
     *
     * @return Response
     */
    public function preparePaginationResponse()
    {
        return new Response($this->total, $this->perPage, $this->page, $this->lastPage);
    }
}