<com:NModalPanel ID="modalPrintOut" CssClass="modal-sipput">
    <div class="modal-header">                
        <h3 id="myModalLabel">Print Out  <com:TActiveLabel ID="lblPrintout" /></h3>
    </div>
    <div class="modal-body">
        <com:TActiveHyperLink ID="linkOutput" />        
    </div>
    <div class="modal-footer">
        <a OnClick="new Modal.Box('<%=$this->modalPrintOut->ClientID%>').hide();return false;" class="btn"><i class='icon-off'></i> Close</a>                              
    </div>    
</com:NModalPanel>