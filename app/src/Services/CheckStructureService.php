<?php
namespace App\src\Services;

use App\src\Path\UploadPath;

class CheckStructureService
{
    private $path;

    /**
     * CheckStructureService constructor.
     *
     * @param string $path
     */
    /*public function __construct($path)
    {
        $this->path = $path;
    }
*/
    public function __construct(UploadPath $path)
    {
        $this->path = $path->getPath();
    }

    /**
     * @param string $path
     *
     * @throws \Exception
     */
    private function create($path)
    {
        if (!file_exists($path)) {
            $result = mkdir($path, 0777);
            if (false === $result) {
                throw new \Exception('Can\'t create directory '.$path);
            }
        }
    }

    /**
     * @throws \Exception
     */
    private function createPath()
    {
        if (!file_exists($this->path)) {
            $this->create($this->path);
        }

    }

    /**
     * @throws \Exception
     */
    public function init()
    {
        $this->createPath();
    }

}

