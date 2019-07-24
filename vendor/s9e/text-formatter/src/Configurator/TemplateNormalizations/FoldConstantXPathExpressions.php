<?php

/**
* @package   s9e\TextFormatter
* @copyright Copyright (c) 2010-2019 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\TextFormatter\Configurator\TemplateNormalizations;

use s9e\TextFormatter\Utils\XPath;

class FoldConstantXPathExpressions extends AbstractConstantFolding
{
	/**
	* @var string[] List of supported XPath functions
	*/
	protected $supportedFunctions = [
		'ceiling',
		'concat',
		'contains',
		'floor',
		'normalize-space',
		'number',
		'round',
		'starts-with',
		'string',
		'string-length',
		'substring',
		'substring-after',
		'substring-before',
		'sum',
		'translate'
	];

	/**
	* {@inheritdoc}
	*/
	protected function getOptimizationPasses()
	{
		return [
			'(^(?:"[^"]*"|\'[^\']*\'|\\.[0-9]|[^"$&\'./:<=>@[\\]])++$)' => 'foldConstantXPathExpression'
		];
	}

	/**
	* Test whether a value can be serialized to an XPath literal
	*
	* @param  mixed $value
	* @return bool
	*/
	protected function canBeSerialized($value)
	{
		return (is_string($value) || is_integer($value) || is_float($value));
	}

	/**
	* Evaluate given expression without raising any warnings
	*
	* @param  string $expr
	* @return mixed
	*/
	protected function evaluate($expr)
	{
		$useErrors = libxml_use_internal_errors(true);
		$result    = $this->xpath->evaluate($expr);
		libxml_use_internal_errors($useErrors);

		return $result;
	}

	/**
	* Evaluate and replace a constant XPath expression
	*
	* @param  array  $m
	* @return string
	*/
	protected function foldConstantXPathExpression(array $m)
	{
		$expr = $m[0];
		if ($this->isConstantExpression($expr))
		{
			$result = $this->evaluate($expr);
			if ($this->canBeSerialized($result))
			{
				$foldedExpr = XPath::export($result);
				if (strlen($foldedExpr) < strlen($expr))
				{
					$expr = $foldedExpr;
				}
			}
		}

		return $expr;
	}

	/**
	* Test whether given expression seems to be constant
	*
	* @param  string $expr
	* @return bool
	*/
	protected function isConstantExpression($expr)
	{
		// Replace strings to avoid false-positives
		$expr = preg_replace('("[^"]*"|\'[^\']*\')', '0', $expr);

		// Match function calls against the list of supported functions
		preg_match_all('(\\w[-\\w]+(?=\\())', $expr, $m);
		if (count(array_diff($m[0], $this->supportedFunctions)) > 0)
		{
			return false;
		}

		// Match unsupported characters and keywords, as well as function calls without arguments
		return !preg_match('([^\\s\\-0-9a-z\\(-.]|\\.(?![0-9])|\\b[-a-z](?![-\\w]+\\()|\\(\\s*\\))i', $expr);
	}
}