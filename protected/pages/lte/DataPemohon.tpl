<div class="row">
    <div class="col-md-12">
        <div class="box box-solid box-success">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-user"></i> Profil Pemohon</h3>
                <div class="box-tools pull-right">
                    <button data-widget="collapse" class="btn btn-success btn-sm" onclick="JavaScript:return false;">
                        <i class="fa fa-minus"></i>
                    </button>
                    <button data-widget="remove" class="btn btn-success btn-sm" onclick="JavaScript:return false;">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">                    
                        <div class="form-group">
                            <label class="col-sm-3 control-label">ID</label>
                            <div class="col-sm-9">
                                <p class="form-control-static"><%=$this->dataPengajuan['RecNoPem']%></p>
                            </div>                            
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Nama</label>
                            <div class="col-sm-9">
                                <p class="form-control-static"><%=$this->dataPengajuan['NmPem']%></p>
                            </div>                            
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Alamat</label>
                            <div class="col-sm-9">
                                <p class="form-control-static"><%=$this->dataPengajuan['AlmtPem']%></p>
                            </div>                            
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">No. Telp</label>
                            <div class="col-sm-9">
                                <p class="form-control-static"><%=$this->dataPengajuan['TelpPem']%></p>
                            </div>                            
                        </div>
                    </div>
                    <div class="col-md-6">                    
                        <div class="form-group">
                            <label class="col-sm-3 control-label">UPTD</label>
                            <div class="col-sm-9">
                                <p class="form-control-static"><%=$this->dataPengajuan['nama_uptd']%></p>
                            </div>                            
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Status</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">
                                    <%=strtoupper($this->dataPengajuan['Status'])%>
                                    <%=$this->dataPengajuan['active']==1 ? '<span class="label label-success">Aktif</span>':'<span class="label label-danger">Tidak Aktif</span>'%>
                                </p>
                            </div>                            
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Tanggal Registrasi</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">
                                    <%=$this->Page->TGL->tanggal('d F Y',$this->dataPengajuan['date_added'])%>                                    
                                </p>
                            </div>                            
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>