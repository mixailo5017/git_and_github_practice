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

        // Setup Glide server
        $server = League\Glide\ServerFactory::create([
            'source' => 'images/' . $imageSubdirectory,
            'cache' => 'cache/made',
            'defaults' => $defaults
        ]);

        $server->outputImage($imageFilename, $this->input->get() ?: []);
    }
}