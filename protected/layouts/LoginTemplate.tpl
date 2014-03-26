<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="ie lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="ie lt-ie9"> <![endif]-->
<!--[if gt IE 8]> <html> <![endif]-->
<!--[if !IE]><!--><html><!-- <![endif]-->
<com:THead>
    <!-- Meta -->
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
    <!--base css styles-->
	<link rel="stylesheet" href="<%=$this->page->theme->baseUrl%>/assets/components/library/bootstrap/css/bootstrap.min.css">     
	<link rel="stylesheet" href="<%=$this->page->theme->baseUrl%>/assets/components/library/icons/fontawesome/assets/css/font-awesome.min.css">
	<link rel="stylesheet" href="<%=$this->page->theme->baseUrl%>/assets/components/library/icons/glyphicons/assets/css/glyphicons_regular.css">
	<link rel="stylesheet" href="<%=$this->page->theme->baseUrl%>/assets/components/library/icons/glyphicons/assets/css/glyphicons_social.css">
    <link rel="stylesheet" href="<%=$this->page->theme->baseUrl%>/assets/components/library/icons/glyphicons/assets/css/glyphicons_filetypes.css">
    <link rel="stylesheet" href="<%=$this->page->theme->baseUrl%>/assets/components/library/icons/pictoicons/css/picto.css">
    <link rel="stylesheet" href="<%=$this->page->theme->baseUrl%>/assets/components/library/animate/animate.min.css">    
    <!--page specifiec css style-->
    <link rel="stylesheet" href="<%=$this->page->theme->baseUrl%>/assets/css/admin/module.admin.page.login.min.css" />
</com:THead>
<body class="loginWrapper">  
<com:TForm Attributes.role="form">
    <div id="content"><h4 class="innerAll margin-none border-bottom text-center"><i class="fa fa-lock"></i> Login ke Akun Anda</h4>
        <div class="login spacing-x2">
            <div class="placeholder text-center"><i class="fa fa-lock"></i></div>
            <div class="col-sm-6 col-sm-offset-3">
                <div class="panel panel-default">
                    <div class="panel-body innerAll">
                        <com:TContentPlaceHolder ID="content" />
                    </div>
                </div>
            </div>
        </div>    
</com:TForm>
<!--[if lt IE 9]>
  <script src="<%=$this->page->theme->baseUrl%>/assets/html5shiv.js"></script>
  <script src="<%=$this->page->theme->baseUrl%>/assets/respond.min.js"></script>
<![endif]-->
</body>
</html>