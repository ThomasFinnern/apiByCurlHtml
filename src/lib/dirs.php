<?php

namespace Finnern\apiByCurlHtml\src\lib;

class dirs
{
    public static function joinDirPath ($one, $other, $normalize = true) {

        # normalize
        if($normalize) {
            $one = str_replace('/', DIRECTORY_SEPARATOR, $one);
            $one = str_replace('\\', DIRECTORY_SEPARATOR, $one);
            $other = str_replace('/', DIRECTORY_SEPARATOR, $other);
            $other = str_replace('\\', DIRECTORY_SEPARATOR, $other);
        }

        # remove leading/trailing dir separators
        if(!empty($one) && substr($one, -1)==DIRECTORY_SEPARATOR) $one = substr($one, 0, -1);
        if(!empty($other) && substr($other, 0, 1)==DIRECTORY_SEPARATOR) $other = substr($other, 1);

        # return combined path
        if(empty($one)) {
            return $other;
        } elseif(empty($other)) {
            return $one;
        } else {
            return $one.DIRECTORY_SEPARATOR.$other;
        }

    }

}