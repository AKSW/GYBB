<?php
	require_once "classes/dao/baseDao.php";
	require_once "classes/user.php";
	
	class UserDao extends BaseDao {
		
		private static $instance = null;
		
		public static function getInstance() {
			if (is_null(self::$instance)) {
				self::$instance = new UserDao();
			}
			return self::$instance;
		}
		
		
		function checkUser($oauthId, $oauthProvider) {
			
			$stmt = $this->prepareStatement('SELECT * FROM users WHERE oauth_uid = ? and  oauth_provider = ?');
			$stmt->bindParam(1, $oauthId);
			$stmt->bindParam(2,$oauthProvider);
			
			if($stmt->execute()) {
				if($row = $stmt->fetch()) {
					return new User($row);
				}
				
			}
			return new User(null);
		}
		
		
		function save($user) {
			$this->beginTransaction();
			if(isset($user->id)) {
				// update
				$stmt = $this->prepareStatement("UPDATE users SET email = ?, oauth_uid = ?, oauth_provider = ?, username = ? WHERE id = ? ");
				$this->bindParameters($user, $stmt);
				$stmt->bindParam(5, $user->id);
				$stmt->execute();
				
			} else {
				//insert
				$stmt = $this->prepareStatement("INSERT INTO users ( email , oauth_uid , oauth_provider , username ) VALUES (?,?,?,?) ");
				$this->bindParameters($user, $stmt);
				$stmt->execute();
				$user->id = $this->lastInsertId();	
			}
			
				
			
			$this->commit();
			return $user;
		} 			
		
		
		private function bindParameters($user, $stmt) {
			$stmt->bindParam(1, $user->email);
			$stmt->bindParam(2, $user->oauthId);
			$stmt->bindParam(3, $user->oauthProvider);
			$stmt->bindParam(4, $user->name);
			
			
		}
	}
	



?>