<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="naver-site-verification" content="8bce253ce1271e2eaa22bd34b508b72cc60044a5"/>
    @section('meta')
        <meta name="description" content="오직 공연 예술인을 위한 크라우드 펀딩"/>
    @show

    @section('title')
        <title>크라우드티켓</title>
    @show
    <link rel="shortcut icon" href="{{ asset('/img/app/ct-favicon.ico') }}">
    <link href="{{ asset('/css/base.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/jquery-ui.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/jquery.toast.min.css') }}" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet"/>
@yield('css')

<!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
    <script async="" src="https://www.google-analytics.com/analytics.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-93377526-1', 'auto');
        ga('send', 'pageview');

    </script>
</head>
<body>
<input type="hidden" id="base_url" value="{{ url() }}"/>
<input type="hidden" id="asset_url" value="{{ asset('/') }}"/>

@section('navbar')
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('/img/app/logo_text.png') }}"/>
                </a>
            </div>

            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/projects') }}">전체 공연 보기</a></li>
                    <li><a href="{{ url('/blueprints/welcome') }}">공연 개설 신청</a></li>
                    <!-- <li><a href="{{ url('/help') }}">도움말</a></li> -->
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    @if (Auth::guest())
                        <li><a href="{{ url('/auth/login') }}">LOGIN</a></li>
                        <li><a href="{{ url('/auth/register') }}">JOIN</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/users/') }}/{{ Auth::user()->id }}">내 페이지</a></li>
                                <li><a href="{{ url('/users/') }}/{{ Auth::user()->id }}/form">내 정보수정</a></li>
                                <li><a href="{{ url('/users/') }}/{{ Auth::user()->id }}/orders">결제확인</a></li>
                                <li><a href="{{ url('/auth/logout') }}">로그아웃</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
@show

<div id="main">
    @yield('content')
</div>

<footer>
    <div class="container ct-res-text footer-top">
        <div class="col-xs-12 col-sm-12 col-md-3">
            <img src="{{ asset('/img/app/logo-color.png') }}" class="footer-logo">
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3">
            <h2>social media</h2>
            <h2 class="footer-social">
            <li>
            <a href="https://www.facebook.com/crowdticket/" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
            <li><a href="https://www.instagram.com/crowdticket/" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
            <li><a href="http://blog.naver.com/crowdticket" target="_blank"><img src="{{ asset('/img/app/naver-icon.png') }}" class="naver-icon"></a></li>
            </h2>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3">
            <h2>address</h2>
            <h4>서울시 동대문구 회기로 85<br>
             카이스트 경영대학원 7415</h4>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-3">
            <h2>contact</h2>
            <h4>KAKAOTALK: @크라우드티켓<br>
             TEL: 070-8819-4308<br>
             E-MAIL: contact@crowdticket.kr</h4>
        </div>
        <div class="col-md-12 ct-info">
            <p>
                 나인에이엠 대표: 신효준&nbsp;|&nbsp;사업자 등록번호: 859 12 00216&nbsp;|&nbsp;통신판매업신고: 2017-서울동대문-1218&nbsp;|&nbsp;<a href="https://crowdticket.kr/terms">이용약관</a> / <a href="https://crowdticket.kr/privacy">개인정보취급방침</a>
            </p>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <h4>COPYRIGHT © 2016 CROWDTICKET</h4>
            <p>
                 크라우드티켓은 펀딩을 받거나 티켓을 판매하는 공연의 당사자가 아닙니다. 따라서 공연의 진행과 보상 지급에 대한 책임은 해당 프로젝트 기획자에게 있습니다.<br>
                 하지만 크라우드티켓팀은 우리 공연예술의 발전을 위해 안전하고 편리한 플랫폼을 제공할 수 있도록 항상 최선을 다하겠습니다.
            </p>
        </div>
    </div>
</footer>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script src="{{ asset('/js/underscore-min.js') }}"></script>
<script src="{{ asset('/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('/js/jquery.form.min.js') }}"></script>
<script src="{{ asset('/js/jquery.toast.min.js') }}"></script>
<script src="{{ asset('/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('/js/additional-methods.min.js') }}"></script>
<script src="{{ asset('/js/jquery.form.custom.js') }}"></script>
<script src="{{ asset('/js/app.2.js?version=3') }}"></script>
<script src="{{ asset('/js/loader.js') }}"></script>

@yield('js')

</body>
</html>
