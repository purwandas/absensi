<?php

use App\Components\Helpers\AccessHelper;
use App\Models\System\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Vinkla\Hashids\Facades\Hashids;
use GuzzleHttp\Client;

/*
|--------------------------------------------------------------------------
| Global Helpers
|--------------------------------------------------------------------------
|
| You can call function name on any dimension: Controllers, Model, event Blade
|
*/

if(!function_exists('get_file_size')){
    function get_file_size($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');   

        return $size > 0 ? round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)] : 0;
    }
}

if(!function_exists('uniqueFileName')){
    function uniqueFileName($file = null)
    {
        if(!$file) return '';

        $original = $file->getClientOriginalName();

        $filename = pathinfo($original, PATHINFO_FILENAME);
        $filename .= ' (@'.Carbon::now()->format('YmdHis').')';
        $filename .= '.'.pathinfo($original, PATHINFO_EXTENSION);

        return $filename;
    }
}

if(!function_exists('access')){
    function access($key = '')
    {
        return AccessHelper::checkByKey($key);
    }
}

if(!function_exists('master')){
    function master()
    {
        return @\Auth::user()->role->is_master;
    }
}


if(!function_exists('homeRedirect')){
    function homeRedirect()
    {
        if(!@\Auth::user()) return config('acl.no_auth_redirect') ?? '/';

        $home = '';
        foreach(config('acl.home_priority') as $prior){
            if($home != '') continue;
            if(AccessHelper::checkByUrl($prior)) $home = $prior;
        }


        if($home == '') $home = 'unaccessible';
        // if($home == '') return abort(404);
        return $home;
    }
}

if(!function_exists('setting')){
    function setting($type = '')
    {
        return @Setting::where('type', $type)->first()->value;
    }
}

if(!function_exists('upload_file')){
    function upload_file($file, $prefix, $isPublic = true)
    {
        $folderPath = ($isPublic ? 'public/' : '') . $prefix;

        if(!Storage::exists($folderPath)) {
            // Storage::makeDirectory($folderPath, 0777, true);
            mkdir('storage/'.$prefix, 0755, true);
        }

        $fileName = $file->storeAs($folderPath, uniqueFileName($file));
        return asset(Storage::url($fileName));
    }
}

if(!function_exists('random_string')){
    function random_string($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
           $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}

if(!function_exists('migration_created_updated_by')){
    function migration_created_updated_by(&$table)
    {
        // CREATED BY & UPDATE BY
        $table->unsignedBigInteger('created_by')->nullable();
        $table->unsignedBigInteger('updated_by')->nullable();

        $table->foreign('created_by')->references('id')->on('users');
        $table->foreign('updated_by')->references('id')->on('users');
    }
}

if(!function_exists('encrypt_id')){
    function encrypt_id($id)
    {
        return Hashids::encode($id);
    }
}

if(!function_exists('decrypt_hash')){
    function decrypt_hash($hashText)
    {
        $decode = Hashids::decode($hashText);
        if(count($decode) == 0) abort(404);
        return $decode[0];
    }
}

if(!function_exists('whatsapp_status')){
    function whatsapp_status($token)
    {
        $url = 'http://carimangsa.test/whatsapp/broadcast-api/status';
        $client = new Client(['verify' => env('SSL_CHECK_DISABLED') ? false : true]);
        $clientParams = [];

        // SET BODY
        if(@$token) $clientParams['form_params']['token'] = $token;

        // try{
            $response = $client->request('POST', $url, $clientParams);
            $resultJson = json_decode($response->getBody()->getContents(), true);
        // }catch(\Exception $e){
        //     $resultJson['failed'] = true;
        //     $resultJson['failed_message'] = $e->getMessage();
        // }

        return $resultJson;
    }
}

if(!function_exists('whatsapp_send')){
    function whatsapp_send($params = [])
    {
        // $url = 'http://carimangsa.test/whatsapp/broadcast-api/send';
        $url = 'http://carimangsa.test/whatsapp/webhook';
        $client = new Client(['verify' => env('SSL_CHECK_DISABLED') ? false : true]);
        $clientParams = [];

        // SET BODY
        if(@$params) $clientParams['form_params'] = $params;

        $response = $client->request('POST', $url, $clientParams);
        $resultJson = json_decode($response->getBody()->getContents(), true);
       
        return $resultJson;
    }
}



if(!function_exists('getMacAddress')){
    function getMacAddress(){

        $mac = "Not Found";

        if(preg_match('/^Windows/i', getOperatingSystemName())){
            ob_start(); // Turn on output buffering
            system('ipconfig /all'); //Execute external program to display output
            $mycom = ob_get_contents(); // Capture the output into a variable
            ob_clean(); // Clean (erase) the output buffer

            $findme = "Physical";
            $pmac = strpos($mycom, $findme); // Find the position of Physical text
            $mac = substr($mycom, ($pmac+36), 17); // Get Physical Address
        }

        return $mac;
    }
}

if(!function_exists('getOperatingSystemName')){
    function getOperatingSystemName(){
        if ( isset( $_SERVER ) ) {
            $agent = $_SERVER['HTTP_USER_AGENT'] ;
        }
        else {
            global $HTTP_SERVER_VARS ;
            if ( isset( $HTTP_SERVER_VARS ) ) {
                $agent = $HTTP_SERVER_VARS['HTTP_USER_AGENT'] ;
            }
            else {
                global $HTTP_USER_AGENT ;
                $agent = $HTTP_USER_AGENT ;
            }
        }

        $ros[] = array('Windows XP', 'Windows XP');
        $ros[] = array('Windows NT 5.1|Windows NT5.1)', 'Windows XP');
        $ros[] = array('Windows 2000', 'Windows 2000');
        $ros[] = array('Windows NT 5.0', 'Windows 2000');
        $ros[] = array('Windows NT 4.0|WinNT4.0', 'Windows NT');
        $ros[] = array('Windows NT 5.2', 'Windows Server 2003');
        $ros[] = array('Windows NT 6.0', 'Windows Vista');
        $ros[] = array('Windows NT 7.0', 'Windows 7');
        $ros[] = array('Windows CE', 'Windows CE');
        $ros[] = array('(media center pc).([0-9]{1,2}\.[0-9]{1,2})', 'Windows Media Center');
        $ros[] = array('(win)([0-9]{1,2}\.[0-9x]{1,2})', 'Windows');
        $ros[] = array('(win)([0-9]{2})', 'Windows');
        $ros[] = array('(windows)([0-9x]{2})', 'Windows');
        $ros[] = array('Windows ME', 'Windows ME');
        $ros[] = array('Win 9x 4.90', 'Windows ME');
        $ros[] = array('Windows 98|Win98', 'Windows 98');
        $ros[] = array('Windows 95', 'Windows 95');
        $ros[] = array('(windows)([0-9]{1,2}\.[0-9]{1,2})', 'Windows');
        $ros[] = array('win32', 'Windows');
        $ros[] = array('(java)([0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,2})', 'Java');
        $ros[] = array('(Solaris)([0-9]{1,2}\.[0-9x]{1,2}){0,1}', 'Solaris');
        $ros[] = array('dos x86', 'DOS');
        $ros[] = array('unix', 'Unix');
        $ros[] = array('Mac OS X', 'Mac OS X');
        $ros[] = array('Mac_PowerPC', 'Macintosh PowerPC');
        $ros[] = array('(mac|Macintosh)', 'Mac OS');
        $ros[] = array('(sunos)([0-9]{1,2}\.[0-9]{1,2}){0,1}', 'SunOS');
        $ros[] = array('(beos)([0-9]{1,2}\.[0-9]{1,2}){0,1}', 'BeOS');
        $ros[] = array('(risc os)([0-9]{1,2}\.[0-9]{1,2})', 'RISC OS');
        $ros[] = array('os/2', 'OS/2');
        $ros[] = array('freebsd', 'FreeBSD');
        $ros[] = array('openbsd', 'OpenBSD');
        $ros[] = array('netbsd', 'NetBSD');
        $ros[] = array('irix', 'IRIX');
        $ros[] = array('plan9', 'Plan9');
        $ros[] = array('osf', 'OSF');
        $ros[] = array('aix', 'AIX');
        $ros[] = array('GNU Hurd', 'GNU Hurd');
        $ros[] = array('(fedora)', 'Linux - Fedora');
        $ros[] = array('(kubuntu)', 'Linux - Kubuntu');
        $ros[] = array('(ubuntu)', 'Linux - Ubuntu');
        $ros[] = array('(debian)', 'Linux - Debian');
        $ros[] = array('(CentOS)', 'Linux - CentOS');
        $ros[] = array('(Mandriva).([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?)', 'Linux - Mandriva');
        $ros[] = array('(SUSE).([0-9]{1,3}(\.[0-9]{1,3})?(\.[0-9]{1,3})?)', 'Linux - SUSE');
        $ros[] = array('(Dropline)', 'Linux - Slackware (Dropline GNOME)');
        $ros[] = array('(ASPLinux)', 'Linux - ASPLinux');
        $ros[] = array('(Red Hat)', 'Linux - Red Hat');
        $ros[] = array('(linux)', 'Linux');
        $ros[] = array('(amigaos)([0-9]{1,2}\.[0-9]{1,2})', 'AmigaOS');
        $ros[] = array('amiga-aweb', 'AmigaOS');
        $ros[] = array('amiga', 'Amiga');
        $ros[] = array('AvantGo', 'PalmOS');
        $ros[] = array('[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{1,3})', 'Linux');
        $ros[] = array('(webtv)/([0-9]{1,2}\.[0-9]{1,2})', 'WebTV');
        $ros[] = array('Dreamcast', 'Dreamcast OS');
        $ros[] = array('GetRight', 'Windows');
        $ros[] = array('go!zilla', 'Windows');
        $ros[] = array('gozilla', 'Windows');
        $ros[] = array('gulliver', 'Windows');
        $ros[] = array('ia archiver', 'Windows');
        $ros[] = array('NetPositive', 'Windows');
        $ros[] = array('mass downloader', 'Windows');
        $ros[] = array('microsoft', 'Windows');
        $ros[] = array('offline explorer', 'Windows');
        $ros[] = array('teleport', 'Windows');
        $ros[] = array('web downloader', 'Windows');
        $ros[] = array('webcapture', 'Windows');
        $ros[] = array('webcollage', 'Windows');
        $ros[] = array('webcopier', 'Windows');
        $ros[] = array('webstripper', 'Windows');
        $ros[] = array('webzip', 'Windows');
        $ros[] = array('wget', 'Windows');
        $ros[] = array('Java', 'Unknown');
        $ros[] = array('flashget', 'Windows');
        $ros[] = array('MS FrontPage', 'Windows');
        $ros[] = array('(msproxy)/([0-9]{1,2}.[0-9]{1,2})', 'Windows');
        $ros[] = array('(msie)([0-9]{1,2}.[0-9]{1,2})', 'Windows');
        $ros[] = array('libwww-perl', 'Unix');
        $ros[] = array('UP.Browser', 'Windows CE');
        $ros[] = array('NetAnts', 'Windows');

        $file = count ( $ros );
        $os = '';
        for ( $n=0 ; $n<$file ; $n++ ){
            if ( @preg_match('/'.$ros[$n][0].'/i' , $agent, $name)){
                $os = @$ros[$n][1].' '.@$name[2];
                break;
            }
        }

        return trim ( $os );
    }
}