<?php
if (! function_exists('session')) {
    /**
     * Get SessionManager
     *
     * @return \Swoft\Session\SessionManager
     */
    function session()
    {
        return \Swoft\App::getBean('sessionManager');
    }
}