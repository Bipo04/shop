<?php
require 'vendor/autoload.php';
use Cloudinary\Cloudinary;

class Cloud {
    public function connect() {
        return $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => CLOUD_NAME,
                'api_key'    => API_KEY,
                'api_secret' => API_SECRET,
            ],
        ]);
    }
}
?>