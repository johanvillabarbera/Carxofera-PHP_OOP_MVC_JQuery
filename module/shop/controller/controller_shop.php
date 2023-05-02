<?php
$path = $_SERVER['DOCUMENT_ROOT'] . '/8_CRUD_MVC v10 proves/8_MVC_CRUD/';
include($path . "module/shop/model/DAOShop.php");
include($path . "model/middleware_auth.php");

switch ($_GET['op']) {
    case 'list':
        include('module/shop/view/shop.html');
        break;

    case 'all_cars':
        $prod = $_POST['total_prod'];
        $items = $_POST['items_page'];
        try {
            $daoshop = new DAOShop();
            $Dates_Cars = $daoshop->select_all_cars($prod, $items);
        } catch (Exception $e) {
            echo json_encode("error");
        }

        if (!empty($Dates_Cars)) {
            echo json_encode($Dates_Cars);
        } else {
            echo json_encode("error");
        }
        break;

    case 'details_car':
        try {
            $daoshop = new DAOShop();
            $Date_car = $daoshop->select_one_car($_GET['id']);
            $daoshop->count_visits($_GET['id']);
        } catch (Exception $e) {
            echo json_encode("error");
        }
        try {
            $daoshop_img = new DAOShop();
            $Date_images = $daoshop_img->select_imgs_car($_GET['id']);
        } catch (Exception $e) {
            echo json_encode("error");
        }

        if (!empty($Date_car || $Date_images)) {
            $rdo = array();
            $rdo[0] = $Date_car;
            $rdo[1][] = $Date_images;
            echo json_encode($rdo);
        } else {
            echo json_encode("error");
        }
        break;

    case 'all_filters':
        try {
            $daoshop = new DAOShop();
            $Date_brands = $daoshop->select_all_brands();
        } catch (Exception $e) {
            echo json_encode("error");
        }
        try {
            $daoshop = new DAOShop();
            $Date_motor_types = $daoshop->select_all_motor_types();
        } catch (Exception $e) {
            echo json_encode("error");
        }
        try {
            $daoshop = new DAOShop();
            $Date_categories = $daoshop->select_all_categories();
        } catch (Exception $e) {
            echo json_encode("error");
        }
        try {
            $daoshop = new DAOShop();
            $Date_colors = $daoshop->select_all_colors();
        } catch (Exception $e) {
            echo json_encode("error");
        }

        if (!empty($Date_brands || $Date_motor_types || $Date_categories || $Date_colors)) {
            $rdo = array();
            $rdo[0] = $Date_brands;
            $rdo[1] = $Date_motor_types;
            $rdo[2] = $Date_categories;
            $rdo[3] = $Date_colors;
            echo json_encode($rdo);
        } else {
            echo json_encode("error");
        }
        break;

    case 'filter';
        $prod = $_POST['total_prod'];
        $items = $_POST['items_page'];
        try {
            $daoshop = new DAOShop();
            $Date_filter = $daoshop->filters($_POST['filter'], $prod, $items);
        } catch (Exception $e) {
            echo json_encode("error");
        }

        if (!empty($Date_filter)) {
            echo json_encode($Date_filter);
        }
        else {
            echo json_encode("error");
        }
        break;

    case 'filter_search':
        $all_search = $_POST['filter'];
        $city = ($all_search[0]);
        $model = ($all_search[1]);
        $brand = ($all_search[2]);
        $prod = $_POST['total_prod'];
        $items = $_POST['items_page'];

        try {
            $dao = new DAOShop();
            if (($model[1] != "0") && ($brand[1] == "0") && ($city[1] == "0")) {
                $rdo_array[0] = $model;
                $rdo = $dao->filters($rdo_array, $prod, $items);
            }else if (($model[1] == "0") && ($brand[1] != "0") && ($city[1] == "0")) {
                $rdo_array[0] = $brand;
                $rdo = $dao->filters($rdo_array, $prod, $items);
            } else if (($model[1] == "0") && ($brand[1] == "0") && ($city[1] != "0")) {
                $rdo_array[0] = $city;
                $rdo = $dao->filters($rdo_array, $prod, $items);
            } else if (($model[1] != "0") && ($brand[1] != "0") && ($city[1] == "0")) {
                $rdo_array[0] = $model;
                $rdo_array[1] = $brand;
                $rdo = $dao->filters($rdo_array, $prod, $items);
            } else if (($model[1] == "0") && ($brand[1] != "0") && ($city[1] != "0")) {
                $rdo_array[0] = $brand;
                $rdo_array[1] = $city;
                $rdo = $dao->filters($rdo_array, $prod, $items);
            } else if (($model[1] != "0") && ($brand[1] == "0") && ($city[1] != "0")) {
                $rdo_array[0] = $model;
                $rdo_array[1] = $city;
                $rdo = $dao->filters($rdo_array, $prod, $items);
            } else if (($model[1] != "0") && ($brand[1] != "0") && ($city[1] != "0")) {
                $rdo_array[0] = $model;
                $rdo_array[1] = $brand;
                $rdo_array[2] = $city;
                $rdo = $dao->filters($rdo_array, $prod, $items);
            } else {
                $rdo = $dao->select_all_cars($prod, $items);
            }
            
        } catch (Exception $e) {
            echo json_encode("error");
            exit;
        }
        if (!$rdo) {
            echo json_encode("error");
            exit;
        } else {
            $dinfo = array();
            foreach ($rdo as $row) {
                array_push($dinfo, $row);
            }
            echo json_encode($dinfo);
        }
        break;

    case 'filter_sort_by';
        $prod = $_POST['total_prod'];
        $items = $_POST['items_page'];
        try {
            $daoshop = new DAOShop();
            $Date_filter_sort_by = $daoshop->filter_sort_by($_POST['filter'], $prod, $items);
        } catch (Exception $e) {
            echo json_encode("error");
        }

        if (!empty($Date_filter_sort_by)) {
            echo json_encode($Date_filter_sort_by);
        }
        else {
            echo json_encode("error");
        }
        break;

    case 'count_cars_related':
        try {
            $dao = new DAOShop();
            $rdo = $dao->count_cars_related($_POST['name_brand'], $_POST['id_car']);
        } catch (Exception $e) {
            echo json_encode("error");
            exit;
        }
        if (!$rdo) {
            echo json_encode("error");
            exit;
        } else {
            $dinfo = array();
            foreach ($rdo as $row) {
                array_push($dinfo, $row);
            }
            echo json_encode($dinfo);
        }
        break;

    case 'cars_related':
        $brand_name = $_POST['brand'];
        $id_car = $_POST['id_car'];
        $loaded =  $_POST['loaded'];
        $items =  $_POST['items'];
        try {
            $dao = new DAOShop();
            $rdo = $dao->select_cars_related($brand_name, $id_car, $loaded, $items);
        } catch (Exception $e) {
            echo json_encode("error");
            exit;
        }
        if (!$rdo) {
            echo json_encode("error");
            exit;
        } else {
            $dinfo = array();
            foreach ($rdo as $row) {
                array_push($dinfo, $row);
            }
            echo json_encode($dinfo);
        }
        break;

    case 'count_cars_all':
        try {
            $dao = new DAOShop();
            $rdo = $dao->count_cars_all();
        } catch (Exception $e) {
            echo json_encode("error");
            exit;
        }
        if (!$rdo) {
            echo json_encode("error");
            exit;
        } else {
            $dinfo = array();
            foreach ($rdo as $row) {
                array_push($dinfo, $row);
            }
            echo json_encode($dinfo);
        }
        break;

    case 'count_cars_filter':
        $filter =  $_POST['filter'];
        try {
            $dao = new DAOShop();
            $rdo = $dao->count_cars_filter($filter);
        } catch (Exception $e) {
            echo json_encode("error");
            exit;
        }
        if (!$rdo) {
            echo json_encode("error");
            exit;
        } else {
            $dinfo = array();
            foreach ($rdo as $row) {
                array_push($dinfo, $row);
            }
            echo json_encode($dinfo);
        }
        break;

    case 'count_cars_filter_search':
        $all_search = $_POST['filter'];
        $city = ($all_search[0]);
        $model = ($all_search[1]);
        $brand = ($all_search[2]);

        try {
            $dao = new DAOShop();
            if (($model[1] != "0") && ($brand[1] == "0") && ($city[1] == "0")) {
                $rdo_array[0] = $model;
                $rdo = $dao->count_cars_filter($rdo_array);
            }else if (($model[1] == "0") && ($brand[1] != "0") && ($city[1] == "0")) {
                $rdo_array[0] = $brand;
                $rdo = $dao->count_cars_filter($rdo_array);
            } else if (($model[1] == "0") && ($brand[1] == "0") && ($city[1] != "0")) {
                $rdo_array[0] = $city;
                $rdo = $dao->count_cars_filter($rdo_array);
            } else if (($model[1] != "0") && ($brand[1] != "0") && ($city[1] == "0")) {
                $rdo_array[0] = $model;
                $rdo_array[1] = $brand;
                $rdo = $dao->count_cars_filter($rdo_array);
            } else if (($model[1] == "0") && ($brand[1] != "0") && ($city[1] != "0")) {
                $rdo_array[0] = $brand;
                $rdo_array[1] = $city;
                $rdo = $dao->count_cars_filter($rdo_array);
            } else if (($model[1] != "0") && ($brand[1] == "0") && ($city[1] != "0")) {
                $rdo_array[0] = $model;
                $rdo_array[1] = $city;
                $rdo = $dao->count_cars_filter($rdo_array);
            } else if (($model[1] != "0") && ($brand[1] != "0") && ($city[1] != "0")) {
                $rdo_array[0] = $model;
                $rdo_array[1] = $brand;
                $rdo_array[2] = $city;
                $rdo = $dao->count_cars_filter($rdo_array);
            } else {
                $rdo = $dao->count_cars_all();
            }
            
        } catch (Exception $e) {
            echo json_encode("error");
            exit;
        }
        if (!$rdo) {
            echo json_encode("error");
            exit;
        } else {
            $dinfo = array();
            foreach ($rdo as $row) {
                array_push($dinfo, $row);
            }
            echo json_encode($dinfo);
        }
        break;

    case 'control_likes':
        $token = $_POST['token'];
        $id_car = $_POST['id_car'];

        try {
            $json = decode_token($token);
            $dao = new DAOShop();
            $rdo = $dao->control_likes($id_car, $json['username']);
        } catch (Exception $e) {
            echo json_encode("error");
            exit;
        }

        if (!$rdo) {
            echo json_encode("error");
            exit;
        } else{
            echo json_encode("ok");
            exit;
        }//end_else
        break;

    case 'load_likes_user';
        try {
            $json = decode_token($_POST['token']);
            $dao = new DAOShop();
            $rdo = $dao->select_load_likes($json['username']);
        } catch (Exception $e) {
            echo json_encode("error");
            exit;
        }
        if (!$rdo) {
            echo json_encode("error");
            exit;
        } else {
            $dinfo = array();
            foreach ($rdo as $row) {
                array_push($dinfo, $row);
            }
            echo json_encode($dinfo);
        }
        break;

    default;
        include("view/inc/error404.php");
        break;
}
?>