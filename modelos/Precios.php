<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/ConexionSQL.php";

Class Precios
{
	//Implementamos nuestro constructor
	public function __construct()
	{

    }
    
    public function MARCA_PROD(){

        $sql="SELECT distinct RTRIM(LTRIM(MARCA_PROD)) MARCA_PROD from VI_LISTA_PRECIOS where MARCA_PROD <> ''";
        return ejecutarConsultaSQL($sql);

    }

    public function MARCA_VEHI(){

        $sql="SELECT distinct RTRIM(LTRIM(MARCA_VEHI)) MARCA_VEHI from VI_LISTA_PRECIOS where MARCA_VEHI <> ''";
        return ejecutarConsultaSQL($sql);

    }

    public function MODELO_VEHI(){

        $sql="SELECT distinct RTRIM(LTRIM(MODELO_VEHI)) MODELO_VEHI from VI_LISTA_PRECIOS where MODELO_VEHI <> ''";
        return ejecutarConsultaSQL($sql);

    }

    public function FAMILIA(){

        $sql="SELECT distinct RTRIM(LTRIM(FAMILIA)) FAMILIA from VI_LISTA_PRECIOS where FAMILIA <> ''";
        return ejecutarConsultaSQL($sql);

    }

    public function selectModeloMarca($MARCA_VEHI){

        $MARCA_VEHI=str_replace(" ","%",$MARCA_VEHI);
        $MARCA_VEHI=str_replace("%20","%",$MARCA_VEHI);
        
        $sql="SELECT distinct RTRIM(LTRIM(MODELO_VEHI)) MODELO_VEHI from VI_LISTA_PRECIOS where MODELO_VEHI <> '' and MARCA_VEHI like '%$MARCA_VEHI%'";
     
        return ejecutarConsultaSQL($sql);

    }

    public function listar($MARCA_PROD,$MARCA_VEHI,$MODELO_VEHI,$FAMILIA,$RUC,$VENDEDOR,$GENERAL){

        $MARCA_VEHI=str_replace(" ","%",$MARCA_VEHI);
        $MARCA_VEHI=str_replace("%20","%",$MARCA_VEHI);
        $MODELO_VEHI=str_replace(" ","%",$MODELO_VEHI);
        $MODELO_VEHI=str_replace("%20","%",$MODELO_VEHI);
        $FAMILIA=str_replace(" ","%",$FAMILIA);
        $FAMILIA=str_replace("%20","%",$FAMILIA);

        $con=0;
        $like_MARCA_PROD='(';
        while($con<count($MARCA_PROD)){
            $marca=$MARCA_PROD[$con];

            $marca=str_replace(" ","%",$marca);
            $marca=str_replace("%20","%",$marca);

           
            if($con==(count($MARCA_PROD))-1){

                $like_MARCA_PROD.="MARCA_PROD like '%$marca%' )";
            }else{
                $like_MARCA_PROD.="MARCA_PROD like '%$marca%' or ";
            }
            $con++;
        }

   

      

        if($MARCA_PROD==""  && $MARCA_VEHI==""  &&  $MODELO_VEHI==""  &&  $FAMILIA=="" ){
            //consula general

            $sql=" SELECT top 1 tipo FROM [GA_VTA_CTR_LISTA_PRECIOS] WHERE tipo='GENERAL' AND ruc='$RUC' AND vendedor='$VENDEDOR' AND MONTH(fecha)= MONTH(GETDATE()) ";
          
            $resp=ejecutarConsultaSQL($sql);

            if(empty($resp->fetchObject())){

                $sql="INSERT INTO [GA_VTA_CTR_LISTA_PRECIOS] (tipo,ruc,fecha,vendedor,filtros) VALUES ('GENERAL','$RUC',GETDATE(),'$VENDEDOR','$GENERAL')";
            
                ejecutarConsultaSQL($sql);

                if($GENERAL=='CARROCERIA'){

                    $sql="SELECT top 1 CODIGO_EMPRESA,
                    DESCRIPCION,
                    MARCA_PROD,
                    MARCA_VEHI,
                    MODELO_VEHI,
                    FAMILIA,
                    PRECIO_MAYORISTA,
                    PRECIO_MAYORISTA_IVA,
                    PRECIO_MINORISTA,
                    PRECIO_MINORISTA_IVA,
                    PRECIO_ESPECIAL,
                    CANT_MATRIZ,
                    PRECIO_ESPECIAL_IVA FROM VI_LISTA_PRECIOS WHERE RTRIM(LTRIM(SUBLINEA)) ='COLISIÓN - CARROCERIA' and CANT_MATRIZ >0";

                }else{
                    $sql="SELECT top 1 CODIGO_EMPRESA,
                    DESCRIPCION,
                    MARCA_PROD,
                    MARCA_VEHI,
                    MODELO_VEHI,
                    FAMILIA,
                    PRECIO_MAYORISTA,
                    PRECIO_MAYORISTA_IVA,
                    PRECIO_MINORISTA,
                    PRECIO_MINORISTA_IVA,
                    PRECIO_ESPECIAL,
                    CANT_MATRIZ,
                    PRECIO_ESPECIAL_IVA FROM VI_LISTA_PRECIOS WHERE RTRIM(LTRIM(SUBLINEA)) IN('MOTOR - MOTOR DIESEL','MOTOR - MOTOR GASOLINA') and CANT_MATRIZ >0";
                        
                }

               
                return ejecutarConsultaSQL($sql);
                
            }else{

                return 'GENERAL';

            }

        }else{

            $sql=" SELECT COUNT(ID) CONTADOR  FROM [GA_VTA_CTR_LISTA_PRECIOS] WHERE tipo='ESPECIALISTA' AND ruc='$RUC' AND vendedor='$VENDEDOR' AND MONTH(fecha)= MONTH(GETDATE())  ";
        
            $resp=ejecutarConsultaSQL($sql)->fetchObject();

          
            if((int)$resp->CONTADOR<2){

                $filtro=$like_MARCA_PROD.'_'.$MARCA_VEHI.'_'.$MODELO_VEHI.'_'.$FAMILIA;
                $filtro=str_replace("%"," ",$filtro);
                $filtro=str_replace("'","",$filtro);
                $filtro=str_replace("(","",$filtro);
                $filtro=str_replace(")","",$filtro);

                $sql="INSERT INTO [GA_VTA_CTR_LISTA_PRECIOS] (tipo,ruc,fecha,vendedor,filtros) VALUES ('ESPECIALISTA','$RUC',GETDATE(),'$VENDEDOR','$filtro')";
               
                ejecutarConsultaSQL($sql);

                $sql="SELECT top 1  CODIGO_EMPRESA,
                DESCRIPCION,
                MARCA_PROD,
                MARCA_VEHI,
                MODELO_VEHI,
                FAMILIA,
                PRECIO_MAYORISTA,
                PRECIO_MAYORISTA_IVA,
                PRECIO_MINORISTA,
                PRECIO_MINORISTA_IVA,
                PRECIO_ESPECIAL,
                CANT_MATRIZ,
                PRECIO_ESPECIAL_IVA FROM VI_LISTA_PRECIOS where $like_MARCA_PROD and MARCA_VEHI like '%$MARCA_VEHI%'  and MODELO_VEHI like '%$MODELO_VEHI%' and FAMILIA like '%$FAMILIA%' and CANT_MATRIZ >0";
                 
                return ejecutarConsultaSQL($sql);
                
            }else{

                return 'ESPECIALISTA';

            }

        }
    }

    public function listar2($MARCA_PROD,$MARCA_VEHI,$MODELO_VEHI,$FAMILIA,$RUC,$VENDEDOR,$GENERAL){

        
        $MARCA_VEHI=str_replace(" ","%",$MARCA_VEHI);
        $MARCA_VEHI=str_replace("%20","%",$MARCA_VEHI);
        $MODELO_VEHI=str_replace(" ","%",$MODELO_VEHI);
        $MODELO_VEHI=str_replace("%20","%",$MODELO_VEHI);
        $FAMILIA=str_replace(" ","%",$FAMILIA);
        $FAMILIA=str_replace("%20","%",$FAMILIA);

        
        $con=0;
        $like_MARCA_PROD='(';
        while($con<count($MARCA_PROD)){
            $marca=$MARCA_PROD[$con];

            $marca=str_replace(" ","%",$marca);
            $marca=str_replace("%20","%",$marca);

           
            if($con==(count($MARCA_PROD))-1){

                $like_MARCA_PROD.="MARCA_PROD like '%$marca%' )";
            }else{
                $like_MARCA_PROD.="MARCA_PROD like '%$marca%' or ";
            }
            $con++;
        }
      
      

        if($MARCA_PROD==""  && $MARCA_VEHI==""  &&  $MODELO_VEHI==""  &&  $FAMILIA=="" ){
            //consula general

            if($GENERAL=='CARROCERIA'){

                $sql="SELECT CODIGO_EMPRESA,
                DESCRIPCION,
                MARCA_PROD,
                MARCA_VEHI,
                MODELO_VEHI,
                FAMILIA,
                PRECIO_MAYORISTA,
                PRECIO_MAYORISTA_IVA,
                PRECIO_MINORISTA,
                PRECIO_MINORISTA_IVA,
                PRECIO_ESPECIAL,
                CANT_MATRIZ,
                PRECIO_ESPECIAL_IVA FROM VI_LISTA_PRECIOS WHERE RTRIM(LTRIM(SUBLINEA)) ='COLISIÓN - CARROCERIA' and CANT_MATRIZ >0";

            }else{
                $sql="SELECT CODIGO_EMPRESA,
                DESCRIPCION,
                MARCA_PROD,
                MARCA_VEHI,
                MODELO_VEHI,
                FAMILIA,
                PRECIO_MAYORISTA,
                PRECIO_MAYORISTA_IVA,
                PRECIO_MINORISTA,
                PRECIO_MINORISTA_IVA,
                PRECIO_ESPECIAL,
                CANT_MATRIZ,
                PRECIO_ESPECIAL_IVA FROM VI_LISTA_PRECIOS WHERE RTRIM(LTRIM(SUBLINEA)) IN('MOTOR - MOTOR DIESEL','MOTOR - MOTOR GASOLINA') and CANT_MATRIZ >0";
                    
            }
                return ejecutarConsultaSQL($sql);
                
            }

        else{

         

                $sql="SELECT CODIGO_EMPRESA,
                DESCRIPCION,
                MARCA_PROD,
                MARCA_VEHI,
                MODELO_VEHI,
                FAMILIA,
                PRECIO_MAYORISTA,
                PRECIO_MAYORISTA_IVA,
                PRECIO_MINORISTA,
                PRECIO_MINORISTA_IVA,
                PRECIO_ESPECIAL,
                CANT_MATRIZ,
                PRECIO_ESPECIAL_IVA FROM VI_LISTA_PRECIOS where $like_MARCA_PROD and MARCA_VEHI like '%$MARCA_VEHI%'  and MODELO_VEHI like '%$MODELO_VEHI%' and FAMILIA like '%$FAMILIA%' and CANT_MATRIZ >0";
                    
                return ejecutarConsultaSQL($sql);
                
            } 
    }

    public function numSecuencial(){
        $sql='SELECT top 1 id from [GA_VTA_CTR_LISTA_PRECIOS] order by id desc';
        return ejecutarConsultaSQL($sql);
    }

    public function selectEmail($ruc,$vendedor){
        $sql="SELECT RTRIM(LTRIM(Master_ID)) ruc ,
        RTRIM(LTRIM(INET1)) correo1 from SY01200 where Master_Type = 'CUS' and Master_ID ='$ruc'
        UNION ALL 
        SELECT ven_codigo, correo FROM SI_PRE..vendedores WHERE VEN_CODIGO = '$vendedor'";

        return ejecutarConsultaSQL($sql);
    }

    public function reporteEmail($MARCA_PROD,
    $MARCA_VEHI,
    $MODELO_VEHI,
    $FAMILIA,$RUC,$VENDEDOR,$linea){



            $rspta=$this->listar2($MARCA_PROD,
            $MARCA_VEHI,
            $MODELO_VEHI,
            $FAMILIA,$RUC,$VENDEDOR,$linea);

            $secuencial=$this->numSecuencial()->fetchObject();

            if(strlen($secuencial->id)==1){
                $secuencial->id='00000'.$secuencial->id;
            }elseif(strlen($secuencial->id)==2){
                $secuencial->id='0000'.$secuencial->id;
            }elseif(strlen($secuencial->id)==3){
                $secuencial->id='000'.$secuencial->id;
            }elseif(strlen($secuencial->id)==4){
                $secuencial->id='00'.$secuencial->id;
            }elseif(strlen($secuencial->id)==5){
                $secuencial->id='0'.$secuencial->id;
            }elseif(strlen($secuencial->id)==6){
                $secuencial->id=''.$secuencial->id;
            }



                $cabecera='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
                <HTML>
                <HEAD>
                    <META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
                    <TITLE></TITLE>
                    <META NAME="GENERATOR" CONTENT="LibreOffice 4.1.6.2 (Linux)">
                    <META NAME="AUTHOR" CONTENT="DANNY GARCIA">
                    <META NAME="CREATED" CONTENT="20201014;134700000000000">
                    <META NAME="CHANGEDBY" CONTENT="DANNY GARCIA">
                    <META NAME="CHANGED" CONTENT="20201014;135200000000000">
                    <META NAME="AppVersion" CONTENT="16.0000">
                    <META NAME="DocSecurity" CONTENT="0">
                    <META NAME="HyperlinksChanged" CONTENT="false">
                    <META NAME="LinksUpToDate" CONTENT="false">
                    <META NAME="ScaleCrop" CONTENT="false">
                    <META NAME="ShareDoc" CONTENT="false">
                    <STYLE TYPE="text/css">
                    <!--
                        @page { size: landscape; margin-left: 0.98in; margin-right: 0.98in; margin-top: 1.18in; margin-bottom: 1.18in }
                        P { margin-bottom: 0.08in; direction: ltr; widows: 2; orphans: 2 }
                    -->
                    </STYLE>
                </HEAD>
                <BODY LANG="en-US" DIR="LTR">
                <P ALIGN=CENTER STYLE="margin-bottom: 0.11in"><FONT SIZE=4 STYLE="font-size: 16pt"><I><B>REPORTE
                DE LISTA DE PRECIOS</B></I></FONT></P>
                <TABLE WIDTH=933 CELLPADDING=7 CELLSPACING=0>
                    <COL WIDTH=296>
                    <COL WIDTH=297>
                    <COL WIDTH=296>
                    <TR VALIGN=TOP>
                        <TD WIDTH=296 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                            <P><B>VENDEDOR:</B> '.$VENDEDOR.'</P>
                        </TD>
                        <TD WIDTH=297 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                            <P><B>CLIENTE:</B> '.$RUC.'</P>
                        </TD>
                        <TD WIDTH=296 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                            <P><B>NUMERO:</B> '.$secuencial->id.' </P>
                        </TD>
                    </TR>
                </TABLE>
                <P STYLE="margin-bottom: 0.11in"><BR><BR>
                </P>
                        <TABLE WIDTH=933 CELLPADDING=7 CELLSPACING=0>
                <COL WIDTH=102>
                <COL WIDTH=102>
                <COL WIDTH=103>
                <COL WIDTH=103>
                <COL WIDTH=103>
                <COL WIDTH=103>
                <COL WIDTH=103>
                <COL WIDTH=102>
                <TR VALIGN=TOP>
                    <TD WIDTH=102 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                        <P ALIGN=CENTER><B>CODIGO</B></P>
                    </TD>
                    <TD WIDTH=102 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                        <P ALIGN=CENTER><B>DESCRIPCION</B></P>
                    </TD>
                    <TD WIDTH=103 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                        <P ALIGN=CENTER><B>MARCA_PROD</B></P>
                    </TD>
                    <TD WIDTH=103 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                        <P ALIGN=CENTER><B>MARCA_VEHI</B></P>
                    </TD>
                    <TD WIDTH=103 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                        <P ALIGN=CENTER><B>MODELO_VEHI</B></P>
                    </TD>
                    <TD WIDTH=103 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                        <P ALIGN=CENTER><B>FAMILIA</B></P>
                    </TD>
                    <TD WIDTH=103 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                        <P ALIGN=CENTER><B>PRECIO_MAYO</B></P>
                    </TD>
                    <TD WIDTH=102 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                        <P ALIGN=CENTER><B>CANTIDAD</B></P>
                    </TD>
                </TR>';

                $detalle='';

                while($reg=$rspta->fetchObject()){


                $cantidad=number_format($reg->CANT_MATRIZ,2,'.','');
                if((int)$cantidad>10){
                    $cantidad="+10";
                }else{
                    $cantidad=(int)$cantidad;
                }
                    $detalle.='<TR VALIGN=TOP>
                    <TD WIDTH=102 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                        <P ALIGN=CENTER><FONT COLOR="#333333"><FONT FACE="Source Sans Pro, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN STYLE="background: #f9f9f9">'.$reg->CODIGO_EMPRESA.'</SPAN></FONT></FONT></FONT></P>
                    </TD>
                    <TD WIDTH=102 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                        <P ALIGN=CENTER><FONT COLOR="#333333"><FONT FACE="Source Sans Pro, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN STYLE="background: #f9f9f9">'.$reg->DESCRIPCION.'
                        </SPAN></FONT></FONT></FONT></P>
                    </TD>
                    <TD WIDTH=103 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                        <P ALIGN=CENTER><FONT COLOR="#333333"><FONT FACE="Source Sans Pro, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN STYLE="background: #f9f9f9">'.$reg->MARCA_PROD.'</SPAN></FONT></FONT></FONT></P>
                    </TD>
                    <TD WIDTH=103 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                        <P ALIGN=CENTER><FONT COLOR="#333333"><FONT FACE="Source Sans Pro, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN STYLE="background: #f9f9f9">'.$reg->MARCA_VEHI.'</SPAN></FONT></FONT></FONT></P>
                    </TD>
                    <TD WIDTH=103 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                        <P ALIGN=CENTER><FONT COLOR="#333333"><FONT FACE="Source Sans Pro, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN STYLE="background: #f9f9f9">'.$reg->MODELO_VEHI.'</SPAN></FONT></FONT></FONT></P>
                    </TD>
                    <TD WIDTH=103 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                        <P ALIGN=CENTER><FONT COLOR="#333333"><FONT FACE="Source Sans Pro, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN STYLE="background: #f9f9f9">'.$reg->FAMILIA.'
                        </SPAN></FONT></FONT></FONT></P>
                    </TD>
                    <TD WIDTH=103 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                        <P ALIGN=CENTER><FONT COLOR="#333333"><FONT FACE="Source Sans Pro, serif"><FONT SIZE=2 STYLE="font-size: 10pt"><SPAN STYLE="background: #f9f9f9">'.number_format($reg->PRECIO_MAYORISTA_IVA,2,'.','').'</SPAN></FONT></FONT></FONT></P>
                    </TD>
                    <TD WIDTH=102 STYLE="border: 1px solid #00000a; padding-top: 0in; padding-bottom: 0in; padding-left: 0.08in; padding-right: 0.08in">
                        <P ALIGN=CENTER>'.$cantidad.'</P>
                    </TD>
                </TR>';
                }
            
                $html=$cabecera.$detalle.'
                </TABLE>
                <P STYLE="margin-bottom: 0.11in"><BR><BR>
                </P>
                </BODY>
                </HTML>';

                require '../vendor/autoload.php';
                //Creamos la instancia de la clase PHPMailer y configuramos la cuenta
                $mail=new \PHPMailer\PHPMailer\PHPMailer();
                $mail->SMTPDebug  = 2;
                $mail->Mailer="smtp";
                $mail->Helo = "www.iav.com.ec"; //Muy importante para que llegue a hotmail y otros
                $mail->SMTPAuth=true;
                $mail->Host='smtp.outlook.com';
                $mail->Port='587'; //depende de lo que te indique tu ISP. El default es 25, pero nuestro ISP lo tiene puesto al 26
                $mail->Username='info-noreplay@iav.com.ec';
                $mail->Password='IAVin2016@';
                $mail->From='info-noreplay@iav.com.ec';
                $mail->FromName='LISTADO DE PRECIOS';
                $mail->Timeout=60;
                $mail->IsHTML(true);
                //Enviamos el correo
                //$mail->AddAddress($correo); //Puede ser Hotmail

                $SelectCorreo=$this->selectEmail($RUC,$VENDEDOR);

                

                while($regCorreos=$SelectCorreo->fetchObject()){
                    $mail->AddAddress($regCorreos->correo1);
                }

                //generar pdf
                $date=date('Y-m-d');
                $mpdf = new \Mpdf\Mpdf();
                $mpdf->WriteHTML($html);
                $mpdf->Output('../files/'.$RUC.$date.'.pdf');
                $docpdf='../files/'.$RUC.$date.'.pdf';

                //$mail->AddAddress('dannyggg23@gmail.com');
                $mail->AddAddress('dggarcia@iav.com.ec');
                $mail->AddAddress('mvargas@iav.com.ec');

                $mail->Subject ='LISTADO DE PRECIOS';

                //$mail->Body=$html;
                $mail->Body="<h2>LISTA DE PRECIOS </h2> <BR> 
                <br> <h3>CLIENTE : <STRONG> ".$RUC." </STRONG></h3>
                <br> <h3>VENDEDOR : <STRONG> ".$VENDEDOR." </STRONG></h3>
                ";
                $mail->addAttachment($docpdf, $name = $RUC.$date.".pdf",  $encoding = 'base64', $type = 'application/pdf');
                $mail->AltBody='Se ha generado el listado de precios';
                $exito = $mail->Send();

                return $exito."___ok";

    }












}
