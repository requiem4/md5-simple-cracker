<?php

namespace models;

use core\SimpleOrm;

/**
 * Class CheckedPasswords
 */
class CheckedPasswords extends SimpleOrm
{
	protected static
		$table = 'checked_passwords',
		$pk = 'id';

	/**
	 * @param $decodedUserHash
	 * @return bool|mixed|CheckedPasswords
	 */
	public static function saveDecodedHash($decodedUserHash){
		if(empty($decodedUserHash['hash'])){
			return false;
		}
		try{
			$checkedPassword = CheckedPasswords::retrieveByField('md5',$decodedUserHash['hash']);
			if(isset($checkedPassword[0])){
				$checkedPassword = $checkedPassword[0];
			} else {
				$checkedPassword = new CheckedPasswords();
			}
			$checkedPassword->md5 = $decodedUserHash['hash'];
			$checkedPassword->password = $decodedUserHash['password'];
			$checkedPassword->user_id = $decodedUserHash['user_id'];
			$checkedPassword->save();
		}catch (\Exception $exception){

		}
		return $checkedPassword;
	}

	public static function saveHash($hash){
		if(empty($hash['hash'])){
			return false;
		}
		try{
			$checkedPassword = CheckedPasswords::retrieveByField('md5',$hash['hash']);
			if(isset($checkedPassword[0])){
				$checkedPassword = $checkedPassword[0];
			} else {
				$checkedPassword = new CheckedPasswords();
			}
			$checkedPassword->md5 = $hash['hash'];
			$checkedPassword->password = $hash['password'];
			$checkedPassword->save();
		}catch (\Exception $exception){

		}
		return $checkedPassword;
	}
}
