<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 22/11/13
 * Time: 17:03
 * To change this template use File | Settings | File Templates.
 */
function file_get_contents_chunked($file,$chunk_size,$callback)
{
    try
    {
        $handle = fopen($file, "r");
        $i = 0;
        while (!feof($handle))
        {
            call_user_func_array($callback,array(fread($handle,$chunk_size),&$handle,$i));
            $i++;
        }

        fclose($handle);

    }
    catch(Exception $e)
    {
        trigger_error("file_get_contents_chunked::" . $e->getMessage(),E_USER_NOTICE);
        return false;
    }

    return true;
}

$file = '/home/kujaomega/Escritorio/parser.php.html';
//$contents = file_get_contents_chunked( $file, 5000000,  );
echo '<br> hola';
$hola = explode('<tr>', $contents, 1);
var_dump( $hola );