<?php

namespace Potaka\BbcodeBundle\BbCode;

use Potaka\BbCode\BbCode;
use Potaka\BbCode\Tokenizer\Tokenizer;

/**
 * Escape string with htmlspecialchars and do the bbcode
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class TextToHtml implements TextToHtmlInterface
{
    /**
     * @var BbCode
     */
    private $bbCode;

    /**
     * @var Tokenizer
     */
    private $tokenizer;

    public function __construct(BbCode $bbCode, Tokenizer $tokenizer)
    {
        $this->bbCode = $bbCode;
        $this->tokenizer = $tokenizer;
    }

    /**
     * Transform string to html escaped string with applied bb-code.
     *
     * @param string $text
     * @return string
     */
    public function getHtml(string $text) : string
    {
        $textAsHtml = htmlspecialchars($text, ENT_QUOTES);
        $textWithNewLines = nl2br($textAsHtml);

        $tokenized = $this->tokenizer->tokenize($textWithNewLines);
        $html = $this->bbCode->format($tokenized);

        return $html;
    }
}
