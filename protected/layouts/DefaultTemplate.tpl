<!DOCTYPE html>
<html>
<com:THead>    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Core CSS -->
    <link href="<%=$this->Page->Theme->baseUrl%>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<%=$this->Page->Theme->baseUrl%>/font-awesome/css/font-awesome.css" rel="stylesheet">
    <!-- Page-Level Plugin CSS -->
    <com:TContentPlaceHolder ID="csscontent" />
    <!-- SB Admin CSS -->
    <link href="<%=$this->Page->Theme->baseUrl%>/css/sb-admin.css" rel="stylesheet">
</com:THead>
<body>
<div id="loading" style="display:none">
    Please wait while process your request !!!
</div>
<com:TForm Attributes.role="form">
<div id="wrapper">
    <!-- start navbar-header -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<%=$this->Page->constructUrl('Home',true)%>">SIPPUT</a>            
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li>
                <a href="<%=$this->Page->constructUrl('Download',true)%>">
                    <i class="fa fa-download fa-fw"></i>
                    DOWNLOAD
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-user fa-fw"></i>
                    PROFIL USER
                </a>
            </li>
            <li>
                <a href="<%=$this->Page->constructUrl('Logout')%>"><i class="fa fa-sign-out fa-fw"></i> LOGOUT</a>
            </li>            
        </ul>
    </nav>
    <!-- end navbar-header --> 
    <nav class="navbar-default navbar-static-side" role="navigation">
        <com:TPanel Visible="<%=$this->Page->Pengguna->getTipeUser()=='sa'%>">        
            <ul class="nav" id="side-menu">                
                <li>
                    <a href="<%=$this->Page->constructUrl('Home',true)%>"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                </li>                
                <li<%=$this->Page->showDMaster==true ? ' class="active"':''%>>
                    <a href="#"><i class="fa fa-book fa-fw"></i> Data Master<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">                                             
                        <li>
                            <a href="<%=$this->Page->constructUrl('dmaster.JenisIzinUsaha',true)%>">Jenis Izin Usaha<%=$this->Page->showJenisIzinUsaha==true?'<span class="fa fa-eye child-menu-selected"></span>':''%></a>
                        </li>                        
                        <li>
                            <a href="<%=$this->Page->constructUrl('dmaster.BidangIzinUsaha',true)%>">Bidang Izin Usaha<%=$this->Page->showBidangIzinUsaha==true?'<span class="fa fa-eye child-menu-selected"></span>':''%></a>
                        </li>                        
                        <li>
                            <a href="<%=$this->Page->constructUrl('dmaster.JenisAlat',true)%>">Jenis Alat<%=$this->Page->showJenisAlat==true?'<span class="fa fa-eye child-menu-selected"></span>':''%></a>
                        </li>                        
                        <li>
                            <a href="<%=$this->Page->constructUrl('dmaster.UPTD',true)%>">UPTD<%=$this->Page->showUPTD==true?'<span class="fa fa-eye child-menu-selected"></span>':''%></a>
                        </li>                        
                        <li>
                            <a href="<%=$this->Page->constructUrl('dmaster.Pemohon',true)%>">Pemohon <%=$this->Page->showPemohon==true?'<span class="fa fa-eye child-menu-selected"></span>':''%></a>
                        </li>                        
                    </ul>
                    <!-- /.nav-second-level -->
                </li>                                
                <li<%=$this->Page->showPerizinan==true ? ' class="active"':''%>>
                    <a href="#"><i class="fa fa-tasks fa-fw"></i> Perizinan<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">                                                
                        <li>
                            <a href="<%=$this->Page->constructUrl('perizinan.PermohonanBaru',true)%>">Permohonan Baru <%=$this->Page->showPermohonanBaru==true?'<span class="fa fa-eye child-menu-selected"></span>':''%></a>
                        </li>                        
                        <li>
                            <a href="<%=$this->Page->constructUrl('perizinan.PermohonanPerpanjangan',true)%>">Permohonan Perpan. <%=$this->Page->showPermohonanPerpanjangan==true?'<span class="fa fa-eye child-menu-selected"></span>':''%></a>
                        </li>                        
                    </ul>
                </li>
                <li<%=$this->Page->showSetting==true ? ' class="active"':''%>>
                    <a href="#"><i class="fa fa-wrench fa-fw"></i> Setting<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">                        
                        <li>
                            <a href="<%=$this->Page->constructUrl('setting.User',true)%>">User <%=$this->Page->showUser==true?'<span class="fa fa-eye child-menu-selected"></span>':''%></a>
                        </li>                        
                        <li>
                            <a href="<%=$this->Page->constructUrl('setting.Cache',true)%>">Cache<%=$this->Page->showCache==true?'<span class="fa fa-eye child-menu-selected"></span>':''%></a>
                        </li>                        
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
            </ul>
            <!-- /#side-menu -->
        </com:TPanel>
        <com:TPanel Visible="<%=$this->Page->Pengguna->getTipeUser()=='ad'%>">
            <ul class="nav" id="side-menu">                
                <li>
                    <a href="<%=$this->Page->constructUrl('Home',true)%>"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                </li>  
                <li<%=$this->Page->showDMaster==true ? ' class="active"':''%>>
                    <a href="#"><i class="fa fa-book fa-fw"></i> Data Master<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">                        
                        <li>
                            <a href="<%=$this->Page->constructUrl('dmaster.Pemohon',true)%>">Pemohon <%=$this->Page->showPemohon==true?'<span class="fa fa-eye child-menu-selected"></span>':''%></a>
                        </li>                        
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li<%=$this->Page->showPerizinan==true ? ' class="active"':''%>>
                    <a href="#"><i class="fa fa-tasks fa-fw"></i> Perizinan<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">                                                
                        <li>
                            <a href="<%=$this->Page->constructUrl('perizinan.PermohonanBaru',true)%>">Permohonan Baru <%=$this->Page->showPermohonanBaru==true?'<span class="fa fa-eye child-menu-selected"></span>':''%></a>
                        </li>                        
                        <li>
                            <a href="<%=$this->Page->constructUrl('perizinan.PermohonanPerpanjangan',true)%>">Permohonan Perpan. <%=$this->Page->showPermohonanPerpanjangan==true?'<span class="fa fa-eye child-menu-selected"></span>':''%></a>
                        </li>                        
                    </ul>
                </li>
            </ul>
        </com:TPanel>
        <!-- /.sidebar-collapse -->
    </nav>
    <!-- /.navbar-static-side -->
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><com:TContentPlaceHolder ID="moduleheader" /></h1>
            </div>                        
        </div>
        <!-- /.row -->
        <com:TContentPlaceHolder ID="maincontent" />
    </div>
    <!-- /#page-wrapper -->
    <com:TJavascriptLogger ID="JSLogger" />
</div>
</com:TForm>
<!-- Core Scripts -->
<script src="<%=$this->Page->Theme->baseUrl%>/js/jquery-1.10.2.js"></script>
<script type="text/javascript">
    jQuery.noConflict();
</script>
<script src="<%=$this->Page->Theme->baseUrl%>/js/bootstrap.min.js"></script>
<script src="<%=$this->Page->Theme->baseUrl%>/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<!-- SB Admin Scripts  -->
<script src="<%=$this->Page->Theme->baseUrl%>/js/sb-admin.js"></script>
<!-- Page-Level Plugin Scripts -->
<com:TContentPlaceHolder ID="jscontent" />  
<!-- SIPPUT Scripts  -->
<script src="<%=$this->Page->Theme->baseUrl%>/js/sipput.js"></script>
</body>
</html>