<?php
/**
 * Create by Alex Luzhanovskyi
 */
namespace components;

use components\helpers\Md5HashHelper;
use models\CheckedPasswords;
use models\UserHash;

class Md5HashCracker {
	const CHARS_COUNT = 4;
	const HARD_CHARS_COUNT = 6;
	private static $salt;

	public static function setSalt($salt) {
		self::$salt = $salt;
	}

	public function getMd5Hash($string) {
		return md5($string . self::$salt);
	}

	public function decode($hashes) {
		$decodedHashes = [];
		$decodedHashes = array_merge($decodedHashes, $this->decodeDigitHashes($hashes));
		$decodedHashes = array_merge($decodedHashes, $this->decodeDictionaryWords($hashes));
		$decodedHashes = array_merge($decodedHashes, $this->decodeUppercaseHashes($hashes));
		$decodedHashes = array_merge($decodedHashes, $this->decodeLowercaseHashes($hashes));

		UserHash::saveDecodedHashes($decodedHashes);
		return $decodedHashes;
	}

	public function hardDecode($hashes){
		$decodedHashes = [];
		$decodedHashes = array_merge($decodedHashes, $this->decodeAlphaNumeric($hashes));
		UserHash::saveDecodedHashes($decodedHashes);
		return $decodedHashes;
	}



	private function getNumberHashes($count) {
		$digitsCount = 10;
		$startNumber = pow($digitsCount, $count - 1);
		$endNumber = pow($digitsCount, $count) - 1;
		$numberHashes = [];
		foreach (range($startNumber, $endNumber) as $number) {
			$numberHashes[$number] = $this->getMd5Hash($number);
		}
		return $numberHashes;
	}

	private function getDictionaryWords() {
		$words = [];
		$dictionaryPath = __DIR__ . '/helpers/words.txt';
		if (!file_exists($dictionaryPath)) {
			return $words;
		}
		$dictionary = fopen($dictionaryPath, 'r');
		if ($dictionary) {
			while (($buffer = fgets($dictionary)) !== false) {
				$word = trim($buffer);
				$words[] = $word;
				if (strtolower($word) !== $word) {
					$words[] = strtolower($word);
				}
				/*if (strlen($word) >= self::$count) {

				}*/
				/*$words[] = [
					'word' => $word,
					'hash' => $this->getMd5Hash($word)
				];*/
			}
			if (!feof($dictionary)) {
				echo "Ошибка: fgets() неожиданно потерпел неудачу\n";
			}
			fclose($dictionary);
		}
		return $words;
	}

	private function generatePassword($array, &$combination) {
		$sets[] = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
		$sets[] = 'abcdefghjkmnpqrstuvwxyz';
		$sets[] = '0123456789';
		$helper = new Md5HashHelper();
		do {
			$password = '';

			//append a character from each set - gets first 4 characters
			foreach ($sets as $set) {
				$password .= $set[array_rand(str_split($set))];
			}

			//use all characters to fill up to $len
			while (strlen($password) < self::HARD_CHARS_COUNT) {
				//get a random set
				$randomSet = $sets[array_rand($sets)];

				//add a random char from the random set
				$password .= $randomSet[array_rand(str_split($randomSet))];
			}
			$combination = [];
			$helper->getStringVariations(str_split($password), [], $combination);
		} while (in_array($password, $array));

		return $password;
	}

	protected function decodeDigitHashes($hashes) {
		$numberLength = 5;
		$numberHashes = $this->getNumberHashes($numberLength);
		$decoded = [];
		foreach ($hashes as $hash) {
			$number = array_search($hash['password'], $numberHashes);
			if ($number) {
				$decoded[] = [
					'user_id' => $hash['user_id'],
					'password' => $number,
					'hash' => $hash['password']
				];
			}
		}
		return $decoded;
	}

	protected function decodeDictionaryWords($hashes) {
		$words = $this->getDictionaryWords();
		return $this->getDecodedPasswords($hashes, $words);
	}

	protected function decodeUppercaseHashes($hashes) {
		$alphabetUppercaseRange = range('A', 'Z');
		$digitalRange = range(0, 9);
		$alphabetNumericRange = array_merge($alphabetUppercaseRange, $digitalRange);
		return $this->decodeHashes($hashes, $alphabetNumericRange);
	}

	protected function decodeLowercaseHashes($hashes) {
		$alphabetLowercaseRange = range('a', 'z');
		$digitalRange = range(0, 9);
		$alphabetNumericRange = array_merge($digitalRange, $alphabetLowercaseRange);
		return $this->decodeHashes($hashes, $alphabetNumericRange);
	}

	private function decodeHashes($hashes, $charRange) {
		$combinations = [];
		$helper = new Md5HashHelper();
		$helper->combinationUtil($charRange, sizeof($charRange), self::CHARS_COUNT, 0, [], 0, $combinations);

		$decodedPasswords = [];
		foreach ($combinations as $combination) {
			$variations = [];
			$helper->getStringVariations($combination, [], $variations);
			$decodedPasswords = array_merge($decodedPasswords, $this->getDecodedPasswords($hashes, $variations));
		}
		return $decodedPasswords;
	}

	protected function decodeAlphaNumeric($hashes) {
		$hashNotFound = true;
		$decoded = [];
		$checkedPasswords = CheckedPasswords::allAsArray(['password']);
		while ($hashNotFound) {
			$combinations = [];
			$this->generatePassword($checkedPasswords, $combinations);
			foreach ($combinations as $combination) {
				$checkedHash = $this->checkHash($hashes, $combination, $checkedPasswords);
				if (!empty($checkedHash)
				&& isset($checkedHash['password'])) {
					$hashNotFound = false;
					$decoded[] = $checkedHash;
				}
			}
		}
		return $decoded;
	}

	private function checkHash($hashes, $password, &$checkedPasswords) {
		$md5Hashes = array_column($hashes, 'password');
		$hash = $this->getMd5Hash($password);
		$hashKey = array_search($hash, $md5Hashes);
		CheckedPasswords::saveHash(['hash' => $hash,'password' => $password]);
		$checkedPasswords[] = $password;
		if ($hashKey !== false) {
			return [
				'password' => $password,
				'hash' => $hash,
				'user_id' => $hashes[$hashKey]['user_id']
			];
		}
		return $hashKey;
	}

	private function getDecodedPasswords($hashes, $words) {
		$decodedHashes = [];
		$md5Hashes = array_column($hashes, 'password');
		foreach ($words as $word) {
			$hash = $this->getMd5Hash($word);
			$hashKey = array_search($hash, $md5Hashes);
			if ($hashKey !== false) {
				$decodedHashes[] = [
					'password' => $word,
					'hash' => $hash,
					'user_id' => $hashes[$hashKey]['user_id']
				];
			}
		}
		return $decodedHashes;
	}
}
