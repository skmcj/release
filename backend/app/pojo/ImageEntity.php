<?php

namespace app\pojo;

use JsonSerializable;
use think\File;

class ImageEntity implements JsonSerializable {

    private int $size;

    private string $name;

    private string $url;

    private string $type;
    
    public function __construct(int $size = 0, string $name = '', string $url = '', string $type = '') {
        $this -> size = $size;
        $this -> name = $name;
        $this -> url = $url;
        $this -> type = $type;
    }

    /**
     * 解析文件
     */
    public static function parseFile(File $file, $fileName = '', $type = 'storage'): ImageEntity {
        $res = new ImageEntity($file -> getSize(), $fileName, '', $file -> extension());

        $imgHost = '';
		if($type === 'storage') {
			$imgHost = config('common.img_host');
		} else {
			$imgHost = config('common.img_tmp');
		}
        $res -> setUrl("{$imgHost}{$fileName}");
        return $res;
    }


	public function jsonSerialize(){
		return [
			'size' => $this -> getSize(),
			'name' => $this -> getName(),
			'type' => $this -> getType(),
			'url' => $this -> getUrl()
		];
	}

	/**
	 * @return int
	 */
	public function getSize(): int {
		return $this->size;
	}
	
	/**
	 * @param int $size 
	 * @return self
	 */
	public function setSize(int $size): self {
		$this->size = $size;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}
	
	/**
	 * @param string $name 
	 * @return self
	 */
	public function setName(string $name): self {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUrl(): string {
		return $this->url;
	}
	
	/**
	 * @param string $url 
	 * @return self
	 */
	public function setUrl(string $url): self {
		$this->url = $url;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getType(): string {
		return $this->type;
	}
	
	/**
	 * @param string $type 
	 * @return self
	 */
	public function setType(string $type): self {
		$this->type = $type;
		return $this;
	}

    public function __toString(): string {
        return 'sss';
    }

    public function toArray() {
        return ['sss' => 'sss'];
    }
}