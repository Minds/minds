<?php

/**
 * Elgg translation browser.
 *
 * @package translationbrowser
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Mariusz Bulkowski
 * @author v2 Pedro Prez
 * @copyright 2009
 * @link http://www.pedroprez.com.ar/
 * 
 * @author v2 Renato Cerceau
 * @copyright 2011  (Upgrade to elgg 1.8)
 */


// Safety first
action_gatekeeper();

$session_translationbrowser = get_input('session_translate');
$data = $_SESSION['translationbrowser'][$session_translationbrowser];

$file_to_trans = $data->file_to_trans;
$lang_code_to_trans = $data->lang_code_to_trans;
$lang_name_to_trans = $data->lang_name_to_trans;
$type_export = get_input('export');

if(is_array($type_export)) $type_export = $type_export[0];

$new_trans = get_input('words');


if(empty($new_trans))
{
    register_error(elgg_echo("translationbrowser:error"));
    //forward("pg/translationbrowser/translate/{$session_translationbrowser}");
    forward("translationbrowser/translate/{$session_translationbrowser}");
}
else
{
    $_SESSION['translationbrowser'][$session_translationbrowser]->new_trans = $new_trans;
}

$c = 0;
$i = 0;
$values = array_values($new_trans);
$size_values = sizeof($values);

while($i != $size_values)
{
    if(empty($values[$i]))
    {
        $c++;
    }
    $i++;
}

if($c == $size_values)
{
    register_error(elgg_echo("translationbrowser:emptyfields"));
    //forward("pg/translationbrowser/translate/{$session_translationbrowser}");
    forward("translationbrowser/translate/{$session_translationbrowser}");
}

//begin traduction ;)
if($type_export == 'update')
{
    $folder_lang = dirname("{$file_to_trans}");

    if(!is_writable($folder_lang))
    {
        if(!@chmod($folder_lang, 0755))
        {
            register_error(elgg_echo("translationbrowser:problem:permiss"));
            //forward("pg/translationbrowser/translate/{$session_translationbrowser}");
            forward("translationbrowser/translate/{$session_translationbrowser}");
        }
    }

    $file_to_trans_path = trim("{$file_to_trans}");

    if (file_exists($file_to_trans_path))
    {
        if(is_writable($file_to_trans_path))
        {
            $back_filename = dirname($file_to_trans) . "/" .
                             basename($file_to_trans) . "." . date('YmdHis');
            $file_to_rename_path = trim("{$back_filename}");
            if(!@rename($file_to_trans_path, $file_to_rename_path))
            {
                register_error(elgg_echo("translationbrowser:error"));
                //forward("pg/translationbrowser/translate/{$session_translationbrowser}");
                forward("translationbrowser/translate/{$session_translationbrowser}");
            }
        }
    }

}

$content = "<?php" . PHP_EOL;
$content .=  PHP_EOL;
$content .= '// '. elgg_echo("translationbrowser:generatedby");
$content .= "  " . date('Ymd-h:i:s A');
$content .=  PHP_EOL;
$content .=  PHP_EOL;
$content .= "$" . "{$lang_name_to_trans} = array( " . PHP_EOL;

// remember last key
end($new_trans);
$last_key = key($new_trans);


foreach ($new_trans as $key => $word)
{
    if(empty($word))
    {
        continue;
    }
    $word = translationbrowser_clean_text($word);

    $line = "\t '{$key}'  =>  \"{$word}\"";

    if ($last_key != $key)
    {
        $line .=" , " ;
    }
    $content .= "$line" . PHP_EOL;
}

$content .= "); " . PHP_EOL;
$content .= PHP_EOL;

// add example add_translation("de",$german);
$content .= "add_translation('{$lang_code_to_trans}', ";
$content .= "$" . "{$lang_name_to_trans}); " . PHP_EOL;
$content .=  PHP_EOL;
$content .= "?>";

if($type_export == 'update')
{
    //Generamos o actualizamos el archivo
    if(!$file = fopen($file_to_trans_path,"w"))
    {
        register_error(elgg_echo("translationbrowser:error:filecreate"));
        //forward("pg/translationbrowser/translate/{$session_translationbrowser}");
        forward("translationbrowser/translate/{$session_translationbrowser}");
    }

    fwrite($file,$content);
    fclose($file);

    //Clean Session
    unset($_SESSION['translationbrowser']);

    system_message(elgg_echo("translationbrowser:success"));
    //forward("pg/translationbrowser/");
    forward("translationbrowser/");
}
else
{
    //Clean Session
    //unset($_SESSION['translationbrowser']);

    //Generamos el archivo para descarga nomas
    $file_name = "{$lang_code_to_trans}.php";
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"$file_name\"" . PHP_EOL);
    echo $content;
}

exit;
?>