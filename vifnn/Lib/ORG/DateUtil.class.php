<?php

	/**
	 * Created by PhpStorm.
	 * User: Dove Chen
	 * Date: 16-3-3
	 * Time: 下午1:22
	 */
	class DateUtil
	{

		const SHORTEST_DATE_FORMAT = 'Ymd';
		const SHORT_DATE_FIRST = 'Y-m-01';
		const SHORT_DATE_FORMAT = 'Y-m-d';
		const FULL_DATE_FORMAT = 'Y-m-d H:i:s';

		public static function calculateAge ($birthDay)
		{
			if (empty($birthDay)) {
				return 0;
			}

			return date_diff(date_create($birthDay), date_create('now'))->y;
		}

		public static function getCurrentYMD ()
		{
			return date(static::SHORT_DATE_FORMAT);
		}

		public static function getCurrentTime ()
		{
			return date(static::FULL_DATE_FORMAT);
		}

		public static function getPreviousSecondsTime ($date, $seconds)
		{
			$dateObj = date_create($date);
			date_sub($dateObj, date_interval_create_from_date_string("1 seconds"));

			return date_format($dateObj, static::FULL_DATE_FORMAT);
		}

		public static function isToday ($date)
		{
			$tmp = date_create($date);

			return static::getCurrentYMD() == date_format($tmp, static::SHORT_DATE_FORMAT);
		}

		public static function isSameDay ($d1, $d2)
		{
			$tmp1 = date_create($d1);
			$tmp2 = date_create($d2);

			return date_format($tmp1, static::SHORT_DATE_FORMAT) == date_format($tmp2, static::SHORT_DATE_FORMAT);
		}

		/**
		 * @param null $ymd
		 * @param int  $day
		 *
		 * @return bool|string
		 */
		public static function getPreviousYMD ($ymd = NULL, $day = 1)
		{
			if (is_null($ymd)) {
				$ymd = static::getCurrentYMD();
			}
			$date = date_create($ymd);
			date_sub($date, date_interval_create_from_date_string($day . " days"));

			return date_format($date, static::SHORT_DATE_FORMAT);
		}

		public static function getNextYMD ($ymd = NULL, $day = 1)
		{
			if (is_null($ymd)) {
				$ymd = static::getCurrentYMD();
			}

			$date = date_create($ymd);
			date_add($date, date_interval_create_from_date_string("1 days"));

			return date_format($date, static::SHORT_DATE_FORMAT);
		}

		public static function dateAddSeconds ($date, $interval)
		{
			$obj = date_create($date);
			date_add($obj, date_interval_create_from_date_string($interval . " seconds"));

			return date_format($obj, static::FULL_DATE_FORMAT);
		}

		public static function dateDiffSeconds ($d1, $d2)
		{
			return strtotime($d2) - strtotime($d1);
		}

		public static function getFormattedYMD ($ymd)
		{
			$unixTime = strtotime($ymd);
			if (!$unixTime)
				return false;

			return date(static::SHORT_DATE_FORMAT, $unixTime);
		}

		public static function getNoDashFormattedYMD ($time)
		{
			$unixTime = strtotime($time);
			if (!$unixTime)
				return false;

			return date(static::SHORTEST_DATE_FORMAT, $unixTime);
		}

		public static function getMaxTimeInDay ($ymd)
		{
			return date(DateUtil::FULL_DATE_FORMAT, strtotime($ymd . ' 23:59:59'));
		}

		public static function getMinTimeInDay ($ymd)
		{
			return date(DateUtil::FULL_DATE_FORMAT, strtotime($ymd . ' 00:00:00'));
		}

		public static function getFirstDayInMonth ($ymd = NULL)
		{
			if (is_null($ymd)) {
				$ymd = static::getCurrentYMD();
			}

			return date(DateUtil::SHORT_DATE_FIRST, strtotime($ymd));
		}

		public static function getLastDayInMonth ($ymd = NULL)
		{
			if (is_null($ymd)) {
				$ymd = static::getCurrentYMD();
			}

			$firstDay = static::getFirstDayInMonth($ymd);

			return date(DateUtil::SHORT_DATE_FORMAT, strtotime($firstDay . " +1 month -1 day"));
		}

		public static function getTimezoneOffset ($strTimezone)
		{
			$timeZone = new DateTimeZone($strTimezone);
			$datetimeNow = new DateTime('now', $timeZone);

			return $datetimeNow->format('P');
		}

		/**
		 * @param      $date1
		 * @param null $date2
		 *
		 * @return string
		 * @throws HttpInvalidParamException
		 */
		public static function getDiffText ($date1, $date2 = NULL)
		{
			if (is_null($date2)) {
				$date2 = static::getCurrentTime();
			}

			$diffSeconds = static::dateDiffSeconds($date1, $date2);

			if ($diffSeconds < 0) {
				throw new HttpInvalidParamException("date two must abort date one");
			} elseif ($diffSeconds == 0) {
				return '刚刚';
			} elseif ($diffSeconds < 1 * 60) {
				return $diffSeconds . '秒前';
			} else {
				$minute = floor($diffSeconds / 60);

				if ($minute < 60) {
					return $minute . "分钟前";
				} else {
					$hour = floor($minute / 60);

					if ($hour < 24) {
						return $hour . '小时前';
					} else {
						$day = floor($hour / 24);

						if ($day < 365) {
							return $day . '天前';
						} else {
							$year = floor($day / 365);

							return $year . '年前';
						}
					}
				}
			}
		}

		/**
		 * @param $timeString
		 *
		 * @return string
		 */
		public static function getTextFromSeconds ($timeString)
		{
			$hour = $minute = $seconds = 0;
			if (floor($timeString / 60 / 60) > 0) {
				$hour = floor($timeString / 60 / 60);
				$minute = floor($timeString / 60 - $hour * 60);
				$seconds = $timeString - $hour * 60 * 60 - $minute * 60;
			} elseif (floor($timeString / 60) > 0) {
				$minute = floor($timeString / 60);
				$seconds = $timeString - $minute * 60;
			} else {
				$seconds = $timeString;
			}

			if ($hour == 0) {
				if ($minute == 0) {
					$result = $seconds . '秒';
				} elseif ($seconds == 0) {
					$result = $minute . '分钟';
				} else {
					$result = $minute . '分钟' . $seconds . '秒';
				}
			} elseif ($minute == 0) {
				if ($seconds == 0) {
					$result = $hour . '小时';
				} else {
					$result = $hour . '小时' . $seconds . '秒';
				}
			} elseif ($seconds == 0) {
				$result = $hour . '小时' . $minute . '分钟';
			} else {
				$result = $hour . '小时' . $minute . '分钟' . $seconds . '秒';
			}

			return $result;
		}
	}