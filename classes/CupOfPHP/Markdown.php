<?php

namespace CupOfPHP;

/**
 * Simple implementation of Markdown for formatting user input.
 *
 * @package  CupOfPHP
 * @author   Jordan Newland <github@jenewland.me.uk>
 * @license  All Rights Reserved
 * @link     https://github.com/jenewland1999/
 */
class Markdown
{
    private $markdown;

    public function __construct($markdown)
    {
        $this->markdown = $markdown;
    }

    public function toHtml()
    {
        // Convert $this->markdown to HTML
        $text = htmlspecialchars($this->markdown, ENT_QUOTES, 'UTF-8');

        // Convert Windows (\r\n) to Unix (\n)
        $text = str_replace("\r\n", "\n", $text);

        // Convert Macintosh (\r) to Unix (\n)
        $text = str_replace("\r", "\n", $text);

        // Paragraphs
        $text = '<p>' . str_replace("\n\n", '</p><p>', $text) . '</p>';

        // Line Breaks
        $text = str_replace("\n", '<br>', $text);

        // Markdown - Strong (bold) - __text__ or **text**
        $text = preg_replace('/__(.+?)__/s', '<strong>$1</strong>', $text);
        $text = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $text);

        // Markdown - Emphasis (italic) - _text_ or *text*
        $text = preg_replace('/_([^_]+)_/', '<em>$1</em>', $text);
        $text = preg_replace('/\*([^\*]+)\*/', '<em>$1</em>', $text);

        // Markdown - Deleted (strikethrough) - ~text~ or ~~text~~
        $text = preg_replace('/\~([^_]+)\~/', '<del>$1</del>', $text);
        $text = preg_replace('/\~\~(.+?)\~\~/s', '<del>$1</del>', $text);

        // Markdown - Images - ![Img alt text](Img SRC)
        $text = preg_replace(
            '/\!\[([^\]]+)]\(([-a-z0-9._~:\/?#@!$&\'()*+,;=%]+)\)/i',
            '<img src="$2" alt="$1" style="display: block;"/>',
            $text
        );

        // Markdown - Links - [linked text](link URL)
        $text = preg_replace('/\[([^\]]+)]\(([-a-z0-9._~:\/?#@!$&\'()*+,;=%]+)\)/i', '<a href="$2">$1</a>', $text);

        return $text;
    }
}
