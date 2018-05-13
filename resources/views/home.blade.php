<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <!--
    Customize this policy to fit your own app's needs. For more guidance, see:
        https://github.com/apache/cordova-plugin-whitelist/blob/master/README.md#content-security-policy
    Some notes:
        * gap: is required only on iOS (when using UIWebView) and is needed for JS->native communication
        * https://ssl.gstatic.com is required only on Android and is needed for TalkBack to function properly
        * Disables use of inline scripts in order to mitigate risk of XSS vulnerabilities. To change this:
            * Enable inline JS: add 'unsafe-inline' to default-src
    -->
    <meta http-equiv="Content-Security-Policy" content="default-src * 'self' 'unsafe-inline' 'unsafe-eval' data: gap: content:">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="theme-color" content="#2196f3">
    <meta name="format-detection" content="telephone=no">
    <meta name="msapplication-tap-highlight" content="no">
    <title>订票系统</title>

    <link rel="stylesheet" href="https://cdn.bootcss.com/framework7/2.2.5/css/framework7.min.css">
    <link rel="stylesheet" href="css/icons.css">
    <link rel="stylesheet" href="css/app.css">
</head>
<body>
<div id="app">
    <!-- Status bar overlay for fullscreen mode-->
    <div class="statusbar"></div>
    <!-- Left panel with reveal effect when hidden -->
    <div class="panel panel-left panel-reveal">
        <div class="view view-left">
            <div class="page">
                <div class="navbar">
                    <div class="navbar-inner sliding">
                        <div class="title">菜单</div>
                    </div>
                </div>
                <div class="page-content">
                    <div class="block-title">系统导航</div>
                    <div class="list links-list">
                        <ul>
                            <li>
                                <a href="/leftTicket/" data-view=".view-main" data-ignore-cache="true" class="panel-close">车次查询</a>
                            </li>
                            <li>
                                <a href="/orders/" data-view=".view-main" data-ignore-cache="true" class="panel-close">我的订单</a>
                            </li>
                            <li>
                                <a href="/account/" data-view=".view-main" data-ignore-cache="true" class="panel-close">我的账户</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="view view-main ios-edges">
        <!-- Page, data-name contains page name which can be used in callbacks -->
        <div class="page" data-name="home">
            <!-- Top Navbar -->
            <div class="navbar">
                <div class="navbar-inner">
                    <div class="left">
                        <a href="#" class="link icon-only panel-open" data-panel="left">
                            <i class="icon f7-icons ios-only">menu</i>
                            <i class="icon material-icons md-only">menu</i>
                        </a>
                    </div>
                    <div class="title sliding">订票系统</div>
                </div>
            </div>
            <!-- Toolbar-->
            <div class="toolbar">
                <div class="toolbar-inner">
                    <a href="{{ '/account/' }}" class="link">{{ Auth::check() ? Auth::user()->name : "" }} 的账户</a>
                    <a href="{{ '/logout' }}" class="link">注销</a>
                </div>
            </div>
            <!-- Scrollable page content-->
            <div class="page-content">
                <div class="block block-strong">
                    <p>Hi, {{ Auth::check() ? Auth::user()->name : "" }} @ {{ Auth::check() ? Auth::user()->college : "" }}</p>

                    <p>欢迎使用订票系统！</p>

                    <p>请使用左侧导航来进行您需要的操作。</p>
                </div>

            </div>
        </div>
    </div>

    <div class="login-screen" id="my-login-screen">
        <div class="view">
            <div class="page">
                <div class="page-content login-screen-content">
                    <div class="login-screen-title">登录</div>
                    <div class="list">
                        <ul>
                            <li class="item-content item-input">
                                <div class="item-inner">
                                    <div class="item-title item-label">用户名</div>
                                    <div class="item-input-wrap">
                                        <input type="text" name="username" placeholder="Username">
                                    </div>
                                </div>
                            </li>
                            <li class="item-content item-input">
                                <div class="item-inner">
                                    <div class="item-title item-label">密码</div>
                                    <div class="item-input-wrap">
                                        <input type="password" name="password" placeholder="Password">
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="list">
                        <ul>
                            <li>
                                <a href="#" class="item-link list-button login-button">登录</a>
                            </li>
                            <li>
                                <a href="/jaccount/" class="item-link list-button">JAccount登录</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- Cordova -->
<!--
<script src="cordova.js"></script>
-->

<!-- Framework7 library -->
<script src="https://cdn.bootcss.com/framework7/2.2.5/js/framework7.min.js"></script>

<!-- App routes -->
<script src="js/routes.js"></script>

<!-- Your custom app scripts -->
<script src="js/app.js"></script>

</body>
</html>
