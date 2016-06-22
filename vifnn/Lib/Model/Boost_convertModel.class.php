<?php

	class Boost_convertModel extends Model
	{
		/**
		 * @param $code
		 *
		 * @return bool
		 */
		public function isValideCode ($code)
		{
			$count = $this->where(['convert_code' => $code])->select('count(*) as count');

			return $count['count'] == 0;
		}

		/**
		 * @param $uid
		 * @param $uBoostId
		 *
		 * @return array
		 */
		public function getConvertUseData ($uid, $uBoostId)
		{
			$convertCodeData = [];

			$where = [
				'uid'       => $uid,
				'u_boost_id' => $uBoostId,
			];

			$convertData = D('Boost_convert')->where($where)->select();

			if (!empty($convertData)) {
				foreach ($convertData as $key => $convert) {
					// 统计兑换码兑换情况
					if (isset($convertCodeData[$convert['awards_level']])) {
						$convertCodeData[$convert['awards_level']]['counts']++;
					} else {
						$convertCodeData[$convert['awards_level']]['counts'] = 1;
						$convertCodeData[$convert['awards_level']]['use'] = 0;
					}

					if ($convert['is_use'] == 1) {
						$convertCodeData[$convert['awards_level']]['use']++;
					}
				}
			}

			return $convertCodeData;
		}
	}
