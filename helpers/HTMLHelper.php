<?php

/**
 * Created by PhpStorm.
 * User: Chrille
 * Date: 2014-10-09
 * Time: 14:48
 */
class HTMLHelper
{

    /**
     * @param $head string code to put into the head of the html code
     * @param $body string code to put into the body of the html code
     * @return string spliced with the input parameters
     */
    public static function spliceBaseHTML($head, $body)
    {
        $html = "
        <!doctype html>
        <html>
            <head>
                {$head}
                <meta charset='UTF-8'>
            </head>
            <body>
                <ul>
                    <li>
                        <a href='?/'>Home</a>
                    </li>
                    <li>
                        <a href='?/login'>Login</a>
                    </li>
                    <li>
                        <a href='?/logout'>Logout</a>
                    </li>
                    <li>
                        <a href='?/register'>Register</a>
                    </li>
                    <li>
                        <a href='?/quizes'>Quizes</a>
                    </li>
                </ul>
                {$body}
            </body>
        </html>
        ";
        return $html;
    }
}