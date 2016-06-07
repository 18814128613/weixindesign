<?php
    $code = $_GET["code"];
    $userinfo = getUserInfo($code);
    function getUserInfo($code)
    {
        $appid = "wx86b797cf63c262b3";
        $appsecret = "b102b27a5564a19ae268684f19566206";
        $access_token = "";

        $access_token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
        $access_token_json = https_request($access_token_url);
        $access_token_array = json_decode($access_token_json, true);
        $access_token = $access_token_array['access_token'];
        $openid = $access_token_array['openid'];

        $userinfo_url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
        $userinfo_json = https_request($userinfo_url);
        $userinfo_array = json_decode($userinfo_json, true);
        return $userinfo_array;
    }

    function https_request($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return 'ERROR' . curl_error($curl);
        }
        curl_close($curl);
        return $data;
    }


    $weixinnumber = $userinfo["openid"];
    if (isset($weixinnumber)) {
        ?>
        <html>
        <script type="text/javascript" src="http://1.479258585.applinzi.com/OAuth2.0/js/jquery-2.1.4.min.js"></script>
        <link rel="stylesheet" type="text/css" href="styles.css" />
        <body>
        <div id="carbonForm">
            <h1>Bind</h1>
            <form action="login_deal.php" method="post" id="signupForm">
                <div class="fieldContainer">

                    <div class="formRow">
                        <div class="label">
                            <label for="name">学号 :</label>
                        </div>

                        <div class="field">
                            <input type="text" id="user" name="user" />
                        </div>
                    </div>
                    <div class="formRow">
                        <div class="label">
                            <label for="pass">密码 :</label>
                        </div>

                        <div class="field">
                            <input type="password" name="pass" id="pass" />
                        </div>
                    </div>
                    <input type="hidden" id="weixinnumber" name="weixinnumber" value="<?php echo $weixinnumber;?>">
                </div> <!-- Closing fieldContainer -->

                <div class="signupButton">
                    <input type="submit" name="submit" id="submit" />
                </div>

            </form>

        </div>
        <h2 id="footer">Login in MySise</h2>
        </body>
  </html>
    <?php
    } else {
        echo "<script>alert('访问登录错误');</script>";
    }

?>