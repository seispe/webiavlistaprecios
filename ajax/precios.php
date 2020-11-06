<?php 

header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
header("Access-Control-Allow-Origin: *");


require_once "../modelos/Precios.php";

$precios=new Precios();

$MARCA_PROD=isset($_GET["MARCA_PROD"])? ($_GET["MARCA_PROD"]):"";
$MARCA_VEHI=isset($_GET["MARCA_VEHI"])? ($_GET["MARCA_VEHI"]):"";
$MODELO_VEHI=isset($_GET["MODELO_VEHI"])? ($_GET["MODELO_VEHI"]):"";
$FAMILIA=isset($_GET["FAMILIA"])? ($_GET["FAMILIA"]):"";

switch ($_GET["op"]){

	case 'guardaryeditar':
		if (empty($idcategoria)){
			$rspta=$categoria->insertar($nombre,$descripcion);
			echo $rspta ? "Categoría registrada" : "Categoría no se pudo registrar";
		}
		else {
			$rspta=$categoria->editar($idcategoria,$nombre,$descripcion);
			echo $rspta ? "Categoría actualizada" : "Categoría no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$categoria->desactivar($idcategoria);
 		echo $rspta ? "Categoría Desactivada" : "Categoría no se puede desactivar";
	break;

	case 'activar':
		$rspta=$categoria->activar($idcategoria);
 		echo $rspta ? "Categoría activada" : "Categoría no se puede activar";
	break;

	case 'mostrar':
		$rspta=$categoria->mostrar($idcategoria);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

    case 'listar':
        
        $RUC=$_GET['ruc'];
        $VENDEDOR=$_GET['vendedor'];
        $linea=$_GET['linea'];

  
     

		$rspta=$precios->listar($MARCA_PROD,
        $MARCA_VEHI,
        $MODELO_VEHI,
        $FAMILIA,$RUC,$VENDEDOR,$linea);

     

        if($rspta=='GENERAL'){

            echo 'GENERAL';

        }elseif($rspta=='ESPECIALISTA'){

            echo 'ESPECIALISTA';
        }
        
        else{
            // $data= Array();

            // while ($reg=$rspta->fetchObject()){
            //     $cantidad=number_format($reg->CANT_MATRIZ,2,'.','');
            //     if((int)$cantidad>10){
            //        $cantidad="+10";
            //     }else{
            //         $cantidad=(int)$cantidad;
            //     }
            //     $data[]=array(
            //         "0"=>$reg->CODIGO_EMPRESA,
            //         "1"=>$reg->DESCRIPCION,
            //         //"2"=>$reg->DESC_CORTA,
            //         //"3"=>$reg->UND,
            //         //"4"=>$reg->FECHA_CREAC_ART,
            //         //"5"=>$reg->IND_ACTIVO,
            //         "2"=>$reg->MARCA_PROD,
            //         "3"=>$reg->MARCA_VEHI,
            //         "4"=>$reg->MODELO_VEHI,
            //         "5"=>$reg->FAMILIA,
            //         //"10"=>$reg->SUBLINEA,
            //         //"11"=>$reg->COD_ORIGINAL,
            //         //"13"=>number_format($reg->CUARENTENA,2,'.',''),
            //         //"14"=>number_format($reg->NO_CONFORME,2,'.',''),
            //         //"15"=>number_format($reg->CANT_PVG,2,'.',''),
            //         // //"16"=>number_format($reg->ASIGNADO_WMS,2,'.',''),
            //         // "6"=>number_format($reg->PRECIO_MAYORISTA,2,'.',''),
            //          "6"=>number_format($reg->PRECIO_MAYORISTA_IVA,2,'.',''),
            //         // "8"=>number_format($reg->PRECIO_MINORISTA,2,'.',''),
            //         // "9"=>number_format($reg->PRECIO_MINORISTA_IVA,2,'.',''),
            //         // "10"=>number_format($reg->PRECIO_ESPECIAL,2,'.',''),
            //         // "11"=>number_format($reg->PRECIO_ESPECIAL_IVA,2,'.','')
            //         "7"=>$cantidad
            //         );
            // }
            // $results = array(
            //     "sEcho"=>1, //Información para el datatables
            //     "iTotalRecords"=>count($data), //enviamos el total registros al datatable
            //     "iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
            //     "aaData"=>$data);
            // echo json_encode($results);

            $resp=$precios->reporteEmail($MARCA_PROD,
            $MARCA_VEHI,
            $MODELO_VEHI,
            $FAMILIA,$RUC,$VENDEDOR,$linea);

           echo $resp;

            

        }

         //Vamos a declarar un array
         
 		
         
         break;

         case 'MARCA_PROD':

            $rspta = $precios->MARCA_PROD();

            $ech='<option value="">--SELECCIONE--</option>';

		while ($reg = $rspta->fetchObject())
				{
					$ech.= '<option value="'. $reg->MARCA_PROD . '"> '.$reg->MARCA_PROD.' </option>';
                }
        echo $ech;
         break;

         case 'MARCA_VEHI':

            $rspta = $precios->MARCA_VEHI();
            $ech='<option value="">--SELECCIONE--</option>';

		while ($reg = $rspta->fetchObject())
				{
					$ech.= '<option value="'. $reg->MARCA_VEHI . '"> '.$reg->MARCA_VEHI.' </option>';
                }
        echo $ech;
         break;

         case 'MODELO_VEHI':

            $rspta = $precios->MODELO_VEHI();
            $ech='<option value="">--SELECCIONE--</option>';

		while ($reg = $rspta->fetchObject())
				{
					$ech.= '<option value="'. $reg->MODELO_VEHI . '"> '.$reg->MODELO_VEHI.' </option>';
                }
        echo $ech;
        break;

        
        case 'FAMILIA':

            $rspta = $precios->FAMILIA();
            $ech='<option value="">--SELECCIONE--</option>';

		while ($reg = $rspta->fetchObject())
				{
					$ech.= '<option value="'. $reg->Linea_desc . '"> '.$reg->Linea_desc.' </option>';
                }
        echo $ech;
        break;


        case 'MODELO_VEHI_MARCA':

            
         

            $rspta = $precios->selectModeloMarca($MARCA_VEHI);

            $ech='<option value="">--SELECCIONE--</option>';

		while ($reg = $rspta->fetchObject())
				{
					$ech.= '<option value="'. $reg->MODELO_VEHI . '"> '.$reg->MODELO_VEHI.' </option>';
                }
        echo $ech;
        break;


         
    break;
    
    default:

break;
}

 


?>