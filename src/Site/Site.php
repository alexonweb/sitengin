<?php

namespace Sitengin;

class Site
{

    private $menu = 'user/menu.json';
    private $site = 'user/site.json';

    public function __construct() 
    {

        $this->menu = $this->objFromJsonFile($this->menu);

        $this->site = $this->objFromJsonFile($this->site);

        $this->pageGenerator();

    }

    private function pageGenerator() 
    {

        $_site = $this->site->site; // quasi-global constant

        // Directories paths
        $_themedir  = 'user/templates/' . $_site->theme . '/';
        $_imgdir    = $_themedir . 'images/';
        $_jsdir     = $_themedir . 'js/';

        $_page = 'user/content/' . $this->uri() . '.xml'; // quasi-global constant

        if (file_exists($_page)) {

            $_page = simplexml_load_file($_page);

        } else {

            $_page = simplexml_load_file('user/content/er404.xml');

        }

        require 'user/templates/' . $_site->theme . '/' . $_page->template . '.php';

    }

    /*
     * Return resource identifier
     * Example: http://site.com/                return 'index'
     *          http://site.com/about/          return 'index'
     *          http://site.com/about           return 'about'
     *          http://site.com/about/second    return 'about/second'
    */ 
    private function uri()
    {

        $uri = urldecode($_SERVER['REQUEST_URI']);

        $uri = substr($uri, 1); // Remove slash at the start of the line

        if ($uri == '') {

            return 'index';

        } elseif (substr($uri, -1) == '/') {

            return $uri . 'index';

        } else {

            return $uri;

        }

    }

    /**
     * Main lavel menu
     * Return HTML unordered list
     */
    private function menu()
    {

        $htmlmenu = '<ul>';

        foreach ($this->menu as $slug => $title) {

            $current = strpos( $this->uri() , $slug );

            $htmlmenu .=    
                    '<li><a href="' . $slug . '"' .
                    ( ( $current === 0 ) ? ' class="current"' : '' )
                    . '>' . rtrim($title) . '</a></li>';

        }

        $htmlmenu .= '</ul>';

        return $htmlmenu;

    }

    private function objFromJsonFile($file)
    {

        $filecontent = file_get_contents($file);

        return json_decode($filecontent);

    }

}

?>
