<?php

namespace App\Document;

use App\Core\APiController;
class DocumentController extends APiController
{

    public function __construct()
    {
        $this->authenicate();
    }

    public function upload()
    {
        // Check if it's a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // If it's not a POST request, return a 405 Method Not Allowed response
            $this->sendJsonResponse(405, ['error' => 'Method Not Allowed']);
        }

        try {

            // Check $_FILES['file']['error'] value.
            switch ($_FILES['file']['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new \RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new \RuntimeException('Exceeded filesize limit.');
                default:
                    throw new \RuntimeException('Unknown errors.');
            }

            // check filesize.
        if ($_FILES['file']['size'] > 1000000) {
            throw new \RuntimeException('Exceeded filesize limit.');
        }

        // Check MIME Type.
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $allowedMimeTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
        ];

        $fileMimeType = $finfo->file($_FILES['file']['tmp_name']);

        if (!in_array($fileMimeType, array_keys($allowedMimeTypes))) {
            throw new \RuntimeException('Invalid file format.');
        }

        // Check if the file was uploaded without errors
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = BASE_DIR . '/uploads/';
            $uploadFile = $uploadDir . basename($_FILES['file']['name']);

            // Move the uploaded file to the desired directory
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
                $this->sendJsonResponse(200, ['message' => 'File uploaded successfully', 'file' => $_FILES['file']['name']]);
            } else {
                $this->sendJsonResponse(400, ['error' => 'Error moving file']);
            }
        } else {
            $this->sendJsonResponse(400, ['error' => 'Error uploading file']);
        }

        } catch (\Exception $e) {
            // Respond with error message
            $response = ['error' => $e->getMessage()];
            $this->sendJsonResponse(400, $response);
        }

    }

   
}

