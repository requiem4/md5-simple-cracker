<?php

namespace models;

use core\SimpleOrm;

/**
 * Class UserHash
 */
class UserHash extends SimpleOrm
{
	const SALT = 'ThisIs-A-Salt123';
	protected static
		$table = 'not_so_smart_users',
		$pk = 'user_id';

	public static function saveDecodedHashes($decodedHashes) {
		foreach ($decodedHashes as $decodedHash) {
			$usersHash = UserHash::retrieveByField('user_id', $decodedHash['user_id']);
			foreach ($usersHash as $userHash) {
				CheckedPasswords::saveDecodedHash($decodedHash);
				$userHash->decode_password = $decodedHash['password'];
				$userHash->save();
			}
		}
	}
}
