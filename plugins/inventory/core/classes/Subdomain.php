<?php namespace Inventory\Core\Classes;

class Subdomain
{
    public $subdomain;

    public function __construct()
    {
        $domain = preg_replace('#^https?://#', '', \Request::root());

        $baseDomain = preg_replace('#^https?://#', '', \Config::get('app.url'));
        // If using base domain
        if (preg_match('/' . $baseDomain . '$/', $domain)) {
            // Get the subdomain
            $subdomain = substr($domain, 0, strlen($domain) - strlen($baseDomain));
            $this->subdomain = str_replace('.', '', $subdomain);
        } else {
            $this->subdomain = null;
        }
    }
}