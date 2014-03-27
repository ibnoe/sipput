<!DOCTYPE html>
<html>
<com:THead>    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Core CSS -->
    <link href="<%=$this->Page->Theme->baseUrl%>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<%=$this->Page->Theme->baseUrl%>/font-awesome/css/font-awesome.css" rel="stylesheet">
    <!-- SB Admin CSS -->
    <link href="<%=$this->Page->Theme->baseUrl%>/css/sb-admin.css" rel="stylesheet">
</com:THead>
<body>
<com:TForm Attributes.role="form">
    <div class="container">
        <com:TContentPlaceHolder ID="content" />
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
</body>
</html>
