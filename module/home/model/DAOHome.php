<?php
    $path = $_SERVER['DOCUMENT_ROOT'] . '/8_CRUD_MVC v10 proves/8_MVC_CRUD/';
    include($path . "/model/connect.php");
    
	class DAOHome {
		function select_brand() {
			$sql= "SELECT * FROM brand";

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

		function select_categories() {
			$sql= "SELECT * FROM category";

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

		function select_type_motor() {
			$sql= "SELECT * FROM motor_type";

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
	
		function select_visits(){
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
			ORDER BY c.count_visited DESC" ;
    
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
	}