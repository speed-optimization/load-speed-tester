<?php

class Jm_Filter_MimeTypeToType implements Zend_Filter_Interface
{

    /**
     * Normalize MIME type to a more basic description.
     *
     * @param type $mimetype
     * @return string
     */
    function filter($mimetype)
    {
        $ret = $mimetype;

        switch ($mimetype) {

            case 'text/html':
                $ret = 'HTML';
                break;

            case 'text/css':
                $ret = 'CSS';
                break;

            case 'image/gif':
            case 'image/jpg':
            case 'image/jpeg':
            case 'image/png':
                $ret = 'Image';
                break;

            case 'text/javascript':
            case 'application/x-javascript':
            case 'application/javascript':
                $ret = 'JS';
                break;
        }

        return $ret;
    }

}