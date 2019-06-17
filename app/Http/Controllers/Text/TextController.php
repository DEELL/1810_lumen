<?php

namespace App\Http\Controllers\Text;

use http\Env\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use GuzzleHttp\Client;

class TextController extends BaseController
{
    public  function curl(){
        print_r($_POST);

//        $data=file_get_contents('php://input'); //接受raw格式
//        print_r($data);
    }

    /**
     * 测试解密
     */
    public function curl2(){
        $data=file_get_contents('php://input'); //接受raw格式
        dump($data);
        $res=unserialize(base64_decode($data));
        dd($res);
//        print_r($_POST);
    }

    /**
     * 对称解密
     */
    public function curl3(){
        $key="password";
        $method="AES-128-CBC";//密码学方式
        $iv="adminadminadmin1";//非 NULL 的初始化向量
        $data=file_get_contents('php://input'); //接受raw格式  bas64未解密
        $data_post=base64_decode(file_get_contents('php://input')); //接受raw格式  bas64解密
        dump($data);
        dump($data_post);
        $app=openssl_decrypt($data_post,$method,$key,OPENSSL_RAW_DATA,$iv);//openssl_decrypt解密 $data_post 待加密的明文信息数据  解密和加密第一个值不一样其他必须一致
        dump($app);

    }

    /**
     * 接受非对称加密的数据
     */
    public function asymm(){
        $re=$_GET['url']; //路由拼接的数据
        dump($re);
        $re=base64_decode($re);//数据bas64解密
        dump($re);
        $data=file_get_contents('php://input');
        dd($data);
        $asymm=openssl_pkey_get_public("file://".storage_path('rsa_public_key.pem'));//从证书中解析公钥，以供使用
        dump($asymm);
        $result = openssl_verify($data,$re,$asymm);//验证签名
        dd($result);
    }

//    私钥加密  公钥解密
    public function personal(){
        $key=openssl_pkey_get_public("file://".storage_path('rsa_public_key.pem'));//从证书中解析公钥，以供使用
        $data=file_get_contents('php://input'); //接收数据
        $as=base64_decode($data);
        $crypted='';
        openssl_public_decrypt ($as,$crypted ,$key);//使用公钥解密数据
//        dump($data);
//        var_dump($as);
        dd(json_decode($crypted,true));

    }

    public function exerci(){
        $key="password";
        $method="AES-128-CBC";//密码学方式
        $iv="adminadminadmin1";//非 NULL 的初始化向量
        $re=$_GET['url']; //路由拼接的数据
        $data=file_get_contents('php://input');
        $data_post=openssl_decrypt($data,$method,$key,OPENSSL_RAW_DATA,$iv);
        dump($data_post);
        $asymm=openssl_pkey_get_public("file://".storage_path('rsa_public_key.pem'));//从证书中解析公钥，以供使用
        dump($asymm);
        $result = openssl_verify($data_post,$re,$asymm);//验证签名
        if($result==1){
            echo "验证签名成功，";
            $key="passwords";
            $method="AES-128-CBC";//密码学方式
            $iv="1212121212121212";//非 NULL 的初始化向量
            $a=openssl_get_privatekey("file://".storage_path('rsa_private_key.pem')); //获取秘钥
            openssl_sign($data_post,$exer0,$a);//生成签名
            $exer=base64_encode($exer0);
            $url="http://zhb.1810.com/syntony?url=".urlencode($exer);//签名拼接到路由  发送到服务端
            $app=openssl_encrypt($data_post,$method,$key,OPENSSL_RAW_DATA,$iv);// 对称加密
            $clinet= new Client();//实例化 Guzzle
//        Guzzle 发送
            $response=$clinet->request("POST",$url,[
                'body'=>$app
            ]);
            echo $response->getBody();
        }else{
            echo"对不齐，签名验证失败
            ";
        }
    }

    public function gateway()
    {
      $data=$_POST;
      $info=base64_decode($data['sign']);
        $data['biz_content']=json_decode($data['biz_content'],true);
      unset($data['sign']);
      $str0='';
      dd($data);
      foreach ($data as $k=>$v){
          $str0.=$k.'='.$v.'$';
      }
      dump($str0);
      $str=rtrim($str0,'&');
      dd($str);
       $pub= openssl_pkey_get_public("file://".storage_path("res_public_key.pem"));
      $a=openssl_verify($str,$info,$pub);
      dd($a);
    }


    public function reg(Request $request)
    {
        $data=$request->input();
        dd($data);
    }
}
