<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

use League\Glide\Signatures\SignatureFactory;
use League\Glide\Signatures\SignatureException;

class Images extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function show($imageSubdirectory, $imageFilename)
    {
        $defaults = array(
            'w' => 50,
            'h' => 50,
            'bg' => 'FFFFFF',
            'fit' => 'crop'
        );

        // Don't try accessing image files that don't exist        
        $testPath = ltrim(IMAGE_PATH, '/') . '/' . $imageSubdirectory . '/' . $imageFilename;
        
        if (is_file($testPath)) {
            // Security: check valid signature (to prevent mass image-resize attacks)
            $this->validateRequest($testPath);

            // Setup Glide server
            $server = League\Glide\ServerFactory::create([
                'source' => ltrim(IMAGE_PATH, '/') . '/' . $imageSubdirectory . '/',
                'cache' => ltrim(IMAGE_CACHE_PATH, '/'),
                'max_image_size' => MAX_IMAGE_DIMENSIONS,
                'defaults' => $defaults
            ]);

            $server->outputImage($imageFilename, $this->input->get() ?: []);
        }
        else {
            show_404();
        }
    }

    /**
     * Checks the image request is properly signed, to protect against
     * mass image-resize requests
     * @param  string $path The resource path.
     * @return void       Stops further processing if request is invalid.
     */
    private function validateRequest($path) 
    {
        try {
            $signkey = config_item('glide_image_signature');

            // Validate HTTP signature
            SignatureFactory::create($signkey)->validateRequest($path, $_GET);

        } catch (SignatureException $e) {
            // Handle error
            // var_dump($e); die;
        }
    }
}