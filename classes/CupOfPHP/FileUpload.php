<?php

namespace CupOfPHP;

/**
 * Handles a single file upload.
 *
 * @package  CupOfPHP
 * @author   Jordan Newland <github@jenewland.me.uk>
 * @license  All Rights Reserved
 * @link     https://github.com/jenewland1999/
 */
class FileUpload
{
    private $file;
    private $fileExt;
    private $fileName;
    private $fileSize;
    private $fileTmpName;
    private $fileType;
    private $options;
    private $newFileName;

    public function __construct(array $files, string $name, array $options = [])
    {
        if (isset($files[$name])) {
            $this->file = $files[$name];
        } else {
            return;
        }

        if (
            array_key_exists('uploadsDir', $options) &&
            array_key_exists('namePrefix', $options) &&
            array_key_exists('validFileExts', $options) &&
            array_key_exists('maxFileSizeMB', $options)
        ) {
            $this->options = $options;
        } else {
            $this->options = [
                'uploadsDir' => '/public/uploads/',
                'namePrefix' => '',
                'validFileExts' => [
                    'docx', 'doc', 'pdf', 'rtf', 'jpg', 'jpeg', 'png', 'bmp', 'webp', 'gif'
                ],
                'maxFileSizeMB' => 5
            ];
        }

        // Retrieve info about the file from the $this->file
        $fileParts = explode('.', $this->file['name']);
        $this->fileExt = strtolower(end($fileParts));
        $this->fileName = $this->file['name'];
        $this->fileSize = $this->file['size'];
        $this->fileTmpName = $this->file['tmp_name'];
        $this->fileType = $this->file['type'];

        // Generate a unique ID for the file (prevents other users finding them easily)
        $this->newFileName = uniqid($this->options['namePrefix']) . '.' . $this->fileExt;
    }

    public function getNewFileName()
    {
        return $this->newFileName;
    }

    public function getUploadPath()
    {
        // Check the uploads directory exists and if not, create it
        $currentDir = getcwd();
        if (!file_exists($currentDir . $this->options['uploadsDir'])) {
            mkdir($currentDir . $this->options['uploadsDir'], 0755, true);
        }

        // Create the upload path to move the file to
        return $currentDir . $this->options['uploadsDir'] . $this->getNewFileName();
    }

    public function checkFile()
    {
        // Declare an empty array to store potential errors
        $errors = [];

        // Validate file upload
        if (empty($this->file['name'])) {
            $errors[] = 'File not selected';
        } else {
            // Check for valid file extension
            if (!in_array($this->fileExt, $this->options['validFileExts'])) {
                $errors[] = sprintf(
                    'This file extension is not allowed. Please upload a %s or %s file.',
                    strtoupper(implode(', ', array_slice($this->options['validFileExts'], 0, -1))),
                    strtoupper(end($this->options['validFileExts']))
                );
            }

            // Check for valid file size
            if ($this->fileSize > $this->options['maxFileSizeMB'] * 1024 * 1024) {
                $errors[] = sprintf(
                    'This file exceeds the upload limit of %sKB. Please upload a smaller file.',
                    $this->options['maxFileSizeMB'] * 1000
                );
            }
        }

        // Return any form validation errors
        return $errors;
    }

    public function upload()
    {
        if (empty($this->checkFile())) {
            return  move_uploaded_file($this->fileTmpName, $this->getUploadPath());
        }
    }
}
