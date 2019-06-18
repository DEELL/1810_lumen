<?php

namespace App\Http\Controllers\User;
use App\Http\Model\User;
use Illuminate\Http\Request;

use Laravel\Lumen\Routing\Controller as BaseController;

class UserController extends BaseController
{
    /**
     * 注册  Postman测试  域名zhb.lumen.com
     */
    public function reg(Request $request){
        header('Access-Control-Allow-Origin:*');
//        账号
        $data=$request->input();
//        判断确认密码和密码是否一致
        if($data['password']!=$data['password_confirm']){
            var_dump('密码和确认密码必须一致');die;
        }else{
//            添加入库
            $mml=[
                'u_name'=>$data['accountBox'],
                'u_email'=>$data['email'],
                'u_password'=>$data['password']
            ];
            $res=User::insert($mml);
            if($res==true){
               $res=[
                   'error'=>1,
                    'msg'=>'注册成功'
               ];
            }else{
                $res=[
                    'error'=>1,
                    'msg'=>'很遗憾，失败了'
                ];
            }
            return json_encode($res);
        }
    }
    /**
     * 登录 Postman测试 域名zhb.lumen.com
     * @param Request $request
     */
    public  function login(Request $request){
//        账号
        $data=$request->input();
//        密码
//        $password=$request->input('password');
//            先去数据查询是否有这个账号
            $info=User::where(['u_name'=>$data['u_name']])->first();
            if($info!=null){
//                如果不是空 在查一下密码是否正确
                $llp=User::where(['u_password'=>$data['u_password']])->first();
                if($llp!=null){
                    $res=[
                        'error'=>1,
                        'msg'  =>'登录成功了，高兴不'
                    ];
//                    没有查到密码
                }else{

                    $res=[
                        'error'=>'2',
                        'msg'  =>'密码账户错误'
                    ];
                }
                return json_encode($res);
//                没有查到账号
            }else{
                $res=[
                    'error'=>'2',
                    'msg'  =>'密码账户错误'
                ];
            }
            return json_encode($res);
    }
    /**修改密码 Postman测试 zhb.lumen.com
     * @param Request $request
     */
    public function amend(Request $request){
        //        账号
        $name=$request->input('name');
//        密码
        $password=$request->input('password');
//        修改完的密码
        $password1=$request->input('password1');
//        账号查询数据库
        $data=User::where('u_name',$name)->first();
//        判断查询出来的账号为不为空
        if($data!=null){
//            查询密码
            $llp=User::where('u_password',$password)->first();
//            判断查询出来的密码是否为空
            if($llp!=null){
//                根据账号修改密码
                $mmo=User::where('u_name',$name)->update(['u_password'=>$password1]);
                if($mmo){
                    var_dump('修改成功');
                }else{
                    var_dump('不能和近期的密码一致');
                }
//                查出来的密码为空
            }else{
                var_dump('密码账户错误');
            }
//            查询出来的账号为空
        }else{
            var_dump('密码账户错误');
        }

    }
    /**
     * 天气 Postman测试 域名zhb.lumen.com
     * @param Request $request
     * @return string
     */
    public function weather(Request $request){
//        获取查询天气的城市
        $city=$request->input('city');
//        调用天气接口   K780
        $url="http://api.k780.com:88/?app=weather.future&weaid={$city}&&appkey=10003&sign=b59bc3ef6191eb9f747dd4e83c99f2a4&format=json";
//        get请求
        $data=file_get_contents($url);
//        对象转数组
        $ppl=json_decode($data ,true);
//        如果success为0
        if($ppl['success']==0){
            var_dump('请输入要查询天气的城市');
//            success不为0
        }else{
//            定义一个空的变量
            $msg='';
//            foreach get请求返回回来的数组
            foreach($ppl['result'] as $k=>$v){
//                想要的数据 拼接
              $msg.='日期：'.$v['days'].'，星期：'.$v['week'].'，城市：'.$v['citynm'].'，当日温度区间：'.$v['temperature'].'，天气：'.$v['weather'].'，风向：'.$v['wind'].'，风力:'.$v['winp']."<br>";
            }
            return $msg;
        }
    }

}
