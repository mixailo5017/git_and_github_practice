<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

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
            // Setup Glide server
            $server = League\Glide\ServerFactory::create([
                'source' => ltrim(IMAGE_PATH, '/') . '/' . $imageSubdirectory . '/',
                'cache' => ltrim(IMAGE_CACHE_PATH, '/'),
                'defaults' => $defaults
            ]);

            $server->outputImage($imageFilename, $this->input->get() ?: []);
        }
        else {
            show_404();
        }
    }
}