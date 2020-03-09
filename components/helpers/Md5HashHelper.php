<?php
namespace components\helpers;

class Md5HashHelper
{
	public function combinationUtil($chars, $charsCount, $generatedCharsCount, $index, $data, $i, &$combinations = []) {
		// Current cobination
		if ($index == $generatedCharsCount) {
			$combinations[] = $data;
			return;
		}

		// When no more elements are
		// there to put in data[]
		if ($i >= $charsCount)
			return;

		// current is included, put
		// next at next location
		$data[$index] = $chars[$i];
		$this->combinationUtil($chars, $charsCount, $generatedCharsCount,
			$index + 1,
			$data, $i + 1, $combinations);

		// current is excluded, replace
		// it with next (Note that i+1
		// is passed, but index is not changed)
		$this->combinationUtil($chars, $charsCount, $generatedCharsCount,
			$index, $data, $i + 1, $combinations);
	}
	public function getStringVariations($combination, $changedCombination = [], &$variations = []){
		if (empty($combination)) {
			$variations[] = join('', $changedCombination);
		} else {
			for ($i = count($combination) - 1; $i >= 0; --$i) {
				$newCombination = $combination;
				$newVariation = $changedCombination;
				list($removedElement) = array_splice($newCombination, $i, 1);
				array_unshift($newVariation, $removedElement);
				$this->getStringVariations($newCombination, $newVariation, $variations);
			}
		}
	}

}
