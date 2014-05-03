<!DOCTYPE html>
<html class="bg-black">
<com:THead>    
    <meta charset="utf-8">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>    
    <!-- bootstrap 3.0.2 -->
    <link href="<%=$this->Page->Theme->baseUrl%>/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- font Awesome -->
    <link href="<%=$this->Page->Theme->baseUrl%>/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<%=$this->Page->Theme->baseUrl%>/css/AdminLTE.css" rel="stylesheet" type="text/css" />
    <!-- Page-Level Plugin CSS -->
    <com:TContentPlaceHolder ID="csscontent" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="<%=$this->Page->Theme->baseUrl%>/js/html5shiv.js"></script>
      <script src="<%=$this->Page->Theme->baseUrl%>/js/respond.min.js"></script>
    <![endif]-->
</com:THead>
<body class="bg-black">
<div id="loading" style="display:none">
    Please wait while process your request !!!
</div>
<com:TForm Attributes.role="form">   
<com:TContentPlaceHolder ID="content" />
</com:TForm>
<!-- jQuery 2.0.2 -->
<script src="<%=$this->Page->Theme->baseUrl%>/js/jquery.min.js"></script>
<script type="text/javascript">
    jQuery.noConflict();
</script>
<!-- Bootstrap -->
<script src="<%=$this->Page->Theme->baseUrl%>/js/bootstrap.min.js" type="text/javascript"></script>    
</body>
</html>
