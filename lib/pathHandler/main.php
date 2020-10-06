<?php
/**
 * Then no difficulty in handling path.
 *
 * @author    CrazyWhite <moe@mailo.com>
 * @copyright 2020 (c) CrazyWhite - pathHandler
 * @license   https://opensource.org/licenses/MIT - The MIT License (MIT)
 * @link      https://github.com/Crazy-White/unpkg-proxy/tree/master/lib/pathHandler
 * @since     1.0.1
 */

namespace pathHandler;

function redirect()
{
    /*
    'localhost/sn.php?/path/to/file?fakeQuery'  to  'localhost/sn.php?/path/to/file&fakeQuery'
    then $_GET works
    */
    $uri   = $_SERVER['REQUEST_URI'];
    $query = $_SERVER['QUERY_STRING'];
    
    if (strpos($query, '?') > 0) {
        $fixed_query = str_replace('?', '&', $query);
        header('Location: ' . str_replace($query, $fixed_query, $uri));
        die();
    }
}

function get($is_include_query = false)
{
    $pi    = $_SERVER['PATH_INFO'];
    $uri   = $_SERVER['REQUEST_URI'];
    $query = $_SERVER['QUERY_STRING'];
    $sn    = $_SERVER['SCRIPT_NAME'];
    
    if (isset($pi) && strlen($pi) > 0) {
        /* for path_info&rewrite */
        if (!$is_include_query)
            return $pi;
        
        if (startsWith($uri, $sn)) {
            return substr($uri, strlen($sn));
        }
    }
    
    
    
    if ($query[0] === '/') {
        /* for querystring */
        if ($is_include_query) {
            return $query;
        } else {
            
            $p = strpos($query, '?');
            if (!$p) {
                $p = strpos($query, '&');
                if (!$p)
                    return $query;
            }
            return substr($query, 0, $p);
            
        }
    }
    
    
    return '/';
}


function startsWith($haystack, $needle)
{
    return substr_compare($haystack, $needle, 0, strlen($needle)) === 0;
}
function endsWith($haystack, $needle)
{
    return substr_compare($haystack, $needle, -strlen($needle)) === 0;
}