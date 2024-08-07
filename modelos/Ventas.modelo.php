<?php

require_once "Conexion.php";

class ModeloVenta
{


	/*=============================================
	MOSTRAR VENTAS
	=============================================*/

	static public function mdlMostrarListaVenta($tablaD, $tablaV, $tablaP, $item, $valor)
	{

		if ($item != null) {

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tablaD as d INNER JOIN $tablaV as v on d.id_venta = v.id_venta INNER JOIN $tablaP as p ON p.id_persona = v.id_persona WHERE v.$item = :$item");

			$stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);

			$stmt->execute();

			return $stmt->fetch();
		} else {

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tablaD as d INNER JOIN $tablaV as v on d.id_venta = v.id_venta INNER JOIN $tablaP as p ON p.id_persona = v.id_persona  ORDER BY v.estado_pago DESC");

			$stmt->execute();

			return $stmt->fetchAll();
		}
	}

	/*=============================================
	MOSTRAR SUMA TOTAL DE VENTAS
	=============================================*/

	static public function mdlMostrarSumaTotalVenta($tablaD, $tablaV, $tablaP, $item, $valor)
	{

			$stmt = Conexion::conectar()->prepare("SELECT SUM(total_venta) AS total_ventas FROM $tablaV");

			$stmt->execute();
			
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
	
			return $result['total_ventas'];
		
	}

	/*=============================================
	MOSTRAR SUMA TOTAL DE VENTAS AL CONTADO
	=============================================*/

	static public function mdlMostrarSumaTotalVentaContado($tablaD, $tablaV, $tablaP, $item, $valor)
	{

			$stmt = Conexion::conectar()->prepare("SELECT SUM(total_venta) AS total_ventas_contado FROM $tablaV WHERE tipo_pago = 'credito';");

			$stmt->execute();
			
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
	
			return $result['total_ventas_contado'];
		
	}

	/*=============================================
	MOSTRAR SUMA TOTAL DE VENTAS AL CRÉDITO
	=============================================*/

	static public function mdlMostrarSumaTotalCredito($tablaD, $tablaV, $tablaP, $item, $valor)
	{

			$stmt = Conexion::conectar()->prepare("SELECT SUM(total_venta) AS total_ventas_credito FROM $tablaV WHERE tipo_pago = 'credito';");

			$stmt->execute();
			
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
	
			return $result['total_ventas_credito'];
		
	}

	/*=============================================
	MOSTRAR REPORTE DE VENTAS
	=============================================*/

	static public function mdlMostrarReporteVenta($tablaVentas, $tablaDetalleV, $tablaProducto, $tablaUsuario, $tablaPersona, $fecha_desde,	$fecha_hasta, $id_usuario, $tipo_pago, $descuento_producto) {

		if ($fecha_desde != "" && $fecha_hasta != "" && $id_usuario != null && $tipo_pago == null) {


			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tablaPersona AS p INNER JOIN $tablaVentas AS v ON p.id_persona = v.id_persona	WHERE v.fecha_venta BETWEEN '$fecha_desde' AND '$fecha_hasta' AND v.id_usuario = $id_usuario");

			$stmt->execute();

			return $stmt->fetchAll();

		} else if ($fecha_desde != "" && $fecha_hasta != "" && $id_usuario != null && $tipo_pago != null) {

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tablaPersona AS p INNER JOIN $tablaVentas AS v ON p.id_persona = v.id_persona WHERE v.fecha_venta BETWEEN '$fecha_desde' AND '$fecha_hasta' AND v.id_usuario = $id_usuario AND v.tipo_pago = '$tipo_pago'");

			$stmt->execute();

			return $stmt->fetchAll();

		}else {

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tablaPersona as p INNER JOIN $tablaVentas AS v ON p.id_persona = v.id_persona");

			$stmt->execute();

			return $stmt->fetchAll();
		}
	}

	/*=============================================
	MOSTRAR REPORTE DE VENTAS RANGO DE FECHAS
	=============================================*/

	static public function mdlMostrarReporteVentaRangoFechas($tablaVentas, $tablaDetalleV, $tablaProducto, $tablaUsuario, $tablaPersona, $fecha_desde,	$fecha_hasta, $id_usuario, $tipo_pago, $descuento_producto)
	{

		$stmt = Conexion::conectar()->prepare("SELECT * FROM $tablaPersona AS p INNER JOIN $tablaVentas AS v ON p.id_persona = v.id_persona	WHERE v.fecha_venta BETWEEN '$fecha_desde' AND '$fecha_hasta' AND v.id_usuario = $id_usuario");

		$stmt->execute();

		return $stmt->fetchAll();
		
	}

	/*=============================================
	MOSTRAR REPORTE CREDITO POR CLIENTE
	=============================================*/

	static public function mdlMostrarReporteVentaCreditosCliente($tablaVentas, $tablaDetalleV, $tablaProducto, $tablaUsuario, $tablaPersona, $fecha_desde,	$fecha_hasta, $id_usuario, $tipo_pago, $descuento_producto, $id_cliente_reporte)
	{


		$stmt = Conexion::conectar()->prepare("SELECT * FROM $tablaPersona AS p INNER JOIN $tablaVentas AS v ON p.id_persona = v.id_persona	WHERE v.id_persona = $id_cliente_reporte AND v.tipo_pago = '$tipo_pago'");

		$stmt->execute();

		return $stmt->fetchAll();
		
	}

	/*=============================================
	MOSTRAR REPORTE DE VENTAS POR PRECIOS DE PRODUCTOS
	=============================================*/

	static public function mdlMostrarReporteVentaPrecioProducto($tablaVentas, $tablaDetalleV, $tablaProducto, $tablaUsuario, $tablaPersona, $fecha_desde,	$fecha_hasta, $id_usuario, $tipo_pago, $descuento_producto)
	{

		$stmt = Conexion::conectar()->prepare("SELECT $tablaProducto.nombre_producto, $tablaProducto.precio_producto, $tablaDetalleV.precio_venta, $tablaVentas.fecha_venta, $tablaUsuario.nombre_usuario FROM $tablaProducto INNER JOIN $tablaDetalleV ON $tablaProducto.id_producto = $tablaDetalleV.id_producto INNER JOIN $tablaVentas ON $tablaVentas.id_venta = $tablaDetalleV.id_venta INNER JOIN $tablaUsuario ON $tablaUsuario.id_usuario = $tablaVentas.id_usuario WHERE $tablaProducto.precio_producto != $tablaDetalleV.precio_venta AND $tablaVentas.id_usuario = $id_usuario");

		$stmt->execute();

		return $stmt->fetchAll();
		
	}


	/*=============================================
	MOSTRAR DETALLE VENTAS
	=============================================*/

	static public function mdlMostrarListaDetalleVenta($tablaDV, $tablaP, $item, $valor)
	{

		if ($item != null) {

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tablaDV as dv INNER JOIN $tablaP as p ON dv.id_producto=p.id_producto WHERE $item = :$item");

			$stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);

			$stmt->execute();

			return $stmt->fetchAll();
		} else {

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tablaDV as dv INNER JOIN $tablaP as p ON p.id_producto = dv.id_producto  ORDER BY v.id_venta DESC");

			$stmt->execute();

			return $stmt->fetchAll();
		}
	}



	/*=============================================
	MOSTRAR SERIE Y NUMERO DE COMPRA O EGRESO
	=============================================*/

	static public function mdlMostrarSerieNumero($tabla, $item, $valor)
	{

		$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY id_venta DESC LIMIT 1");

		$stmt->execute();

		return $stmt->fetchAll();

		$stmt = null;
	}

	/*=============================================
	MOSTRAR VENTA
	=============================================*/

	static public function mdlMostrarIdVenta($tabla, $item, $valor)
	{

		if ($item != null) {

			$stmt = Conexion::conectar()->prepare("SELECT * from $tabla WHERE $item = :$item ORDER BY id_venta DESC LIMIT 1");

			$stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);

			$stmt->execute();

			return $stmt->fetch();
		} else {

			$stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY id_venta DESC LIMIT 1");

			$stmt->execute();

			return $stmt->fetchAll();
		}


		$stmt = null;
	}


	/*=============================================
	REGISTRO DE VENTA
	=============================================*/

	static public function mdlIngresarVenta($tabla, $datos)
	{

		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(
                                                                id_persona,
                                                                id_usuario, 
                                                                fecha_venta, 
                                                                tipo_comprobante, 
                                                                serie_comprobante, 
                                                                num_comprobante, 
                                                                impuesto,
                                                                total_venta,
                                                                total_pago,
                                                                sub_total,
                                                                igv,
                                                                tipo_pago,
                                                                estado_pago,
                                                                pago_e_y) 
                                                                VALUES (
                                                                :id_persona, 
                                                                :id_usuario, 
                                                                :fecha_venta, 
                                                                :tipo_comprobante, 
                                                                :serie_comprobante, 
                                                                :num_comprobante, 
                                                                :impuesto,
                                                                :total_venta,
                                                                :total_pago,
                                                                :sub_total,
                                                                :igv,
                                                                :tipo_pago,
                                                                :estado_pago,
                                                                :pago_e_y)");

		$stmt->bindParam(":id_persona", $datos["id_persona"], PDO::PARAM_INT);
		$stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
		$stmt->bindParam(":fecha_venta", $datos["fecha_venta"], PDO::PARAM_STR);
		$stmt->bindParam(":tipo_comprobante", $datos["tipo_comprobante"], PDO::PARAM_STR);
		$stmt->bindParam(":serie_comprobante", $datos["serie_comprobante"], PDO::PARAM_STR);
		$stmt->bindParam(":num_comprobante", $datos["num_comprobante"], PDO::PARAM_STR);
		$stmt->bindParam(":impuesto", $datos["impuesto"], PDO::PARAM_STR);
		$stmt->bindParam(":total_venta", $datos["total_venta"], PDO::PARAM_STR);
		$stmt->bindParam(":total_pago", $datos["total_pago"], PDO::PARAM_STR);
		$stmt->bindParam(":sub_total", $datos["sub_total"], PDO::PARAM_STR);
		$stmt->bindParam(":igv", $datos["igv"], PDO::PARAM_STR);
		$stmt->bindParam(":tipo_pago", $datos["tipo_pago"], PDO::PARAM_STR);
		$stmt->bindParam(":estado_pago", $datos["estado_pago"], PDO::PARAM_STR);
		$stmt->bindParam(":pago_e_y", $datos["pago_e_y"], PDO::PARAM_STR);

		if ($stmt->execute()) {

			return "ok";
		} else {

			return "error";
		}


		$stmt = null;
	}

	/*=============================================
	REGISTRO DETALLE VENTA
	=============================================*/

	static public function mdlIngresarDetalleVenta($tabla, $datos)
	{
		$stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(
													id_venta,
													id_producto, 
													precio_venta, 
													cantidad_u, 
													cantidad_kg) 
												VALUES (
													:id_venta, 
													:id_producto,
													:precio_venta, 
													:cantidad_u, 
													:cantidad_kg)");
	
		$stmt->bindParam(":id_venta", $datos["id_venta"], PDO::PARAM_INT);
		$stmt->bindParam(":id_producto", $datos["id_producto"], PDO::PARAM_INT);
		$stmt->bindParam(":precio_venta", $datos["precio_venta"], PDO::PARAM_STR);
		$stmt->bindParam(":cantidad_u", $datos["cantidad_u"], PDO::PARAM_STR);
		$stmt->bindParam(":cantidad_kg", $datos["cantidad_kg"], PDO::PARAM_STR);
	
		if ($stmt->execute()) {
			// Devolver el id_venta que se acaba de insertar
			return $datos["id_venta"];

		} else {
			
			return "error";  // Devuelve "error" si hay algún problema
		}
	
		$stmt = null;
	}
	


	/*=============================================
	ACTUALIZAR PAGO PENDIENTE
	=============================================*/

	static public function mdlActualizarPagoPendiente($tabla, $datos)
	{
		$respuesta = array();

		// Obtener el total actual de pagos para este egreso
		$stmt = Conexion::conectar()->prepare("SELECT total_pago FROM $tabla WHERE id_venta = :id_venta");
		$stmt->bindParam(":id_venta", $datos["id_venta"], PDO::PARAM_STR);
		$stmt->execute();
		$totalActual = $stmt->fetchColumn();

		// Calcular el nuevo total de pagos sumando el monto proporcionado
		$nuevoTotal = $totalActual + $datos["total_pago"];

		// Obtener el total de la compra
		$stmt = Conexion::conectar()->prepare("SELECT total_venta FROM $tabla WHERE id_venta = :id_venta");
		$stmt->bindParam(":id_venta", $datos["id_venta"], PDO::PARAM_STR);
		$stmt->execute();
		$totalCompra = $stmt->fetchColumn();

		// Verificar si el nuevo total de pagos supera el total de la compra
		if ($nuevoTotal > $totalCompra) {
			// Si supera el total de la compra, retornar error
			$respuesta["estado"] = "error";
			$respuesta["mensaje"] = "El total de los pagos supera el total de la venta";
		} else {
			// Si no supera el total de la compra, proceder con la actualización
			$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET total_pago = :nuevo_total_pago WHERE id_venta = :id_venta");
			$stmt->bindParam(":nuevo_total_pago", $nuevoTotal, PDO::PARAM_STR);
			$stmt->bindParam(":id_venta", $datos["id_venta"], PDO::PARAM_STR);

			if ($stmt->execute()) {
				$respuesta["estado"] = "ok";
				$respuesta["mensaje"] = "El pago se realizó correctamente";
			} else {
				$respuesta["estado"] = "error";
				$respuesta["mensaje"] = "No se pudo realizar el total de pagos";
			}
		}

		return $respuesta;
	}


	/*=============================================
	EDITAR VENTA
	=============================================*/

	static public function mdlEditarVenta($tabla, $datos)
	{

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
																id_persona = :id_persona, 
																id_usuario = :id_usuario, 
																fecha_venta = :fecha_venta, 
																tipo_comprobante = :tipo_comprobante, 
																serie_comprobante = :serie_comprobante, 
																num_comprobante = :num_comprobante, 
																impuesto = :impuesto,
																total_venta = :total_venta,
																total_pago = :total_pago,
																sub_total = :sub_total,
																igv = :igv,
																tipo_pago = :tipo_pago,
																estado_pago = :estado_pago,
																pago_e_y = :pago_e_y
																WHERE id_venta = :id_venta");

		$stmt->bindParam(":id_persona", $datos["id_persona"], PDO::PARAM_INT);
		$stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
		$stmt->bindParam(":fecha_venta", $datos["fecha_venta"], PDO::PARAM_STR);
		$stmt->bindParam(":tipo_comprobante", $datos["tipo_comprobante"], PDO::PARAM_STR);
		$stmt->bindParam(":serie_comprobante", $datos["serie_comprobante"], PDO::PARAM_STR);
		$stmt->bindParam(":num_comprobante", $datos["num_comprobante"], PDO::PARAM_STR);
		$stmt->bindParam(":impuesto", $datos["impuesto"], PDO::PARAM_STR);
		$stmt->bindParam(":total_venta", $datos["total_venta"], PDO::PARAM_STR);
		$stmt->bindParam(":total_pago", $datos["total_pago"], PDO::PARAM_STR);
		$stmt->bindParam(":sub_total", $datos["sub_total"], PDO::PARAM_STR);
		$stmt->bindParam(":igv", $datos["igv"], PDO::PARAM_STR);
		$stmt->bindParam(":tipo_pago", $datos["tipo_pago"], PDO::PARAM_STR);
		$stmt->bindParam(":estado_pago", $datos["estado_pago"], PDO::PARAM_STR);
		$stmt->bindParam(":pago_e_y", $datos["pago_e_y"], PDO::PARAM_STR);
		$stmt->bindParam(":id_venta", $datos["id_venta"], PDO::PARAM_INT);

		if ($stmt->execute()) {

			return "ok";
		} else {

			return "error";
		}


		$stmt = null;
	}

	/*=============================================
	EDITAR DETALLE VENTA
	=============================================*/

	static public function mdlEditarDetalleVenta($tabla, $datos)
	{

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
																id_producto = :id_producto, 
																precio_venta = :precio_venta, 
																cantidad_u = :cantidad_u, 
																cantidad_kg = :cantidad_kg
																WHERE id_venta = :id_venta");

		$stmt->bindParam(":id_producto", $datos["id_producto"], PDO::PARAM_INT);
		$stmt->bindParam(":precio_venta", $datos["precio_venta"], PDO::PARAM_STR);
		$stmt->bindParam(":cantidad_u", $datos["cantidad_u"], PDO::PARAM_INT);
		$stmt->bindParam(":cantidad_kg", $datos["cantidad_kg"], PDO::PARAM_STR);

		$stmt->bindParam(":id_venta", $datos["id_venta"], PDO::PARAM_INT);

		if ($stmt->execute()) {

			return "ok";
		} else {

			return "error";
		}


		$stmt = null;
	}

	/*=============================================
	ACTUALIZAR VENTA
	=============================================*/

	static public function mdlActualizarVenta($tabla, $item1, $valor1, $item2, $valor2)
	{

		$stmt = Conexion::conectar()->prepare("UPDATE $tabla SET $item1 = :$item1 WHERE $item2 = :$item2");

		$stmt->bindParam(":" . $item1, $valor1, PDO::PARAM_STR);
		$stmt->bindParam(":" . $item2, $valor2, PDO::PARAM_STR);

		if ($stmt->execute()) {

			return "ok";
		} else {

			return "error";
		}


		$stmt = null;
	}

	/*=============================================
	ACTUALIZAR STOCK PRODUCTO
	=============================================*/

	static public function mdlActualizarStockProducto($tblProducto, $idProducto, $cantidad)
	{

		$stmt = Conexion::conectar()->prepare("UPDATE $tblProducto SET stock_producto = stock_producto - :cantidad WHERE id_producto = :id_producto");

		$stmt->bindParam(":cantidad", $cantidad, PDO::PARAM_INT);
		$stmt->bindParam(":id_producto", $idProducto, PDO::PARAM_INT);

		if ($stmt->execute()) {
			return "ok";
		} else {
			return "error";
		}

		$stmt = null;
	}


	/*=============================================
	BORRAR VENTA
	=============================================*/

	static public function mdlBorrarVenta($tabla, $datos)
	{

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id_venta = :id_venta");

		$stmt->bindParam(":id_venta", $datos, PDO::PARAM_INT);

		if ($stmt->execute()) {

			return "ok";
		} else {

			return "error";
		}


		$stmt = null;
	}

	/*=============================================
	BORRAR DETALLE VENTA
	=============================================*/

	static public function mdlBorrarDetalleVenta($tabla, $datos)
	{

		$stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id_venta = :id_venta");

		$stmt->bindParam(":id_venta", $datos, PDO::PARAM_INT);

		if ($stmt->execute()) {

			return "ok";
		} else {

			return "error";
		}


		$stmt = null;
	}
}
