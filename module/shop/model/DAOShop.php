<?php
$path = $_SERVER['DOCUMENT_ROOT'] . '/8_CRUD_MVC v10 proves/8_MVC_CRUD/';
include($path . "/model/connect.php");

class DAOShop{
    function select_all_cars($total_prod, $items_page){
            $sql = "SELECT c.*, b.name_brand , m.name_model , st.name_tshift , c2.name_color , ic.name_img, ft.name_frame, mt.name_tmotor
            FROM car c, brand b , model m , shift_type st , color c2 , img_cars ic , frame_type ft, motor_type mt
            WHERE c.id_model = m.id_model 
            AND m.id_brand = b.id_brand 
            AND c.id_tshift = st.id_tshift 
            AND c.id_color = c2.id_color 
            AND ic.id_car = c.id_car
			AND c.id_frame = ft.id_frame 
			AND c.id_tmotor = mt.id_tmotor
            GROUP BY c.id_car
			LIMIT $total_prod, $items_page";
    
        	$conexion = connect::con();
        	$res = mysqli_query($conexion, $sql);
        	connect::close($conexion);
    
        	$retrArray = array();
        	if (mysqli_num_rows($res) > 0) {
        		while ($row = mysqli_fetch_assoc($res)) {
        			$retrArray[] = $row;
        		}
        	}
        	return $retrArray;
    }

    function select_one_car($id){
        $sql = "SELECT c.*, b.name_brand , m.name_model , st.name_tshift , c2.name_color, c3.name_cat , d.number_door , mt.name_tmotor, c4.name_city  
        FROM car c, brand b , model m , shift_type st , color c2, category c3 , doors d , motor_type mt , city c4 
        WHERE c.id_model = m.id_model 
        AND m.id_brand = b.id_brand 
        AND c.id_tshift = st.id_tshift 
        AND c.id_color = c2.id_color 
        AND c.id_cat = c3.id_cat 
        AND c.id_door = d.id_door
        AND c.id_tmotor = mt.id_tmotor 
        AND c.id_city = c4.id_city 
        AND c.id_car = '$id'";

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql)->fetch_object();
		connect::close($conexion);

		return $res;
	}

	function count_visits($id){
        $sql = "UPDATE car c
				SET c.count_visited = c.count_visited + 1
				WHERE c.id_car = '$id'";

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);

		return $res;
	}
	
    function select_imgs_car($id){
		$sql = "SELECT *
			    FROM img_cars i
			    WHERE i.id_car = '$id'";

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);

		$imgArray = array();
		if (mysqli_num_rows($res) > 0) {
			foreach ($res as $row) {
				array_push($imgArray, $row);
			}
		}
		return $imgArray;
	}

	function select_all_brands(){
		$sql = "SELECT id_brand, name_brand
				FROM brand";

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);

		$retrArray = array();
		if (mysqli_num_rows($res) > 0) {
			while ($row = mysqli_fetch_assoc($res)) {
				$retrArray[] = $row;
			}
		}
		return $retrArray;
	}

	function select_all_motor_types(){
		$sql = "SELECT id_tmotor, name_tmotor
				FROM motor_type";

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);

		$retrArray = array();
		if (mysqli_num_rows($res) > 0) {
			while ($row = mysqli_fetch_assoc($res)) {
				$retrArray[] = $row;
			}
		}
		return $retrArray;
	}

	function select_all_categories(){
		$sql = "SELECT id_cat, name_cat
				FROM category";

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);

		$retrArray = array();
		if (mysqli_num_rows($res) > 0) {
			while ($row = mysqli_fetch_assoc($res)) {
				$retrArray[] = $row;
			}
		}
		return $retrArray;
	}

	function select_all_colors(){
		$sql = "SELECT id_color, name_color
				FROM color";

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);

		$retrArray = array();
		if (mysqli_num_rows($res) > 0) {
			while ($row = mysqli_fetch_assoc($res)) {
				$retrArray[] = $row;
			}
		}
		return $retrArray;
	}

	function filters($filter, $total_prod, $items_page){
		$sql = "SELECT cfilter.*
				FROM (SELECT c.*, b.name_brand , b.id_brand, m.name_model , st.name_tshift , c2.name_color, ic.name_img , ca.name_cat, mt.name_tmotor, ci.name_city, ft.name_frame
						FROM car c, brand b , model m , shift_type st , color c2 , img_cars ic , category ca, motor_type mt, city ci, frame_type ft
						WHERE c.id_model = m.id_model 
						AND m.id_brand = b.id_brand 
						AND c.id_tshift = st.id_tshift 
						AND c.id_color = c2.id_color 
						AND ic.id_car = c.id_car 
						AND c.id_cat = ca.id_cat
						AND c.id_tmotor = mt.id_tmotor
						AND c.id_city = ci.id_city
						AND c.id_frame = ft.id_frame
						GROUP BY c.id_car) as cfilter";

            for ($i=0; $i < count($filter); $i++){
                if ($i==0){
                    $sql.= " WHERE cfilter." . $filter[$i][0] . "= '" . $filter[$i][1] . "'";
                }else {
					$sql.= " AND cfilter." . $filter[$i][0] . "= '" . $filter[$i][1] . "'";
                }        
            }

			$sql.= "LIMIT $total_prod, $items_page";

        $conexion = connect::con();
        $res = mysqli_query($conexion, $sql);
        connect::close($conexion);

        $retrArray = array();
        if ($res -> num_rows > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $retrArray[] = $row;
            }
        }
        return $retrArray;
    }

	function filter_sort_by($filter, $total_prod, $items_page){
		$sql = "SELECT cfilter.*
				FROM (SELECT c.*, b.name_brand , b.id_brand, m.name_model , st.name_tshift , c2.name_color, ic.name_img , ca.name_cat, mt.name_tmotor
						FROM car c, brand b , model m , shift_type st , color c2 , img_cars ic , category ca, motor_type mt
						WHERE c.id_model = m.id_model 
						AND m.id_brand = b.id_brand 
						AND c.id_tshift = st.id_tshift 
						AND c.id_color = c2.id_color 
						AND ic.id_car = c.id_car 
						AND c.id_cat = ca.id_cat
						AND c.id_tmotor = mt.id_tmotor
						GROUP BY c.id_car) as cfilter
						ORDER BY cfilter." . $filter[0] ." " . $filter[1] . "";

		$sql.= " LIMIT $total_prod, $items_page";

        $conexion = connect::con();
        $res = mysqli_query($conexion, $sql);
        connect::close($conexion);

        $retrArray = array();
        if ($res -> num_rows > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $retrArray[] = $row;
            }
        }
        return $retrArray;
    }

	function count_cars_related($name_brand, $id_car){
		$sql = "SELECT COUNT(*) AS 'n_cars'
				FROM car c , model m , brand b 
				WHERE c.id_model = m.id_model AND m.id_brand = b.id_brand 
				AND b.name_brand LIKE '$name_brand'
				AND c.id_car NOT LIKE '$id_car'";

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);

		$retrArray = array();
		if (mysqli_num_rows($res) > 0) {
			while ($row = mysqli_fetch_assoc($res)) {
				$retrArray[] = $row;
			}
		}
		return $retrArray;
	}

	function select_cars_related($brand, $id_car, $loaded, $items){
		$sql = "SELECT cfilter.*
				FROM (SELECT c.*, b.name_brand , b.id_brand, m.name_model , st.name_tshift , c2.name_color, ic.name_img , ca.name_cat, mt.name_tmotor, ci.name_city, ft.name_frame
				FROM car c, brand b , model m , shift_type st , color c2 , img_cars ic , category ca, motor_type mt, city ci, frame_type ft
				WHERE c.id_model = m.id_model 
				AND m.id_brand = b.id_brand 
				AND c.id_tshift = st.id_tshift 
				AND c.id_color = c2.id_color 
				AND ic.id_car = c.id_car 
				AND c.id_cat = ca.id_cat
				AND c.id_tmotor = mt.id_tmotor
				AND c.id_city = ci.id_city
				AND c.id_frame = ft.id_frame
				GROUP BY c.id_car) as cfilter
				WHERE cfilter.name_brand LIKE '$brand'
				AND cfilter.id_car NOT LIKE '$id_car'
				LIMIT $loaded, $items";

		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);

		
		$retrArray = array();
		if (mysqli_num_rows($res) > 0) {
			while ($row = mysqli_fetch_assoc($res)) {
				$retrArray[] = $row;
			}
		}
		return $retrArray;
	}

	function count_cars_all(){
		$sql = "SELECT COUNT(*) AS n_cars FROM car";
		$conexion = connect::con();
		$res = mysqli_query($conexion, $sql);
		connect::close($conexion);
		return $res;
	}

	function count_cars_filter($filter){
		$sql = "SELECT COUNT(*) as 'n_cars'
				FROM (SELECT c.*, b.name_brand , b.id_brand, m.name_model , st.name_tshift , c2.name_color, ic.name_img , ca.name_cat, mt.name_tmotor, ci.name_city, ft.name_frame
						FROM car c, brand b , model m , shift_type st , color c2 , img_cars ic , category ca, motor_type mt, city ci, frame_type ft
						WHERE c.id_model = m.id_model 
						AND m.id_brand = b.id_brand 
						AND c.id_tshift = st.id_tshift 
						AND c.id_color = c2.id_color 
						AND ic.id_car = c.id_car 
						AND c.id_cat = ca.id_cat
						AND c.id_tmotor = mt.id_tmotor
						AND c.id_city = ci.id_city
						AND c.id_frame = ft.id_frame
						GROUP BY c.id_car) as cfilter";

            for ($i=0; $i < count($filter); $i++){
                if ($i==0){
                    $sql.= " WHERE cfilter." . $filter[$i][0] . "= '" . $filter[$i][1] . "'";
                }else {
					$sql.= " AND cfilter." . $filter[$i][0] . "= '" . $filter[$i][1] . "'";
                }        
            }

        $conexion = connect::con();
        $res = mysqli_query($conexion, $sql);
        connect::close($conexion);

        $retrArray = array();
        if ($res -> num_rows > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $retrArray[] = $row;
            }
        }
        return $retrArray;
    }

	function select_load_likes($username){
        $sql = "SELECT l.id_car 
				FROM likes l 
				WHERE l.id_user = (SELECT u.id_user 
									FROM users u 
									WHERE u.username = '$username')";
        $conexion = connect::con();
        $res = mysqli_query($conexion, $sql);
        connect::close($conexion);
        return $res;
    }

	function control_likes($id_car, $username){
        $sql = "CALL control_likes('$id_car','$username')";
        $conexion = connect::con();
        $res = mysqli_query($conexion, $sql);
        connect::close($conexion);
        return $res;
    }
}