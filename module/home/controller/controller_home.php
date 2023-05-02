<?php
    $path = $_SERVER['DOCUMENT_ROOT'] . '/8_CRUD_MVC v10 proves/8_MVC_CRUD/';
    include($path . "module/home/model/DAOHome.php");

    if(isset($_GET['op'])){
        switch ($_GET['op']) {
            case 'list';
                include ('module/home/view/home.html');
            break;

            case 'Carrousel_Brand';
                try{
                    $daohome = new DAOHome();
                    $SelectBrand = $daohome->select_brand();
                } catch(Exception $e){
                    echo json_encode("error");
                }
                
                if(!empty($SelectBrand)){
                    echo json_encode($SelectBrand); 
                }
                else{
                    echo json_encode("error");
                }
            break;

            case 'homePageCategory';
                try{
                    $daohome = new DAOHome();
                    $SelectCategory = $daohome->select_categories();
                } catch(Exception $e){
                    echo json_encode("error");
                }
                
                if(!empty($SelectCategory)){
                    echo json_encode($SelectCategory); 
                }
                else{
                    echo json_encode("error");
                }
            break;

            case 'homePageType';
                try{
                    $daohome = new DAOHome();
                    $SelectType = $daohome->select_type_motor();
                } catch(Exception $e){
                    echo json_encode("error");
                }
                
                if(!empty($SelectType)){
                    echo json_encode($SelectType); 
                }
                else{
                    echo json_encode("error");
                }
            break;

            default;
                include("view/inc/error404.php");
            break;

            case 'Carrousel_visits';
                try{
                    $daohome = new DAOHome();
                    $SelectVisits = $daohome->select_visits();
                } catch(Exception $e){
                    echo json_encode("error");
                }
                
                if(!empty($SelectVisits)){
                    echo json_encode($SelectVisits); 
                }
                else{
                    echo json_encode("error");
                }
            break;
        }
    }else{
        include ('module/home/view/home.html');
    }//end_else
?>