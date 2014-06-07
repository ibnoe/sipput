<!DOCTYPE html>
<html>
<com:THead>    
    <meta charset="utf-8">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- bootstrap 3.0.2 -->
    <link href="<%=$this->Page->Theme->baseUrl%>/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="<%=$this->Page->Theme->baseUrl%>/css/ionicons.min.css" rel="stylesheet" type="text/css" />
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
<body class="skin-blue">
<div id="loading" style="display:none">
    Please wait while process your request !!!
</div>
<header class="header">
    <a href="<%=$this->Page->constructUrl('Home',true)%>" class="logo">
        <!-- Add the class icon to your logo image or logo icon to add the margining -->
        SIPPUT
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <div class="navbar-right">
            <ul class="nav navbar-nav">                                   
                <li>
                    <a href="<%=$this->Page->constructUrl('Download',true)%>">
                        <i class="fa fa-download fa-fw"></i>
                        DOWNLOAD
                    </a>
                </li>               
                <!-- Notifications: style can be found in dropdown.less -->
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-tasks"></i> Perizinan Baru                        
                    </a>
                    <ul class="dropdown-menu">                       
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li>
                                    <a href="<%=$this->Page->constructUrl('perizinan.AddSIPI',true)%>"><i class="fa fa-tasks"></i> SIPI</a>                                
                                </li>                            
                                <li>
                                    <a href="<%=$this->Page->constructUrl('perizinan.AddSIKPI',true)%>"><i class="fa fa-tasks"></i> SIKPI</a>
                                </li>                            
                                <li>
                                    <a href="<%=$this->Page->constructUrl('perizinan.AddSIKPPI',true)%>"><i class="fa fa-tasks"></i> SIKPPI</a>
                                </li>                            
                                <li>
                                    <a href="<%=$this->Page->constructUrl('perizinan.AddBudiDaya',true)%>"><i class="fa fa-tasks"></i> Budi Daya</a>
                                </li>                            
                                <li>
                                    <a href="<%=$this->Page->constructUrl('perizinan.AddPengolahan',true)%>"><i class="fa fa-tasks"></i> Pengolahan</a>
                                </li> 
                            </ul>
                        </li>                       
                    </ul>
                </li>                
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-user"></i>
                        <span><%=$this->Page->Pengguna->getUsername()%>  <i class="caret"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header bg-light-blue">
                            <img src="<%=$this->Page->Theme->baseUrl%>/img/avatar3.png" class="img-circle" alt="User Image" />
                            <p>
                                <%=$this->Page->Pengguna->getUsername()%>                                
                            </p>
                        </li>                       
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="<%=$this->Page->constructUrl('Logout')%>" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div><!--end navbar-right -->
    </nav>
</header>
<com:TForm Attributes.roles="form" Attributes.class="form-horizontal">
<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-side sidebar-offcanvas">                
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="<%=$this->Page->Theme->baseUrl%>/img/avatar3.png" class="img-circle" alt="User Image" />
                </div>
                <div class="pull-left info">
                    <p>Hello, <%=$this->Page->Pengguna->getUsername()%> </p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>            
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <com:TLiteral Visible="<%=$this->Page->Pengguna->getTipeUser()=='sa'%>">                
                <ul class="sidebar-menu">
                    <li<%=$this->Page->showDashboard==true ? ' class="active"':''%>>
                        <a href="<%=$this->Page->constructUrl('Home',true)%>">
                            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                        </a>              
                    </li>          
                    <li class="treeview<%=$this->Page->showDMaster==true ? ' active':''%>">
                        <a href="#">
                            <i class="fa fa-book"></i>
                            <span>Data Master</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li<%=$this->Page->showSatuan==true ? ' class="active"':''%>>
                                <a href="<%=$this->Page->constructUrl('dmaster.Satuan',true)%>"><i class="fa fa-angle-double-right"></i> Satuan</a>
                            </li>
                            <li<%=$this->Page->showJenisIzinUsaha==true ? ' class="active"':''%>>
                                <a href="<%=$this->Page->constructUrl('dmaster.JenisIzinUsaha',true)%>"><i class="fa fa-angle-double-right"></i> Jenis Izin Usaha</a>
                            </li>                                                  
                            <li<%=$this->Page->showJenisAlat==true ? ' class="active"':''%>>
                                <a href="<%=$this->Page->constructUrl('dmaster.JenisAlat',true)%>"><i class="fa fa-angle-double-right"></i> Jenis Alat</a>
                            </li>                        
                            <li<%=$this->Page->showBahanAlat==true ? ' class="active"':''%>>
                                <a href="<%=$this->Page->constructUrl('dmaster.BahanAlat',true)%>"><i class="fa fa-angle-double-right"></i> Bahan Alat</a>
                            </li>                     
                        </ul>
                    </li>
                    <li class="treeview<%=$this->Page->showLokasi==true ? ' active':''%>">
                        <a href="#">
                            <i class="fa fa-globe"></i>
                            <span>Lokasi</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">                                                        
                            <li<%=$this->Page->showAreaPenangkapan==true ? ' class="active"':''%>>
                                <a href="<%=$this->Page->constructUrl('lokasi.AreaPenangkapan',true)%>"><i class="fa fa-angle-double-right"></i> Area Penangkapan</a>
                            </li> 
                            <li<%=$this->Page->showPelabuhan==true ? ' class="active"':''%>>
                                <a href="<%=$this->Page->constructUrl('lokasi.Pelabuhan',true)%>"><i class="fa fa-angle-double-right"></i> Pelabuhan</a>
                            </li>
                            <li<%=$this->Page->showLokasiUsaha==true ? ' class="active"':''%>>
                                <a href="<%=$this->Page->constructUrl('lokasi.LokasiUsaha',true)%>"><i class="fa fa-angle-double-right"></i> Lokasi Usaha</a>
                            </li>                                                                                    
                        </ul>
                    </li>                    
                    <li<%=$this->Page->showUPTD==true ? ' class="active"':''%>>
                        <a href="<%=$this->Page->constructUrl('UPTD',true)%>">
                            <i class="fa fa-building-o"></i> <span>UPTD</span>
                        </a>              
                    </li>
                    <li<%=$this->Page->showPemohon==true ? ' class="active"':''%>>
                        <a href="<%=$this->Page->constructUrl('Pemohon',true)%>">
                            <i class="fa fa-user-md"></i> <span>Pemohon</span>   
                        </a>
                    </li>    
                    <li class="treeview<%=$this->Page->showDaftarPengajuan==true ? ' active':''%>">
                        <a href="#">
                            <i class="fa fa-tasks"></i>
                            <span>Daftar Pengajuan</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li<%=$this->Page->showPengajuanSIPI==true ? ' class="active"':''%>>
                                <a href="<%=$this->Page->constructUrl('perizinan.PengajuanSIPI',true)%>"><i class="fa fa-angle-double-right"></i> SIPI</a>                                
                            </li>                                                        
                        </ul>
                    </li>
                    <li class="treeview<%=$this->Page->showDaftarIzin==true ? ' active':''%>">
                        <a href="#">
                            <i class="fa fa-tasks"></i>
                            <span>Daftar Izin</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li<%=$this->Page->showDaftarSIUP==true ? ' class="active"':''%>>
                                <a href="<%=$this->Page->constructUrl('perizinan.DaftarSIUP',true)%>"><i class="fa fa-angle-double-right"></i> SIUP</a>                                
                            </li>                            
                            <li<%=$this->Page->showDaftarSIPI==true ? ' class="active"':''%>>
                                <a href="<%=$this->Page->constructUrl('perizinan.DaftarSIPI',true)%>"><i class="fa fa-angle-double-right"></i> SIPI</a>                                
                            </li>                                                        
                        </ul>
                    </li>
                    <li class="treeview<%=$this->Page->showSetting==true ? ' active':''%>">
                        <a href="#">
                            <i class="fa fa-wrench"></i>
                            <span>Setting</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>                            
                        <ul class="treeview-menu">                        
                            <li<%=$this->Page->showUser==true?' class="active"':''%>>
                                <a href="<%=$this->Page->constructUrl('setting.User',true)%>"><i class="fa fa-angle-double-right"></i> User</a>
                            </li>                        
                            <li<%=$this->Page->showCache==true?' class="active"':''%>>
                                <a href="<%=$this->Page->constructUrl('setting.Cache',true)%>"><i class="fa fa-angle-double-right"></i> Cache</a>
                            </li>                        
                        </ul>
                        <!-- /.nav-second-level -->
                    </li>
                    <!-- sidebar menu: : style can be found in sidebar.less --> 
                </ul>
            </com:TLiteral>            
            <com:TLiteral Visible="<%=$this->Page->Pengguna->getTipeUser()=='ad'%>">
                <ul class="sidebar-menu">
                    <li<%=$this->Page->showDashboard==true ? ' class="active"':''%>>
                        <a href="<%=$this->Page->constructUrl('Home',true)%>">
                            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                        </a>              
                    </li>
                    <li class="treeview<%=$this->Page->showDMaster==true ? ' active':''%>">
                        <a href="#">
                            <i class="fa fa-book"></i>
                            <span>Data Master</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">                                                                               
                            <li<%=$this->Page->showKapal==true ? ' class="active"':''%>>
                                <a href="<%=$this->Page->constructUrl('dmaster.Kapal',true)%>"><i class="fa fa-angle-double-right"></i> Kapal</a>
                            </li>
                        </ul>
                    </li>
                    <li<%=$this->Page->showPemohon==true ? ' class="active"':''%>>
                        <a href="<%=$this->Page->constructUrl('Pemohon',true)%>">
                            <i class="fa fa-user-md"></i> <span>Pemohon</span>
                        </a>
                    </li>
                    <li class="treeview<%=$this->Page->showPerizinanBaru==true ? ' active':''%>">
                        <a href="#">
                            <i class="fa fa-tasks"></i>
                            <span>Perizinan Baru</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li<%=$this->Page->showAddIzinNewSIPI==true ? ' class="active"':''%>>
                                <a href="<%=$this->Page->constructUrl('perizinan.AddSIPI',true)%>"><i class="fa fa-angle-double-right"></i> SIPI<small class="badge pull-right bg-green">new</small></a>                                
                            </li>                                                        
                        </ul>
                    </li>
                    <li class="treeview<%=$this->Page->showDaftarPengajuan==true ? ' active':''%>">
                        <a href="#">
                            <i class="fa fa-tasks"></i>
                            <span>Daftar Pengajuan</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li<%=$this->Page->showPengajuanSIPI==true ? ' class="active"':''%>>
                                <a href="<%=$this->Page->constructUrl('perizinan.PengajuanSIPI',true)%>"><i class="fa fa-angle-double-right"></i> SIPI</a>                                
                            </li>                                                        
                        </ul>
                    </li>                    
                </ul>
            </com:TLiteral>
        </section>
        <!-- /.sidebar -->
    </aside>
    <!-- Right side column. Contains the navbar and content of the page -->    
    <aside class="right-side">                
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <com:TContentPlaceHolder ID="moduleheader" />               
            </h1>            
            <ol class="breadcrumb">   
                <li><a href="<%=$this->Page->constructUrl('Home',true)%>"><i class="fa fa-dashboard"></i> Home</a></li>
                <com:TContentPlaceHolder ID="modulebreadcrumb" />             
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">    
            <com:TContentPlaceHolder ID="maincontent" />
        </section><!-- /.content -->
    </aside><!-- /.right-side -->    
</div><!-- ./wrapper -->
<com:TJavascriptLogger />
</com:TForm>
<!-- jQuery 2.0.2 -->
<script src="<%=$this->Page->Theme->baseUrl%>/js/jquery.min.js"></script>
<script type="text/javascript">
    jQuery.noConflict();
</script>
<!-- Bootstrap -->
<script src="<%=$this->Page->Theme->baseUrl%>/js/bootstrap.min.js" type="text/javascript"></script>    
<!-- AdminLTE App -->
<script src="<%=$this->Page->Theme->baseUrl%>/js/AdminLTE/app.js" type="text/javascript"></script>
<!-- Page-Level Plugin Scripts -->
<com:TContentPlaceHolder ID="jscontent" />  
<!-- SIPPUT Scripts  -->
<script src="<%=$this->Page->Theme->baseUrl%>/js/sipput.js"></script>
</body>
</html>