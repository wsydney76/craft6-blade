<?php

use CraftCms\Cms\Twig\Extensions\ArrayTwigExtension;
use CraftCms\Cms\Twig\Extensions\CoreTwigExtension;
use CraftCms\Cms\Twig\Extensions\HtmlTwigExtension;
use CraftCms\Cms\Twig\Extensions\TextTwigExtension;

function c6b_bladeArrayExtension(): ArrayTwigExtension
{
    static $arrayExtension = null;
    return $arrayExtension ??= new ArrayTwigExtension();
}

function c6b_bladeCoreExtension(): CoreTwigExtension
{
    static $coreExtension = null;
    return $coreExtension ??= app(CoreTwigExtension::class);
}

function c6b_bladeHtmlExtension(): HtmlTwigExtension
{
    static $htmlExtension = null;
    return $htmlExtension ??= new HtmlTwigExtension();
}

function c6b_bladeTextExtension(): TextTwigExtension
{
    static $textExtension = null;
    return $textExtension ??= new TextTwigExtension();
}