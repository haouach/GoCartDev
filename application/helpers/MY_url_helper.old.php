<?php
function show_404()
{
    header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
    $class = new GoCart\Controller\Page();
    $class->show404();
}

function theme_url($uri)
{
    return \CI::config()->base_url('gocart/themes/'.config_item('theme').'/'.$uri);
}

function theme_path(){
    return 'gocart/themes/'.config_item('theme').'/';
}

//to generate an image tag, set tag to true. you can also put a string in tag to generate the alt tag
function theme_img($uri, $tag=false)
{
    if($tag)
    {
        return '<img src="'.theme_url('assets/img/'.$uri).'" alt="'.$tag.'">';
    }
    else
    {
        return theme_url('assets/img/'.$uri);
    }
}

function theme_js($uri, $tag=false)
{
    if($tag)
    {
        return '<script type="text/javascript" src="'.theme_url('assets/js/'.$uri).'"></script>';
    }
    else
    {
        return theme_url('assets/js/'.$uri);
    }
}

//you can fill the tag field in to spit out a link tag, setting tag to a string will fill in the media attribute
function theme_css($uri, $tag=false)
{
    if($tag)
    {
        $media=false;
        if(is_string($tag))
        {
            $media = 'media="'.$tag.'"';
        }
        return '<link href="'.theme_url('assets/css/'.$uri).'" type="text/css" rel="stylesheet" '.$media.'/>';
    }
    
    return theme_url('assets/css/'.$uri);
}

function module_url($module, $uri)
{
    return \CI::config()->base_url('gocart/modules/'.$module.'/'.$uri);
}

//to generate an image tag, set tag to true. you can also put a string in tag to generate the alt tag
function module_img($module, $uri, $tag=false)
{
    if($tag)
    {
        return '<img src="'.module_url($module, 'assets/img/'.$uri).'" alt="'.$tag.'">';
    }
    else
    {
        return module_url($module, 'assets/img/'.$uri);
    }
}

function module_js($module, $uri, $tag=false)
{
    if($tag)
    {
        return '<script type="text/javascript" src="'.module_url($module, 'assets/js/'.$uri).'"></script>';
    }
    else
    {
        return module_url($module, 'assets/js/'.$uri);
    }
}

//you can fill the tag field in to spit out a link tag, setting tag to a string will fill in the media attribute
function module_css($module, $uri, $tag=false)
{
    if($tag)
    {
        $media=false;
        if(is_string($tag))
        {
            $media = 'media="'.$tag.'"';
        }
        return '<link href="'.module_url($module, 'assets/css/'.$uri).'" type="text/css" rel="stylesheet" '.$media.'/>';
    }
    
    return module_url($module, 'assets/css/'.$uri);
}