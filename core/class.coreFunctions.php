<?php
/**
 * User: galiczmiklos
 * Date: 2013.01.04.
 * Time: 12:14
 */

/**
 * Alap funkciók amit minden osztály számára elérhetővé kell tenni
 */
class coreFunctions
{

    /**
     * Megtisztitja az egyszerű tartalmakat, textArea tisztítására NEM ALKALMAS!
     *
     * @param $variable
     * @return mixed
     */
    function cleanVar($variable)
    {
        $variable = htmlspecialchars(trim(stripcslashes(strip_tags($variable))));
        return $variable;
    }

    /**
     * textArea tisztítására alkalmas változó tisztító metódus
     * @param $var
     * @return string
     */
    function cleanTextField($var)
    {
        $var = strip_tags($var, '<br><b><string><ul><ol><li><a><i><em><pre><table><tbody><thead><tfoor><th><tr><td><p><div><img>');
        $var = htmlspecialchars($var, ENT_QUOTES);
        return $var;
    }

    /**
     *
     * Courtesy of an unknown source.
     * If this part of the code resembles yours and you can verify that Your're the author then the required information will be shown here!
     *
     * This bit of code generates a sample text from a larger string.
     * Ideal for intro or sample blocks.
     *
     * @param string $str source string
     * @param int $length
     * @param int $minword
     * @return string sample string
     */
    public function trimmer($str, $length, $minword = 3)
    {
        $str = strip_tags($str);
        $sub = '';
        $len = 0;

        foreach (explode(' ', $str) as $word) {
            $part = (($sub != '') ? ' ' : '') . $word;
            $sub .= $part;
            $len += strlen($part);

            if (strlen($word) > $minword && strlen($sub) >= $length) {
                break;
            }
        }

        return $sub . (($len < strlen($str)) ? '...' : '');
    }

    /**
     *
     * Courtesy of an unknown source.
     * If this part of the code resembles yours and you can verify that Your're the author then the required information will be shown here!
     *
     * This bit of code creates a slugged version of the input string ($str).
     *
     * @param string $str Input string
     * @param array $replace An array of special characters and their replacement
     * @param string $delimiter The delimiter to separate the words
     * @return string The slug version of the input string
     */
    public function slugger($str, $replace = array(), $delimiter = '-')
    {
        if (!empty($replace)) {
            $str = str_replace((array )$replace, ' ', $str);
        }

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        return $clean;
    }

    /**
     * 1000000 -> 1 000 000
     *
     * @param $num
     * @return mixed
     */
    function prettyNumbers($num)
    {
        $num = number_format($num, 0, ',', " ");
        $num = str_replace(' ', '&nbsp;', $num);
        return $num;
    }

    /**
     * Az útvonalakat tisztítja meg a kellemetlen dupla // jelektől
     *
     * @param $path
     * @return mixed
     */
    function cleanPath($path)
    {
        $path = $this->cleanVar($path);
        $path = str_replace('//', '/', $path);
        return $path;
    }
}
