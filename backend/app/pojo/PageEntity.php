<?php

namespace app\pojo;
use JsonSerializable;

class PageEntity implements JsonSerializable {
    

    private $list;
    private int $total;
    private int $totalPage;
    private int $currentPage;
    private int $pageSize;


    public function __construct($list = [], int $total = 0, int $currentPage = 0, int $pageSize = 0) {
        $this -> list = $list;
        $this -> total = $total;
        $this -> currentPage = $currentPage;
        $this -> pageSize = $pageSize;
    }

    public function jsonSerialize(){
		return [
			'list' => $this -> getList(),
			'total' => $this -> getTotal(),
            'totalPage' => $this -> getTotalPage(),
			'currentPage' => $this -> getCurrentPage(),
			'pageSize' => $this -> getPageSize()
		];
	}

	/**
	 * @return mixed
	 */
	public function getList() {
		return $this->list;
	}
	
	/**
	 * @param mixed $list 
	 * @return self
	 */
	public function setList($list): self {
		$this->list = $list;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getCurrentPage(): int {
		return $this->currentPage;
	}
	
	/**
	 * @param int $currentPage 
	 * @return self
	 */
	public function setCurrentPage(int $currentPage): self {
		$this->currentPage = $currentPage;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getPageSize(): int {
		return $this->pageSize;
	}
	
	/**
	 * @param int $pageSize 
	 * @return self
	 */
	public function setPageSize(int $pageSize): self {
		$this->pageSize = $pageSize;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getTotal(): int {
		return $this->total;
	}
	
	/**
	 * @param int $total 
	 * @return self
	 */
	public function setTotal(int $total): self {
		$this->total = $total;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getTotalPage(): int {
		return ceil($this->total / $this -> pageSize);
	}
	
	/**
	 * @param int $totalPage 
	 * @return self
	 */
	public function setTotalPage(int $totalPage): self {
		$this->totalPage = $totalPage;
		return $this;
	}
}