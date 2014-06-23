<com:NModalPanel ID="modalPrintOut" CssClass="row">        
    <div class="col-md-12">
        <div class="box box-solid box-primary">
            <div class="box-header">                        
                <h3 class="box-title">
                    <i class="fa fa-print"></i>
                    Print Out  <com:TActiveLabel ID="lblPrintout" />
                </h3>
                <div class="box-tools pull-right">                    
                    <button data-widget="remove" class="btn btn-primary btn-sm" onclick="new Modal.Box('<%=$this->modalPrintOut->ClientID%>').hide();return false;">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <com:TActiveLabel ID="labelBoxBody" />
                <com:TActiveHyperLink ID="linkOutput" />                        
            </div>
            <div class="box-footer text-right">
                <a OnClick="new Modal.Box('<%=$this->modalPrintOut->ClientID%>').hide();return false;" class="btn btn-default btn-sm"><i class="fa fa-sign-out"></i> Close</a>                              
            </div>
        </div>        
    </div>        
</com:NModalPanel>