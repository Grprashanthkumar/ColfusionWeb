exec sp_addlinkedserver
@server = N'colfusion_external_840',
@srvproduct = N'MySQL',
@provider = N'MSDASQL',
@provstr =N'DRIVER={MySQL ODBC 5.2 Unicode Driver}; SERVER=colfusion.exp.sis.pitt.edu;PORT=3306;DATABASE=colfusion_external_840;USER=dataverseDev;PASSWORD=dataverseDev;OPTION=3;'; 

exec sp_addlinkedsrvlogin
@rmtsrvname = N'colfusion_external_840',
@locallogin = N'remoteUserTest',
@rmtuser = N'dataverseDev',
@rmtpassword = N'dataverseDev';




select * from openquery(colfusion_external_840, 'select * from colfusion_canvases');