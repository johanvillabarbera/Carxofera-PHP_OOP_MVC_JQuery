<?php
    $path = $_SERVER['DOCUMENT_ROOT'] . '/8_CRUD_MVC v10 proves/8_MVC_CRUD/';
    include($path . "/model/connect.php");

    class DAOLogin{
        function select_username($username){
			$sql = "SELECT username FROM users WHERE username='$username'";
			$conexion = connect::con();
            $res = mysqli_query($conexion, $sql)->fetch_object();
            connect::close($conexion);
            return $res;
		}

        function select_email($email){
			$sql = "SELECT email FROM users WHERE email='$email'";
			$conexion = connect::con();
            $res = mysqli_query($conexion, $sql)->fetch_object();
            connect::close($conexion);
            return $res;
		}

        function insert_user($username, $email, $password){
            $hashed_pass = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
            $hashavatar = md5(strtolower(trim($email))); 
            $avatar = "https://i.pravatar.cc/500?u=$hashavatar";
            $sql ="   INSERT INTO `users`(`username`, `password`, `email`, `type_user`, `avatar`) 
            VALUES ('$username','$hashed_pass','$email','client','$avatar')";

            $conexion = connect::con();
            $res = mysqli_query($conexion, $sql);
            connect::close($conexion);
            return $res;
        }

        function select_user($username){
			$sql = "SELECT `username`, `password`, `email`, `type_user`, `avatar` FROM `users` WHERE username='$username'";
			$conexion = connect::con();
            $res = mysqli_query($conexion, $sql)->fetch_object();
            connect::close($conexion);

            if ($res) {
                $value = get_object_vars($res);
                return $value;
            }else {
                return "error_user";
            }
        }

        function select_data_user($username){
			$sql = "SELECT * FROM users WHERE username='$username'";
			$conexion = connect::con();
            $res = mysqli_query($conexion, $sql)->fetch_object();
            connect::close($conexion);
            return $res;
        }

    }
