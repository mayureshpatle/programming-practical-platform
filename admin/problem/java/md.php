<?php
    require '../../../libs/michelf-markdown/vendor/autoload.php';

    use Michelf\MarkdownExtra;

    function md_format($text)
    {
        return MarkdownExtra::defaultTransform($text);
    }
?>


