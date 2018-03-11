<?php

namespace Potaka\BbcodeBundle\BbCode;

/**
 * @author potaka
 */
interface TextToHtmlInterface
{
    /**
     * Transform string to html escaped string with applied bb-code.
     *
     * @param string $text
     * @return string
     */
    public function getHtml(string $text) : string;
}
