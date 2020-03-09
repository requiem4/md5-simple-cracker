<?php

namespace controllers;
use components\Md5HashCracker;
use core\Controller;
use models\UserHash;

class MainController extends Controller
{

	public function index(){


		$hashes = UserHash::all(['order' => ['decode_password' => 'DESC']]);
		$hashes = UserHash::objectsToArray($hashes);

		return $this->render('checked-passwords/checked-passwords', ['hashes' => $hashes]);
	}

	public function runDecode(){
		$md5HashCracker = new Md5HashCracker();
		$md5HashCracker::setSalt(UserHash::SALT);
		$hashes = UserHash::all();
		$hashes = UserHash::objectsToArray($hashes);
		$md5HashCracker->decode($hashes);
		return $this->index();
	}

	public function runHardDecode(){
		$md5HashCracker = new Md5HashCracker();
		$md5HashCracker::setSalt(UserHash::SALT);
		$hashes = UserHash::all();
		$hashes = UserHash::objectsToArray($hashes);
		return $md5HashCracker->hardDecode($hashes);
	}

	public function flushPasswords(){
		$hashes = UserHash::all();
		foreach ($hashes as $hash) {
			$hash->decode_password = '';
			$hash->save();
		}
		$hashes = UserHash::all(['order' => ['decode_password' => 'DESC']]);
		$hashes = UserHash::objectsToArray($hashes);

		return $this->render('checked-passwords/checked-passwords', ['hashes' => $hashes]);
	}
}
