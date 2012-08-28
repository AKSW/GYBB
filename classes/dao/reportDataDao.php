<?php
	require_once "classes/dao/baseDao.php";
	require_once "classes/reportData.php";
	
	class ReportDataDao extends BaseDao {
		
		private static $instance = null;
		
		public static function getInstance() {
			if (is_null(self::$instance)) {
				self::$instance = new ReportDataDao();
			}
			return self::$instance;
		}
		
		
		function save($reportData) {
			$this->beginTransaction();	
			$stmt = $this->prepareStatement(
			"INSERT INTO report ( biketype, color, dateoftheft, placeoftheft, description, components, price, manufacturer, size, codednumber, police, user, creationDate) " 
						. "VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )");
			$stmt->bindParam(1, $reportData->biketype);
			$stmt->bindParam(2, $reportData->color);
			$stmt->bindParam(3, $reportData->dateoftheft);
			$stmt->bindParam(4, $reportData->placeoftheft);
			$stmt->bindParam(5, $reportData->description);
			$stmt->bindParam(6, $reportData->components);
			$stmt->bindParam(7, $reportData->price);
			$stmt->bindParam(8, $reportData->manufacturer);
			$stmt->bindParam(9, $reportData->size);
			$stmt->bindParam(10, $reportData->codednumber);
			$stmt->bindParam(11, $reportData->police);
			$stmt->bindParam(12, $reportData->user);
			$stmt->bindParam(13, $reportData->creationDate);
			$stmt->execute();
			
		 	$this->commit();			
		
		}
	}
			
?>
		