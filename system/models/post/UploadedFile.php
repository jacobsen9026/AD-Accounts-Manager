<?php

/*
 * The MIT License
 *
 * Copyright 2020 cjacobsen.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace System\Models\Post;

/**
 * Description of File
 *
 * @author cjacobsen
 */

use System\File;

class UploadedFile
{

    private $name;
    private $type;
    private $tempFileName;
    private $error;
    private $fileSize;

    public function __construct(array $rawFileUpload)
    {
        $this->name = $rawFileUpload["name"];
        $this->type = $rawFileUpload["type"];
        $this->tempFileName = $rawFileUpload["tmp_name"];
        $this->error = $rawFileUpload["error"];
        $this->fileSize = $rawFileUpload["size"];
    }

    public function getName()
    {
        return $this->name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getTempFileName()
    {
        return $this->tempFileName;
    }

    public function getError()
    {
        return $this->error;
    }

    public function getFileSize()
    {
        return $this->fileSize;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function setTempFileName($tempFileName)
    {
        $this->tempFileName = $tempFileName;
        return $this;
    }

    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;
        return $this;
    }

    public function saveTo(string $destinationFilePath)
    {
        move_uploaded_file($this->tempFileName, $destinationFilePath);
    }

    public function getTempFileContents()
    {
        return File::getContents($this->tempFileName);
    }

    public function exists()
    {
        if ($this->tempFileName == null) {
            return false;
        }
        return true;
    }

}
