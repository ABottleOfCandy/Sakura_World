<?php
    //本程序为Sakura_World Sakura(QQ:3559995539)原创
    //一切版权归Sakura所有 支持正版 请标明版权来源
    //官方群1035970548 邮箱admin@wdsj.games
    //Sakura_World||樱花世界
    //都是一行一行码出来的 支持一下up吧

    require('config/config.php');

    
    include('index.html');


    //0默认值 1未修正 
    if($_COOKIE['welcome'] == '1'){
        echo '<script>alert("验证成功 恭喜您成为尊贵的Sakura_World用户");</script>';
        setcookie('welcome','0',time()+999999);
    }

    if($_POST){
        $account = $_POST['account'];
        $password = $_POST['password'];
        $vaptcha = $_POST['vaptcha'];
        
        if($vaptcha != NULL){

       
            $cookie_vaptcha = $_COOKIE['vaptcha'];
    
             //还原加密
    
             $vaptcha = md5($vaptcha);
             $password = md5($password);
    
            //cookie对照验证是否为空
            if($cookie_vaptcha != NULL){
                
                if($cookie_vaptcha == '102'){
                    echo '<script>alert("验证码已过期 请10秒后重新请求");</script>';
                    exit();
                }
    
                //程序在这里开始
    
                if($vaptcha == $cookie_vaptcha){
    
                    //验证码正确 主程序在这里awa
                    
                    
    
                    //=========连接数据库=======
                    if($_COOKIE['login_state'] == '1'){
                        echo "<script>alert('您已登录 无需再登陆');</script>";
                        exit();
                    }else{
                        $link = mysqli_connect($sql_host,$sql_username,$sql_password,$sql_dbname,3306);
                    }
                    
                    
                    //数据库连接异常处理
                    if(!$link){
                        echo '<script>alert("[ERROR CODE:201]数据库异常提醒 请及时联系管理员")</script>';
                        mysqli_close($link);
                        exit();
                        
                    }else{
                        //数据库连接完成开始
    
                        $query = "SELECT * FROM ".$sql_formname;
                        $result = mysqli_query($link,$query);
                        $query_state = 0;
                        foreach($result as $result){
                            $sql_query_id = $result['id'];
                            $sql_query_username = $result['username'];
                            $sql_query_password = $result['password'];
                            $sql_query_email = $result['email'];
                            $sql_query_state = $result['state'];
    
                            //测试查看
                           /*  echo $sql_query_id .'<-id'.$sql_query_username.'<-username'.$sql_query_password.'<-password'.$sql_query_email.'<-email';
     */
    
    
                            //判断密码
                            if($account == $sql_query_email){
                                if($password == $sql_query_password and $sql_query_state == '1'){
                                    //登陆密码正确执行
                                    echo '<script>alert("登陆成功")</script>';
                                    setcookie('email',$account,time()+3600,'wdsj.games');
                                    setcookie('password',$password,time()+3600,'wdsj.games');
                                    setcookie('login_state','1',time()+3600,'wdsj.games');
                                    $query_state = 1; //1代表登陆成功
                                    mysqli_close($link);
                                    exit();
    
                                   
                                }


                                if($password == $sql_query_password and $sql_query_state == '0'){
                                    header('Location: '.$main_page.'under-review.html');
                                    echo '<script>alert("等待管理员验证")</script>';
                                    $query_state = 2; //2代表正在审核
                                    mysqli_close($link);
                                    exit();

                                }

                                if($password == $sql_query_password and $sql_query_state == '2'){
                                    echo '<script>alert("非法用户 您已被禁止登录")</script>';
                                    $query_state = 3; //3代表风控用户
                                    mysqli_close($link);
                                    exit();
                                    
                                }
                            }
    
    
                            //断联结束
                            
                        }
                        if($query_state == 0){
                            header('Location: '.$main_page.'login.php');
                            echo '<script>alert("密码错误 十秒后重试")</script>';

                        }
                        mysqli_close($link);
                        //cookie重置
                        setcookie('vaptcha','102',time()+10);
                    }
    
                    
    
                }else{
                    echo '<script>alert("[ERROR CODE:102]验证码错误 十秒钟后重试")</script>';
                    setcookie('vaptcha','102',time()+10);
                    mysqli_close($link);
                   
                    exit();
                }
    
            }else{
                echo '<script>alert("[ERROR CODE:101]非法请求 Cookie对照变量不存在 您的IP已被记录")</script>';
                mysqli_close($link);
                
                exit();
            }
    
    
        }else{
            echo '<script>alert("[ERROR CODE:101]非法请求 验证码为空 您的IP已被记录")</script>';
            mysqli_close($link);
            
            exit();
        }
    }
   
    //接收到的验证码是否为空
    
?>