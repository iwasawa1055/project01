<?php

App::uses('AppFile', 'Lib');
/*
* ajaxでやっていた処理を、クラス化 => 実行するタイミングをTRADE設定後に変更してみる　
* todo 暫定　あとで整理必要
*
*/
class AppImageSns
{
    //public $width = '1528'; 
    //public $height = '800';

    public function image_facebook($_image_url)
    {
        //* 800 * 800 jpeg from img_server
        if (empty($_image_url)) {
            //* error info
            new AppInternalCritical('Error : no request data  image_url', $code = 500);
            return false;
        }
        $image_url = $_image_url;

        //* image src
        /*
        * 開発srvから画像検証srvへ接続するには以下必要
        * ゲートウェイがポートフォワーディングしている関係の為
        */
        $patterns = [];
        $patterns[0] = '/dev-image.minikura.com:10080/';
        $patterns[1] = '/dev-image.minikura.com:10443/';
        $patterns[2] = '/stag-image.minikura.com:10080/';
        $patterns[3] = '/stag-image.minikura.com:10443/';
        $replacements = [];
        $replacements[0] = 'dev-image.minikura.lan';
        $replacements[1] = 'dev-image.minikura.lan';
        $replacements[2] = 'stag-image.minikura.lan';
        $replacements[3] = 'stag-image.minikura.lan';
        $replace_image_url = preg_replace($patterns, $replacements, $image_url);
        //* create
        $get_image = imagecreatefromjpeg($replace_image_url);
        if ($get_image === false) {
            //*  error
            new AppInternalCritical('Error : not create image_resouce_id from  image_url', $code = 500);
            return  false;
        } else {
            //* for upload, fine_name
           $image_url_data = explode('/', $image_url);
           $replace_image_file = preg_replace('/\.jpg/', '_fb.png', $image_url_data[6]);
           $replace_image_file = explode('?', $replace_image_file);

            //* recommend for og:image  (横:縦,1.91:1) 
            $width = '1528'; 
            $height = '800';
            $create_image = imagecreatetruecolor($width, $height);
            $background = imagecolorallocate($create_image, 0, 0, 0);
            //* 背景を透明に
            imagecolortransparent($create_image, $background);
            
            //* $get_imageの配置position_x  =  (1528 - 800) / 2 , position_y=0
            $position_x = ($width - $height) / 2 ;
            $position_y = 0;
            imagecopy($create_image, $get_image, $position_x, $position_y, 0, 0, 800, 800);
            //* create
            imagepng($create_image, APP  . 'tmp' . DS  . $replace_image_file[0]);

            /*
            * file upload to drvsrv
            */

            $fileObject  = new AppFile();
            $file_upload_flag = $fileObject->upload(
                $host = Configure::read('api.strage.host'), 
                $user = Configure::read('api.strage.ssh.username'), 
                $public_key = Configure::read('api.strage.ssh.rsa.id_rsa_public'), 
                $id_rsa = Configure::read('api.strage.ssh.rsa.id_rsa'), 
                $image_src = APP  . 'tmp' . DS  . $replace_image_file[0],
                $upload_file = Configure::read('api.strage.file_dir') . $image_url_data[4] . DS . $image_url_data[5] . DS . $replace_image_file[0] 
            );

            //* メモリから開放
            imagedestroy($create_image);
            //* 作成ファイルを消す
            unlink(APP  . 'tmp' . DS . $replace_image_file[0]);
            return true;
        }

    }
}
