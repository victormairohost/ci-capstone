<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// inspired from the source >> https://github.com/nobuti/Codeigniter-breadcrumbs
class Breadcrumbs
{


        protected $CI;
        private $breadcrumbs = array();

        public function __construct()
        {

                $this->CI = & get_instance();
                $this->CI->config->load('breadcrumbs');

                $this->breadcrumb_open               = $this->CI->config->item('breadcrumb_open');
                $this->breadcrumb_close              = $this->CI->config->item('breadcrumb_close');
                $this->breadcrumb_el_open            = $this->CI->config->item('breadcrumb_el_open');
                $this->breadcrumb_el_open_extra      = $this->CI->config->item('breadcrumb_el_open_extra');
                $this->breadcrumb_el_close           = $this->CI->config->item('breadcrumb_el_close');
                $this->breadcrumb_el_first           = $this->CI->config->item('breadcrumb_el_first');
                $this->breadcrumb_el_first_extra     = $this->CI->config->item('breadcrumb_el_first_extra');
                $this->breadcrumb_el_last_open_extra = $this->CI->config->item('breadcrumb_el_last_open_extra');
                $this->breadcrumb_el_last_open       = $this->CI->config->item('breadcrumb_el_last_open');
                $this->breadcrumb_el_last_close      = $this->CI->config->item('breadcrumb_el_last_close');
        }

        function array_sorter($key)
        {
                return function ($a, $b) use ($key)
                {
                        return strnatcmp($a[$key], $b[$key]);
                };
        }

        function push($id, $page, $url)
        {
                if (!$page OR ! $url)
                        return;

                $url = site_url($url);

                $this->breadcrumbs[$url] = array('id' => $id, 'page' => $page, 'href' => $url);
        }

        function unshift($id, $page, $url)
        {
                if (!$page OR ! $url)
                        return;

                if ($url != '#')
                {
                        $url = site_url($url);
                }
                array_unshift($this->breadcrumbs, array('id' => $id, 'page' => $page, 'href' => $url));
        }

        function show()
        {
                if ($this->breadcrumbs)
                {
                        $output = '<!--breadcrumbs-->' . "\n" . $this->breadcrumb_open . "\n";

                        usort($this->breadcrumbs, $this->array_sorter('id'));

                        foreach ($this->breadcrumbs as $key => $value)
                        {
                                $keys = array_keys($this->breadcrumbs);

                                if ('#' == $value['href'])
                                {
                                        $extr = '';
                                        if (end($keys) == $key)
                                        {
                                                foreach ($this->breadcrumb_el_last_open_extra as $k => $v)
                                                {
                                                        $extr .= ' ' . $k . '="' . $v . '" ';
                                                }
                                        }
                                        $output .= $this->breadcrumb_el_last_open . '<a' . $extr . '>' . $value['page'] . '</a>' . $this->breadcrumb_el_last_close . $this->breadcrumb_el_close . "\n";
                                }
                                elseif (reset($keys) == $key)
                                {
                                        $output .= $this->breadcrumb_el_open . anchor($value['href'], $this->breadcrumb_el_first . ' ' . $value['page'], $this->breadcrumb_el_first_extra) . $this->breadcrumb_el_close . "\n";
                                }
                                elseif (end($keys) == $key)
                                {
                                        $output .= $this->breadcrumb_el_last_open . anchor($value['href'], $value['page'], $this->breadcrumb_el_last_open_extra) . $this->breadcrumb_el_last_close . $this->breadcrumb_el_close . "\n";
                                }
                                else
                                {
                                        $output .= $this->breadcrumb_el_open . anchor($value['href'], $value['page'], $this->breadcrumb_el_open_extra) . $this->breadcrumb_el_close . "\n";
                                }
                        }

                        return $output . $this->breadcrumb_close . "\n" . '<!--End-breadcrumbs-->' . "\n";
                }

                return NULL;
        }

}
