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
    <com:TContentPlaceHolder ID="csscontent" />   	
</com:THead>
<body class="">
<com:TForm ID="MainForm" Attributes.class="form-horizontal">
<div class="navbar navbar-fixed-top navbar-primary main" role="navigation"><!--start top bar-->      
    <div class="navbar-header pull-left">
        <div class="navbar-brand">
            <div class="pull-left">
                <a href="" class="toggle-button toggle-sidebar btn-navbar"><i class="fa fa-bars"></i></a>
            </div>
            <a class="appbrand innerL"><com:TOutputCache><%=$this->Application->getID()%></com:TOutputCache></a>            
        </div>
    </div>     
    <ul class="nav navbar-nav navbar-right hidden-xs">                                
        <li><a href="<%=$this->Service->constructUrl('Logout')%>" class="menu-icon"><i class="fa fa-sign-out"></i></a></li>
    </ul>
</div><!--end top bar-->	
<div id="menu" class="hidden-print hidden-xs"><!--start sidebar profile and side bar menu-->
	<div class="sidebar sidebar-inverse">
		<div class="user-profile media innerAll">
			<a href="" class="pull-left"><img src="<%=$this->Page->Theme->baseUrl%>/assets/images/people/50/8.jpg" alt="" class="img-circle"></a>
			<div class="media-body">
				<a href="" class="strong"><%=$this->Page->Pengguna->getUsername()%></a>				
			</div>
			<ul>
				<li><a href=""><i class="fa fa-fw fa-user"></i></a></li>
				<li><a href=""><i class="fa fa-fw fa-envelope"></i></a></li>
				<li><a href=""><i class="fa fa-fw fa-lock"></i></a></li>
			</ul>
		</div>
        <com:TPanel Visible="<%=$this->Page->Pengguna->getTipeUser()=='sa'%>" CssClass="sidebarMenuWrapper">
            <ul class="list-unstyled">
				<li<%=$this->Page->showDashboard==true?' class="active"':''%>><a href="<%=$this->Service->constructUrl('sa.Home')%>"><i class=" icon-projector-screen-line"></i><span>Dashboard</span></a></li>					
                <li<%=$this->Page->showSetting==true?' class="hasSubmenu active"':' class="hasSubmenu"'%>>
					<a href="#" data-target="#collapse-menu" data-toggle="collapse"><i class="icon-settings-wheel-fill"></i><span>Setting</span></a>
					<ul class="collapse" id="collapse-menu">					
						<li><a href="<%=$this->Service->constructUrl('sa.setting.User')%>"><i class="icon-group"></i><span>User</span></a></li>						
					</ul>
				</li>
			</ul>
        </com:TPanel>		
	</div>
</div><!--end sidebar profile and side bar menu-->
<div id="content"><!--start main content-->
    <h1 class="bg-white content-heading border-bottom"><com:TContentPlaceHolder ID="moduleheader" /></h1>
    <com:TContentPlaceHolder ID="maincontent" />
</div><!--end main content-->		
</com:TForm>
<div class="clearfix"></div>
<!-- // Sidebar menu & content wrapper END -->
<div id="footer" class="hidden-print">
    <!--  Copyright Line -->
    <div class="copy">&copy; 2012 - 2014 - <a href="http://www.yacanet.com">Yacanet</a>. All Rights Reserved.</div>
    <!--  End Copyright Line -->
</div><!-- // Footer END -->
<!--core script-->
<!--[if lt IE 9]>
  <script src="<%=$this->page->theme->baseUrl%>/assets/html5shiv.js"></script>
  <script src="<%=$this->page->theme->baseUrl%>/assets/respond.min.js"></script>
<![endif]-->
<script src="<%=$this->Page->Theme->baseUrl%>/assets/components/library/jquery/jquery.min.js?v=v1.2.3"></script>
<script src="<%=$this->Page->Theme->baseUrl%>/assets/components/library/jquery/jquery-migrate.min.js?v=v1.2.3"></script>
<script src="<%=$this->Page->Theme->baseUrl%>/assets/components/library/modernizr/modernizr.js?v=v1.2.3"></script>
<script src="<%=$this->Page->Theme->baseUrl%>/assets/components/plugins/less-js/less.min.js?v=v1.2.3"></script>
<script src="<%=$this->Page->Theme->baseUrl%>/assets/components/modules/admin/charts/flot/assets/lib/excanvas.js?v=v1.2.3"></script>
<script src="<%=$this->Page->Theme->baseUrl%>/assets/components/plugins/browser/ie/ie.prototype.polyfill.js?v=v1.2.3"></script>
<!-- Global -->
	<script>
	var basePath = '',
		commonPath = '<%=$this->Page->Theme->baseUrl%>/assets/',
		rootPath = '<%=$this->Page->Theme->baseUrl%>/',
		DEV = false,
		componentsPath = '<%=$this->Page->Theme->baseUrl%>/assets/components/';
	
	var primaryColor = '#cb4040',
		dangerColor = '#b55151',
		infoColor = '#466baf',
		successColor = '#8baf46',
		warningColor = '#ab7a4b',
		inverseColor = '#45484d';
	
	var themerPrimaryColor = primaryColor;
</script>
<script src="<%=$this->Page->Theme->baseUrl%>/assets/components/library/bootstrap/js/bootstrap.min.js?v=v1.2.3"></script>
<script src="<%=$this->Page->Theme->baseUrl%>/assets/components/plugins/nicescroll/jquery.nicescroll.min.js?v=v1.2.3"></script>
<script src="<%=$this->Page->Theme->baseUrl%>/assets/components/plugins/breakpoints/breakpoints.js?v=v1.2.3"></script>
<script src="<%=$this->Page->Theme->baseUrl%>/assets/components/core/js/animations.init.js?v=v1.2.3"></script>
<com:TContentPlaceHolder ID="jscontent" />
<script src="<%=$this->Page->Theme->baseUrl%>/assets/components/plugins/holder/holder.js?v=v1.2.3"></script>
<script src="<%=$this->Page->Theme->baseUrl%>/assets/components/core/js/sidebar.main.init.js?v=v1.2.3"></script>
<script src="<%=$this->Page->Theme->baseUrl%>/assets/components/core/js/sidebar.collapse.init.js?v=v1.2.3"></script>
<script src="<%=$this->Page->Theme->baseUrl%>/assets/components/helpers/themer/assets/plugins/cookie/jquery.cookie.js?v=v1.2.3"></script>
<script src="<%=$this->Page->Theme->baseUrl%>/assets/components/core/js/core.init.js?v=v1.2.3"></script>	
</body>
</html>