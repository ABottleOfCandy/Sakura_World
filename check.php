<?php

    //本程序为Sakura_World Sakura(QQ:3559995539)原创
    //一切版权归Sakura所有 支持正版 请标明版权来源
    //官方群1035970548 邮箱admin@wdsj.games
    //Sakura_World||樱花世界
    //都是一行一行码出来的 支持一下up吧

    
    require('config/config.php');


    if($_GET){
        $token = $_GET['token'];
        if($token != NULL){
            


            //声明变量


            $link = mysqli_connect($sql_host,$sql_username,$sql_password,$sql_dbname,3306);
            
            if($link){


                //程序在这里开始
                
                $query = "SELECT * FROM ".$sql_formname;

                $result = mysqli_query($link,$query);

                $sql_state = 0;
                foreach($result as $result){
                    $sql_query_id = $result['id'];
                    $sql_query_ip = $result['ip'];
                    $sql_query_state = $result['state'];
                    
                    $secret = $secret_addr. $sql_query_id;  //哈希加密验证在这里 仔细思考你就知道什么意思啦~

                    $secret = md5($secret);


                    if($token == $secret){
                        if($sql_query_ip == $_SERVER['REMOTE_ADDR']){
                            if($sql_query_state == '0'){
                                $sql_state = 1;

                                $query = "UPDATE ".$sql_formname." SET state = 1 WHERE `id` = ". $sql_query_id;
        
                                $result = mysqli_query($link,$query);
        
                                if($result){
                                    echo "success";
                                    setcookie('welcome','1',time()+999999);
                                    header("Location: ".$main_page."login.php");
                                    mysqli_close($link);
                                    exit();
    
                                    
                                }else
                                    mysqli_error($result);
                            }else{
                                if($sql_query_state == '1'){
                                    echo '<script>alert("[ERROR CODE:303]您的帐号已验证")</script>';
                                    mysqli_close($link);
                                    exit();
                                }else{
                                    if($sql_query_state == '2'){
                                        echo '<script>alert("[ERROR CODE:303]您已被禁止注册 详情询问管理员")</script>';
                                        mysqli_close($link);
                                        exit();
                                    }
                                }
                            }

                        }else{
                            echo '<script>alert("[ERROR CODE:302]您的IP环境异常 请询问管理员")</script>';
                            mysqli_close($link);
                            exit();
                        }
                    }
                }
                if($sql_state == 0){
                    echo '<script>alert("[ERROR CODE:301]请求数据不存在 请订正")</script>';
                }

                mysqli_close($link);
                
            }else{
                echo '<script>alert("[ERROR CODE:201]数据库异常提醒 请及时联系管理员")</script>';
                exit();

            }


        }else{
            echo '<script>alert("[ERROR CODE:101]非法请求 您的IP已被记录.IP:'.$_SERVER['REMOTE_ADDR'].'")</script>';
            exit();
        }
    }
    
?>