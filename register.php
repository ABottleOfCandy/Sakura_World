<?php

    //本程序为Sakura_World Sakura(QQ:3559995539)原创
    //一切版权归Sakura所有 支持正版 请标明版权来源
    //官方群1035970548 邮箱admin@wdsj.games
    //Sakura_World||樱花世界
    //都是一行一行码出来的 支持一下up吧

    
    require('config/config.php');
    include('register.html');

    if($_POST){
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $email = $_POST['email'];
        $vaptcha = md5($_POST['vaptcha']);
     

        //头在这里


        //一级验证码验证
        if($vaptcha != NULL){
            

            //是否尝试撞库
            if($_COOKIE['vaptcha'] != '102'){

                //一级判断
                if($vaptcha == $_COOKIE['vaptcha']){


                    //声明数据库
                    
                    $link = mysqli_connect($sql_host,$sql_username,$sql_password,$sql_dbname,3306);

                    if(!$link){
                        echo '<script>alert("[ERROR CODE:201]数据库异常提醒 请及时联系管理员")</script>';
                        mysqli_close($link);
                        exit();
                        
                    }else{
                        
                        //++++==========程序在这里开始==========++++

                        $query = "SELECT * FROM".$sql_formname;

                        $result = mysqli_query($link,$query);


                        //声明query_state
                        //0 ->正常无同库值
                        //1 ->IP有相同
                        //2 ->用户名有相同
                        //3 -> email有相同

                        $query_state = 0;

                        //跑数据库

                        foreach($result as $result){
                            $sql_query_username = $result['username'];
                            $sql_query_email = $result['email'];
                            $sql_query_ip = $result['ip'];

                            if($_SERVER['REMOTE_ADDR']  == $sql_query_ip){
                                echo '<script> alert("[ERROR] CODE:301 您的IP已被注册,无法注册新账号,如有问题加群1035970548");</script>';
                                mysqli_close($link);
                                exit();
                            }

                            if($username == $sql_query_username){
                                echo '<script> alert("[ERROR] CODE:302 您输入的用户名已被注册,无法注册新账号,如有问题加群1035970548");</script>';
                                mysqli_close($link);
                                exit();
                            }

                            if($email == $sql_query_email){
                                echo '<script> alert("[ERROR] CODE:303 您输入的邮箱已被注册,无法注册新账号,如有问题加群1035970548");</script>';
                                mysqli_close($link);
                                exit();
                            }
                        }


                        //跑库完毕 无重复数据执行以下
                        $query = "INSERT INTO ".$sql_formname."(`id`, `username`, `password`, `email`, `state`, `ip`, `level`) VALUES (NULL, '".$username."', '".$password."', '".$email."','0','".$_SERVER['REMOTE_ADDR']."','0')";
                        $result = mysqli_query($link,$query);
                        echo '<script> alert("SUCCESSFULLY");</script>';
                        header('Location: '.$main_page.'under-review.html');


                        
    
                    }


                }else{
                    echo '<script> alert("[ERROR] CODE:102 验证码错误,10秒后重试");</script>';
                    setcookie('vaptcha','102',time()+10);
                    exit();

                }
                    
            }else{
                echo '<script> alert("[ERROR] CODE:103 10秒后重试");</script>';
                exit();

            }




        }else{
            echo '<script> alert("[ERROR] CODE:101 非法请求,验证码为空,您的IP已被记录");</script>';
            setcookie('vaptcha','102',time()+10);
            exit();
        }
    }
?>