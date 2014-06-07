<ul class="nav nav-tabs pull-right">                            
    <li<%=$this->MultiView->ActiveViewIndex===0?' class="active"':''%>>        
        <com:TActiveLinkButton Text="Verifikasi Data" CommandName="SwitchViewIndex" CommandParameter="0" Enabled="<%=$this->MultiView->ActiveViewIndex===0?false:true%>">
            <prop:ClientSide.OnPreDispatch>
                $('loading').show();                                                                                             
            </prop:ClientSide.OnPreDispatch>                                            
            <prop:ClientSide.OnComplete>																	                                            
                $('loading').hide(); 
            </prop:ClientSide.OnComplete>
        </com:TActiveLinkButton>
    </li>
    <li<%=$this->MultiView->ActiveViewIndex===1?' class="active"':''%>>        
        <com:TActiveLinkButton Text="Approval Pengajuan" CommandName="SwitchViewIndex" CommandParameter="1" Enabled="<%=$this->MultiView->ActiveViewIndex===1?false:true%>">
            <prop:ClientSide.OnPreDispatch>
                $('loading').show();                                                                                             
            </prop:ClientSide.OnPreDispatch>                                            
            <prop:ClientSide.OnComplete>																	                                            
                $('loading').hide(); 
            </prop:ClientSide.OnComplete>
        </com:TActiveLinkButton>
    </li>                            
    <li class="dropdown dummyclass">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            AKSI <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
            <li>
                <com:TActiveLinkButton CausesValidation="false" ClientSide.PostState="false" OnClick="Page.printOut" Attributes.Title="Print Surat Pengantar KA. UPT" Text="<i class='fa fa-print'></i> Surat Pengantar KA. UPT" CommandParameter="suratpengantar">
                    <prop:ClientSide.OnPreDispatch>
                        $('loading').show();                                                                                                                 
                    </prop:ClientSide.OnPreDispatch>                    
                    <prop:ClientSide.onComplete>                                            
                        $('loading').hide();
                    </prop:ClientSide.OnComplete>
                </com:TActiveLinkButton>
            </li>  
            <li>
                <com:TActiveLinkButton CausesValidation="false" ClientSide.PostState="false" OnClick="Page.printOut" Attributes.Title="Print Form Pemeriksaan Fisik Kapal" Text="<i class='fa fa-print'></i> Pemeriksaan Fisik Kapal" CommandParameter="pemeriksaankapal">
                    <prop:ClientSide.OnPreDispatch>
                        $('loading').show();                                                                                                                 
                    </prop:ClientSide.OnPreDispatch>                    
                    <prop:ClientSide.onComplete>                                            
                        $('loading').hide();
                    </prop:ClientSide.OnComplete>
                </com:TActiveLinkButton>
            </li>            
            <li role="presentation" class="divider"></li>
            <li>
                <com:TActiveLinkButton CausesValidation="false" ClientSide.PostState="false" OnClick="closeDetailPengajuan" Attributes.Title="Close Detail Pengajuan" Text="<i class='fa fa-sign-out'></i> Close" CommandParameter="<%#$this->DataItem['RecNoSiup']%>">                                                                                                            
                    <prop:ClientSide.OnPreDispatch>
                        $('loading').show();                                                                                                                 
                    </prop:ClientSide.OnPreDispatch>                    
                    <prop:ClientSide.onComplete>                                            
                        $('loading').hide();
                    </prop:ClientSide.OnComplete>
                </com:TActiveLinkButton>
            </li>
        </ul>
    </li>
    <li class="pull-left header"><i class="ion ion-clipboard"></i> Detail Pengajuan</li>
</ul>
                        