<?php
require 'vendor/autoload.php';
use Aws\S3\S3Client;

class awsUpload {
    private $s3;
    
    public function __construct() {
        $this->s3 = new S3Client([
            'version' => 'latest',
            'region'  => 'us-east-1',
            'credentials' => [
                'key'    => KEY_S3,
                'secret' => SECRET_S3,
            ],
        ]);
    }

    public function upload($image, $bucketName, $keyName) {
        try {
            $result = $this->s3->putObject([
                'Bucket' => $bucketName,
                'Key'    => $keyName,
                'SourceFile' => $image,
                'ACL'    => 'public-read', 
                'ContentType' => mime_content_type($image), 
            ]);

            return json_encode([
                'status' => 'success',
                'url' => $result['ObjectURL'],
            ]);

        } catch (S3Exception $e) {
            return json_encode([
                'status' => 'error',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function delete($bucketName, $keyName) {
        try {
            $result = $this->s3->deleteObject([
                'Bucket' => $bucketName,
                'Key'    => $keyName,
            ]);
    
            return json_encode([
                'status' => 'success',
                'message' => 'Deleted successfully',
            ]);
    
        } catch (S3Exception $e) {
            return json_encode([
                'status' => 'error',
                'error' => $e->getMessage(),
            ]);
        }
    }
    
}
?>