<?php

declare(strict_types=1);

namespace Baraja\Color;


final class Color
{
	public const
		FORMAT_SHORT = 'short',
		FORMAT_LONG = 'long';

	public const FORMATS = [
		self::FORMAT_SHORT,
		self::FORMAT_LONG,
	];

	/** @var array<string, string> */
	private static array $named = [
		'red' => '#f00',
	];


	public static function normalize(string $color, string $format = self::FORMAT_SHORT): string
	{
		if (in_array($format, self::FORMATS, true) === false) {
			throw new \InvalidArgumentException(
				'Format "' . $format . '" is not supported. '
				. 'Did you mean "' . implode('", "', self::FORMATS) . '"?'
			);
		}

		$color = strtolower(trim($color));
		if (preg_match('/^#([0-9a-f]+)$/', $color, $parser) === 1) { // hexa color
			$code = $parser[1] ?? '';
			/** @var array<int, string> $tokens */
			$tokens = str_split($code);
			if (isset($tokens[2])) {
				return '#' . self::canonize($code, $format);
			}
		} elseif (str_starts_with($color, '#')) {
			throw new \InvalidArgumentException('Color "' . $color . '" must contain only [0-9-a-f] characters.');
		}
		if (isset(self::$named[$color])) {
			return self::normalize(self::$named[$color], $format);
		}

		throw new \InvalidArgumentException('Color "' . $color . '" is not valid CSS color.');
	}


	private static function canonize(string $code, string $format): string
	{
		// #aaaaaa => #aaa, #aabbcc => #abc
		/** @var array<int, string> $tokens */
		$tokens = str_split($code);

		if (
			isset($tokens[0], $tokens[1], $tokens[2], $tokens[3], $tokens[4], $tokens[5])
			&& $tokens[0] === $tokens[1]
			&& $tokens[2] === $tokens[3]
			&& $tokens[4] === $tokens[5]
		) { // long color can be short?
			return $format === self::FORMAT_SHORT
				? $tokens[0] . $tokens[2] . $tokens[4]
				: $code;
		}
		if ($format === self::FORMAT_SHORT) {
			if (count($tokens) >= 3) {
				return $tokens[0] . $tokens[1] . $tokens[2];
			}
			throw new \InvalidArgumentException('Color #' . $code . ' can not be short.');
		}
		if (
			$format === self::FORMAT_LONG
			&& isset($tokens[0], $tokens[1], $tokens[2])
		) {
			return isset($tokens[5])
				? $code
				: $tokens[0] . $tokens[0] . $tokens[1] . $tokens[1] . $tokens[2] . $tokens[2];
		}

		return $code;
	}
}
