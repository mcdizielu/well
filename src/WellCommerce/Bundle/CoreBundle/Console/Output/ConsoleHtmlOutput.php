<?php

namespace WellCommerce\Bundle\CoreBundle\Console\Output;

/**
 * Class ConsoleHtmlOutput
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ConsoleHtmlOutput
{
    public function write($message)
    {
        $messages = explode(PHP_EOL, $message);
        foreach ($messages as $message) {
            if (strlen($message)) {
                echo '<span style="color: #D7D7D7;font: 11px Courier New;line-height: 1.6em;">' . $message . '</span><br />';
            }
        }
        ob_flush();
        flush();
    }
}
