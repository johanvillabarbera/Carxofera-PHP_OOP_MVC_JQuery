<?php
$path = $_SERVER['DOCUMENT_ROOT'] . '/8_CRUD_MVC v10 proves/8_MVC_CRUD/';
include($path . "module/login/model/DAOLogin.php");
include($path . "model/middleware_auth.php");
@session_start();

switch ($_GET['op']) {
    case 'list';
        include("module/login/view/login.html");
        break;

    case 'register':
        // Comprobar que el usuario no exista
        try {
            $daoLog = new DAOLogin();
            $check_username = $daoLog->select_username($_POST['username_reg']);
        } catch (Exception $e) {
            echo json_encode("error");
            exit;
        }

        if ($check_username) {
            echo json_encode("error_username");
            exit;
        }//end_if

        // Comprobar que la email no exista
        try {
            $daoLog = new DAOLogin();
            $check_email = $daoLog->select_email($_POST['email_reg']);
        } catch (Exception $e) {
            echo json_encode("error");
            exit;
        }

        if ($check_email) {
            echo json_encode("error_email");
            exit;
        }//end_if

        //En caso de no existir ninguno de los dos, creará el perfil
        try {
            $daoLog = new DAOLogin();
            $rdo = $daoLog->insert_user($_POST['username_reg'], $_POST['email_reg'], $_POST['password_reg1']);
        } catch (Exception $e) {
            echo json_encode("error");
            exit;
        }

        if (!empty($rdo)) {
            echo json_encode($rdo);
        } else {
            echo json_encode("error");
        }
        break;

    case 'login':
        try {
            $daoLog = new DAOLogin();
            $rdo = $daoLog->select_user($_POST['username_log']);

            if ($rdo == "error_user") {
                echo json_encode("error_user");
                exit;
            } else {
                if (password_verify($_POST['password_log'], $rdo['password'])) {
                    $token[0]= create_token($rdo["username"]);
                    $token[1]= create_token_refresh($rdo["username"]);
                    $_SESSION['username'] = $rdo['username']; //Guardamos el usuario 
                    $_SESSION['tiempo'] = time(); //Guardamos el tiempo que se logea
                    echo json_encode($token);
                    exit;
                } else {
                    echo json_encode("error_password");
                    exit;
                }
            }
        } catch (Exception $e) {
            echo json_encode("error");
            exit;
        }
        break;

    case 'logout':
        unset($_SESSION['username']);
        unset($_SESSION['tiempo']);
        session_destroy();

        echo json_encode('Done');
        break;

    case 'data_user':
        $json = decode_token($_POST['token']);
        $daoLog = new DAOLogin();
        $rdo = $daoLog->select_data_user($json['username']);
        echo json_encode($rdo);
        exit;
        break;

    case 'actividad':
        if (!isset($_SESSION["tiempo"])) {
            echo json_encode("inactivo");
            exit();
        } else {
            if ((time() - $_SESSION["tiempo"]) >= 1800) { //1800s=30min
                echo json_encode("inactivo");
                exit();
            } else {
                echo json_encode("activo");
                exit();
            }
        }
        break;

    case 'controluser':
        $token_dec = decode_token($_POST['token']);

        if ($token_dec['exp'] < time()) {
            echo json_encode("Wrong_User");
            exit();
        }

        if (isset($_SESSION['username']) && ($_SESSION['username']) == $token_dec['username']) {
            echo json_encode("Correct_User");
            exit();
        } else {
            echo json_encode("Wrong_User");
            exit();
        }
        break;

    case 'refresh_token':
        $old_token = decode_token($_POST['token']);
        $old_token_refresh = decode_token($_POST['token_refresh']);

        if ($old_token_refresh['exp'] < time()) {
            $new_token = create_token_refresh($old_token['username']);
            echo json_encode($new_token);
            exit();
        }else{
            echo json_encode("No_Refresh");
            exit();
        }
        break;

    case 'refresh_cookie':
        session_regenerate_id();
        echo json_encode("Done");
        exit;
        break;

    default;
        include("view/inc/error404.php");
        break;
}