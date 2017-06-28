<?php  if (! defined('BASEPATH')) exit('No direct script access allowed');

if (! function_exists('company_image'))
{
    /**
     * Convenience wrapper for safe_image function to deal with company (member) images
     *
     * @param string $image
     * @param int|boolean $size
     * @param array $options
     * @return string
     */
    function company_image($image = '', $size = false, $options = array())
    {
        if ($size) {
            $options['max'] = $size;
        }
        return safe_image(USER_IMAGE_PATH, $image, USER_NO_IMAGE_PATH . ORGANIZATION_IMAGE_PLACEHOLDER, $options);
    }
}

if (! function_exists('store_item_image'))
{
    /**
     * Convenience wrapper for safe_image function to deal with store item images
     *
     * @param string $image
     * @param int|boolean $size
     * @param array $options
     * @return string
     */
    function store_item_image($image = '', $size = false, $options = array())
    {
        if ($size) {
            $options['max'] = $size;
        }
        return safe_image(STORE_IMAGE_PATH, $image, STORE_NO_IMAGE_PATH . STORE_ITEM_IMAGE_PLACEHOLDER, $options);
    }
}

if (! function_exists('project_image'))
{
    /**
     * Convenience wrapper for safe_image function to deal with project images
     *
     * @param string $image
     * @param int|boolean $size
     * @param array $options
     * @return string
     */
    function project_image($image = '', $size = false, $options = array())
    {
        if ($size) {
            $options['max'] = $size;
        }
        return safe_image(PROJECT_IMAGE_PATH, $image, PROJECT_NO_IMAGE_PATH . PROJECT_IMAGE_PLACEHOLDER, $options);
    }
}

if (! function_exists('expert_image'))
{
    /**
     * Convenience wrapper for safe_image function to deal with expert (member) images
     *
     * @param string $image
     * @param int|boolean $size
     * @param array $options
     * @return string
     */
    function expert_image($image = '', $size = false, $options = array())
    {
        if ($size) {
            $options['max'] = $size;
        }
        return safe_image(USER_IMAGE_PATH, $image, USER_NO_IMAGE_PATH . USER_IMAGE_PLACEHOLDER, $options);
    }
}

if (! function_exists('forum_image'))
{
    /**
     * Convenience wrapper for safe_image function to deal with forum (member) images
     *
     * @param string $image
     * @param int|boolean $size
     * @param array $options
     * @return string
     */
    function forum_image($image = '', $size = false, $options = array() )
    {

        if ($size) {
            $options['max'] = $size;
        }
        return safe_image(FORUM_IMAGE_PATH, $image, FORUM_NO_IMAGE_PATH . FORUM_IMAGE_PLACEHOLDER, $options);
    }
}

if (! function_exists('safe_image'))
{
    /**
     * @param string $imageDirectory
     * @param string $imageFilename
     * @param string $fallback
     * @param array $options
     * @return string
     */
    function safe_image($imageDirectory, $imageFilename = null, $fallback = null, $CEImageOptions = null)
    {

        $glideOptions = [];

        // Convert options in CE Image format into Glide format
        if (! is_null($CEImageOptions) && is_array($CEImageOptions)) {
            // Convert 'max' to 'w' and 'h'
            if ($maxSize = $CEImageOptions['max']) {
                $glideOptions['w'] = $maxSize;
                $glideOptions['h'] = $maxSize;
            }

            // To convert bg_color, remove the leading # and convert to upper case
            if ($CEImageOptions['bg_color']) {
                $glideOptions['bg'] = strtoupper(ltrim($CEImageOptions['bg_color'], '#'));
            }

            if ($CEImageOptions['width']) {
                $glideOptions['w'] = $CEImageOptions['width'];
            }
        }

        // Set default return value to an empty string
        $src = '';
        // Let's default to a fallback image first
        $full_path = $fallback;

        // If image is set check its dimensions first
        if (! is_null($imageFilename) && $imageFilename != '') {
            if ($CI->ce_image->open($imageDirectory . $imageFilename, $CEImageOptions)) {
                // get width and height of the image in pixels
                $width  = $CI->ce_image->get_original_width();
                $height = $CI->ce_image->get_original_height();

                // If dimensions are less than max allowed then use that image
                if ($width && $height && $width * $height < MAX_IMAGE_DIMENSIONS) {
                    $full_path = $imageDirectory . $imageFilename;
                }
            }
        }
        // Fallback image can be null so we check again
        if (! is_null($full_path) && $full_path != '') {
            if ($CI->ce_image->make($full_path, $CEImageOptions)) {
                $src = $CI->ce_image->get_relative_path();
            }
        }

        $CI->ce_image->close();

        return $src;
    }
}