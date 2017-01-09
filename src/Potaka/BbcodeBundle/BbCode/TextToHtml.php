<?php

namespace Potaka\BbcodeBundle\BbCode;

use Potaka\BbCode\BbCode;
use Potaka\BbCode\Tokenizer\Tokenizer;

/**
 * Description of TextToHtml
 *
 * @author po_taka <angel.koilov@gmail.com>
 */
class TextToHtml
{
    private $bbCode;
    private $tokenizer;

    public function __construct(BbCode $bbCode, Tokenizer $tokenizer)
    {
        $this->bbCode = $bbCode;
        $this->tokenizer = $tokenizer;
    }

    /**
     * Transform string to html using bb code.
     * You must escape the html!
     *
     * @param string $text
     * @return string
     */
    public function getHtml(string $text) : string
    {
        $tokenized = $this->tokenizer->tokenize($text);
        $html = $this->bbCode->format($tokenized);
        return $html;
    }
}
