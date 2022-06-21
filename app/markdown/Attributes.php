<?php

/**
 * Port of the original makdown-it attrs extension:
 * https://github.com/arve0/markdown-it-attrs
 */

namespace App\Markdown;

use Exception;
use Kaoken\MarkdownIt\MarkdownIt;
use Kaoken\MarkdownIt\RulesCore\StateCore;

class Attributes
{
    protected $defaultOptions = [
        'leftDelimiter' => '{',
        'rightDelimiter' => '}',
        'allowedAttributes' => []
    ];

    protected $options, $patterns;

    public function plugin(MarkdownIt $md)
    {
        $this->options = (object)$this->defaultOptions;
        $this->patterns = $this->getPatterns();

        $md->core->ruler->before('linkify', 'curly_attributes', [$this, 'curlyAttrs']);
    }

    protected function getPatterns()
    {
        $__hr = '/^ {0,3}[-*_]{3,} ?' . preg_quote($this->options->leftDelimiter) . '[^' . preg_quote($this->options->rightDelimiter) . ']/';

        /**
         * Does string have properly formatted curly?
         *
         * start: '{.a} asdf'
         * end: 'asdf {.a}'
         * only: '{.a}'
         *
         * @param {string} where to expect {} curly. start, end or only.
         * @return {function(string)} Function which testes if string has curly.
         */
        $hasDelimiters = function ($where) {
            return function ($str) use ($where) {
                // we need minimum three chars, for example {b}
                $minCurlyLength = strlen($this->options->leftDelimiter) + 1 + strlen($this->options->rightDelimiter);
                if (!$str || !is_string($str) || strlen($str) < $minCurlyLength) {
                    return false;
                }

                $validCurlyLength = function($curly) use ($minCurlyLength) {
                    $isClass = $curly[strlen($this->options->leftDelimiter)] === '.';
                    $isId = $curly[strlen($this->options->leftDelimiter)] === '#';
                    return ($isClass || $isId)
                        ? strlen($curly) >= ($minCurlyLength + 1)
                        : strlen($curly) >= $minCurlyLength;
                };

                $rightDelimiterMinimumShift = $minCurlyLength - strlen($this->options->rightDelimiter);
                switch ($where) {
                    case 'start':
                        // first char should be {, } found in char 2 or more
                        $slice = substr($str, 0, strlen($this->options->leftDelimiter));
                        $start = ($slice === $this->options->leftDelimiter) ? 0 : -1;
                        $end = ($start === -1) ? -1 : strpos($str, $this->options->rightDelimiter, $rightDelimiterMinimumShift);
                        // check if next character is not one of the delimiters
                        $nextChar = ($str[$end + strlen($this->options->rightDelimiter)] ?? false);
                        if ($nextChar !== false && strpos($this->options->rightDelimiter, $nextChar) !== false) {
                            $end = -1;
                        }
                        break;

                    case 'end':
                        // last char should be }
                        $start = strrpos($str, $this->options->leftDelimiter);
                        if ($start === false) $start = -1;
                        $end = ($start === -1) ? -1 : strpos($str, $this->options->rightDelimiter, $start + $rightDelimiterMinimumShift);
                        $end = ($end === strlen($str) - strlen($this->options->rightDelimiter)) ? $end : -1;
                        break;

                    case 'only':
                        // '{.a}'
                        $slice = substr($str, 0, strlen($this->options->leftDelimiter));
                        $start = ($slice === $this->options->leftDelimiter) ? 0 : -1;
                        $slice = substr($str, strlen($str) - strlen($this->options->rightDelimiter));
                        $end = ($slice === $this->options->rightDelimiter) ? (strlen($str) - strlen($this->options->rightDelimiter)) : -1;
                        break;

                    default:
                        throw new Exception("Unexpected case $where, expected 'start', 'end' or 'only'");
                }

                return $start !== -1 && $end !== -1 && $validCurlyLength(substr($str, $start, $end - $start + strlen($this->options->rightDelimiter)));

            };
        };

        /**
         * parse {.class #id key=val} strings
         * @param {string} str: string to parse
         * @param {int} start: where to start parsing (including {)
         * @returns {2d array}: [['key', 'val'], ['class', 'red']]
         */
        $getAttrs = function ($str, $start) {
            // not tab, line feed, form feed, space, solidus, greater than sign, quotation mark, apostrophe and equals sign
            $allowedKeyChars = '@[^\t\n\f />"\'=]@';
            $pairSeparator = ' ';
            $keySeparator = '=';
            $classChar = '.';
            $idChar = '#';
          
            $attrs = [];
            $key = '';
            $value = '';
            $parsingKey = true;
            $valueInsideQuotes = false;
          
            // read inside {}
            // start + left delimiter length to avoid beginning {
            // breaks when } is found or end of string
            for ($i = $start + strlen($this->options->leftDelimiter); $i < strlen($str); $i++) {
                if (substr($str, $i, strlen($this->options->rightDelimiter)) === $this->options->rightDelimiter) {
                    if ($key !== '') { $attrs[] = [$key, $value]; }
                    break;
                }
                $char_ = $str[$i];
            
                // switch to reading value if equal sign
                if ($char_ === $keySeparator && $parsingKey) {
                    $parsingKey = false;
                    continue;
                }
            
                // {.class} {..css-module}
                if ($char_ === $classChar && $key === '') {
                    if ($str[$i + 1] === $classChar) {
                        $key = 'css-module';
                        $i += 1;
                    } else {
                        $key = 'class';
                    }
                    $parsingKey = false;
                    continue;
                }
            
                // {#id}
                if ($char_ === $idChar && $key === '') {
                    $key = 'id';
                    $parsingKey = false;
                    continue;
                }
            
                // {value="inside quotes"}
                if ($char_ === '"' && $value === '') {
                    $valueInsideQuotes = true;
                    continue;
                }
                if ($char_ === '"' && $valueInsideQuotes) {
                    $valueInsideQuotes = false;
                    continue;
                }
            
                // read next key/value pair
                if (($char_ === $pairSeparator && !$valueInsideQuotes)) {
                    if ($key === '') {
                        // beginning or ending space: { .red } vs {.red}
                        continue;
                    }
                    $attrs[] = [$key, $value];
                    $key = '';
                    $value = '';
                    $parsingKey = true;
                    continue;
                }
            
                // continue if character not allowed
                if ($parsingKey && preg_match($allowedKeyChars, $char_) === 0) {
                    continue;
                }
            
                // no other conditions met; append to key/value
                if ($parsingKey) {
                    $key .= $char_;
                    continue;
                }
                $value .= $char_;
            }
          
            if ($this->options->allowedAttributes) {
                $allowedAttributes = $this->options->allowedAttributes;
                
                return array_filter($attrs, fn($attr) => in_array($attr[0], $allowedAttributes));

            }
            return $attrs;
          
        };

        /**
         * add attributes from [['key', 'val']] list
         * @param {array} attrs: [['key', 'val']]
         * @param {token} token: which token to add attributes
         * @returns token
         */
        $addAttrs = function ($attrs, $token) {
            foreach ($attrs as $attr) {
              if ($attr[0] === 'class') {
                $token->attrJoin('class', $attr[1]);
              } else if ($attr[0] === 'css-module') {
                $token->attrJoin('css-module', $attr[1]);
              } else {
                $token->attrPush($attr);
              }
            }
            return $token;
        };

        /**
         * Removes last curly from string.
         */
        $removeDelimiter = function ($str) {
            $start = preg_quote($this->options->leftDelimiter);
            $end = preg_quote($this->options->rightDelimiter);
        
            preg_match('/[ \\n]?' . $start . '[^' . $start . $end . ']+' . $end . '$/', $str, $matches, PREG_OFFSET_CAPTURE);

            return (isset($matches[0][1])) ? substr($str, 0, $matches[0][1]) : $str;
        };

        /**
         * find corresponding opening block
         */
        $getMatchingOpeningToken = function ($tokens, $i) {
            if ($tokens[$i]->type === 'softbreak') {
                return false;
            }
            // non closing blocks, example img
            if ($tokens[$i]->nesting === 0) {
                return $tokens[$i];
            }
        
            $level = $tokens[$i]->level;
            $type = str_replace('_close', '_open', $tokens[$i]->type);
        
            for (; $i >= 0; --$i) {
                if ($tokens[$i]->type === $type && $tokens[$i]->level === $level) {
                    return $tokens[$i];
                }
            }
        
            return false;
        };
  
        return [
            (object)[
                'name' => 'fenced code blocks',
                'tests' => [
                    (object)[
                        'shift' => 0,
                        'block' => true,
                        'info' => $hasDelimiters('end')
                    ]
                ],
                'transform' => function ($tokens, $i) use ($getAttrs, $addAttrs, $removeDelimiter) {
                    $token = $tokens[$i];
                    $start = strrpos($token->info, $this->options->leftDelimiter);
                    $attrs = $getAttrs($token->info, $start);
                    $addAttrs($attrs, $token);
                    $token->info = $removeDelimiter($token->info);
                }
            ],
            (object)[
                /**
                 * bla `click()`{.c} ![](img.png){.d}
                 *
                 * differs from 'inline attributes' as it does
                 * not have a closing tag (nesting: -1)
                 */
                'name' => 'inline nesting 0',
                'tests' => [
                    (object)[
                        'shift' => 0,
                        'type' => 'inline',
                        'children' => [
                            (object)[
                                'shift' => -1,
                                'type' => fn ($str) => $str === 'image' || $str === 'code_inline'
                            ],
                            (object)[
                                'shift' => 0,
                                'type' => 'text',
                                'content' => $hasDelimiters('start')
                            ]
                        ]
                    ]
                ],
                'transform' => function ($tokens, $i, $j) use ($getAttrs, $addAttrs)  {
                    $token = $tokens[$i]->children[$j];
                    $endChar = strpos($token->content, $this->options->rightDelimiter);
                    $attrToken = $tokens[$i]->children[$j - 1];
                    $attrs = $getAttrs($token->content, 0);
                    $addAttrs($attrs, $attrToken);
                    if (strlen($token->content) === ($endChar + strlen($this->options->rightDelimiter))) {
                        array_splice($tokens[$i]->children, $j, 1);
                    } else {
                        $token->content = array_slice($token->content, $endChar + strlen($this->options->rightDelimiter));
                    }
                }
            ],
            (object)[
                /**
                 * | h1 |
                 * | -- |
                 * | c1 |
                 *
                 * {.c}
                 */
                'name' => 'tables',
                'tests' => [
                    (object)[
                        // let this token be i, such that for-loop continues at
                        // next token after tokens.splice
                        'shift' => 0,
                        'type' => 'table_close'
                    ],
                    (object)[
                        'shift' => 1,
                        'type' => 'paragraph_open'
                    ],
                    (object)[
                        'shift' => 2,
                        'type' => 'inline',
                        'content' => $hasDelimiters('only')
                    ]
                ],
                'transform' => function($tokens, $i) use ($getAttrs, $addAttrs, $getMatchingOpeningToken) {
                    $token = $tokens[$i + 2];
                    $tableOpen = $getMatchingOpeningToken($tokens, $i);
                    $attrs = $getAttrs($token->content, 0);
                    // add attributes
                    $addAttrs($attrs, $tableOpen);
                    // remove <p>{.c}</p>
                    array_splice($tokens, $i + 1, 3);
                }
            ],
            (object)[
                /**
                 * *emphasis*{.with attrs=1}
                 */
                'name' => 'inline attributes',
                'tests' => [
                    (object)[
                        'shift' => 0,
                        'type' => 'inline',
                        'children' => [
                            (object)[
                                'shift'=> -1,
                                'nesting'=> -1  // closing inline tag, </em>{.a}
                            ],
                            (object)[
                                'shift'=> 0,
                                'type'=> 'text',
                                'content'=> $hasDelimiters('start')
                            ]
                        ]
                    ]
                ],
                'transform' => function ($tokens, $i, $j) use ($getAttrs, $addAttrs, $getMatchingOpeningToken) {
                    $token = $tokens[$i]->children[$j];
                    $content = $token->content;
                    $attrs = $getAttrs($content, 0);
                    $openingToken = $getMatchingOpeningToken($tokens[$i]->children, $j - 1);
                    $addAttrs($attrs, $openingToken);

                    $token->content = substr($content, strpos($content, $this->options->rightDelimiter) + strlen($this->options->rightDelimiter));
                }
            ],
            (object)[
                /**
                 * - item
                 * {.a}
                 */
                'name' => 'list softbreak',
                'tests' => [
                    (object)[
                        'shift' => -2,
                        'type' => 'list_item_open'
                    ],
                    (object)[
                        'shift' => 0,
                        'type' => 'inline',
                        'children' => [
                            (object)[
                                'position' => -2,
                                'type' => 'softbreak'
                            ],
                            (object)[
                                'position' => -1,
                                'type' => 'text',
                                'content' => $hasDelimiters('only')
                            ]
                        ]
                    ]
                ],
                'transform' => function ($tokens, $i, $j) use ($getAttrs, $addAttrs){
                    $token = $tokens[$i]->children[$j];
                    $content = $token->content;
                    $attrs = $getAttrs($content, 0);
                    $ii = $i - 2;
                    while (
                        isset($tokens[$ii - 1]) &&
                        $tokens[$ii - 1]->type !== 'ordered_list_open' &&
                        $tokens[$ii - 1]->type !== 'bullet_list_open'
                    ){
                        $ii--;
                    }
                    $addAttrs($attrs, $tokens[$ii - 1]);
                    $tokens[$i]->children = array_slice($tokens[$i]->children, 0, -2);
                }
            ],
            (object)[
                /**
                 * - nested list
                 *   - with double \n
                 *   {.a} <-- apply to nested ul
                 *
                 * {.b} <-- apply to root <ul>
                 */
                'name' => 'list double softbreak',
                'tests' => [
                    (object)[
                        // let this token be i = 0 so that we can erase
                        // the <p>{.a}</p> tokens below
                        'shift' => 0,
                        'type' => fn($str) => $str === 'bullet_list_close' || $str === 'ordered_list_close'
                    ],
                    (object)[
                        'shift' => 1,
                        'type' => 'paragraph_open'
                    ],
                    (object)[
                        'shift' => 2,
                        'type' => 'inline',
                        'content' => $hasDelimiters('only'),
                        'children' => fn($arr) => count($arr) === 1
                    ],
                    (object)[
                        'shift' => 3,
                        'type' => 'paragraph_close'
                    ]
                ],
                'transform' => function($tokens, $i) use ($getAttrs, $addAttrs, $getMatchingOpeningToken) {
                    $token = $tokens[$i + 2];
                    $content = $token->content;
                    $attrs = $getAttrs($content, 0);
                    $openingToken = $getMatchingOpeningToken($tokens, $i);
                    $addAttrs($attrs, $openingToken);
                    array_splice($tokens, $i + 1, 3);
                }
            ],
            (object)[
                /**
                 * - end of {.list-item}
                 */
                'name' => 'list item end',
                'tests' => [
                    (object)[
                        'shift' => -2,
                        'type' => 'list_item_open'
                    ],
                    (object)[
                        'shift' => 0,
                        'type' => 'inline',
                        'children' => [
                            (object)[
                                'position' => -1,
                                'type' => 'text',
                                'content' => $hasDelimiters('end')
                            ]
                        ]
                    ]
                ],
                'transform' => function($tokens, $i, $j) use ($getAttrs, $addAttrs) {
                    $token = $tokens[$i]->children[$j];
                    $content = $token->content;
                    $attrs = $getAttrs($content, strrpos($content, $this->options->leftDelimiter));
                    $addAttrs($attrs, $tokens[$i - 2]);
                    $trimmed = substr($content, 0, strrpos($content, $this->options->leftDelimiter));
                    $token->content = (substr($trimmed, -1) !== ' ') ? $trimmed : substr($trimmed, 0, -1);
                }
            ],
            (object)[
                /**
                 * something with softbreak
                 * {.cls}
                 */
                'name' => '\n{.a} softbreak then curly in start',
                'tests' => [
                    (object)[
                        'shift' => 0,
                        'type' => 'inline',
                        'children' => [
                            (object)[
                                'position' => -2,
                                'type' => 'softbreak'
                            ],
                            (object)[
                                'position' => -1,
                                'type' => 'text',
                                'content' => $hasDelimiters('only')
                            ]
                        ]
                    ]
                ],
                'transform' => function ($tokens, $i, $j) use ($getAttrs, $addAttrs, $getMatchingOpeningToken) {
                    $token = $tokens[$i]->children[$j];
                    $attrs = $getAttrs($token->content, 0);
                    // find last closing tag
                    $ii = $i + 1;
                    while (isset($tokens[$ii + 1]) && $tokens[$ii + 1]->nesting === -1) { $ii++; }
                    $openingToken = $getMatchingOpeningToken($tokens, $ii);
                    $addAttrs($attrs, $openingToken);
                    $tokens[$i]->children = array_slice($tokens[$i]->children, 0, -2);
                }
            ],
            (object)[
                /**
                 * horizontal rule --- {#id}
                 */
                'name' => 'horizontal rule',
                'tests' => [
                    (object)[
                        'shift' => 0,
                        'type' => 'paragraph_open'
                    ],
                    (object)[
                        'shift' => 1,
                        'type' => 'inline',
                        'children' => fn($arr) => count($arr) === 1,
                        'content' => fn($str) => preg_match($__hr, $str),
                    ],
                    (object)[
                        'shift' => 2,
                        'type' => 'paragraph_close'
                    ]
                ],
                'transform' => function ($tokens, $i) use ($getAttrs, $addAttrs) {
                    $token = $tokens[$i];
                    $token->type = 'hr';
                    $token->tag = 'hr';
                    $token->nesting = 0;
                    $content = $tokens[$i + 1]->content;
                    $start = strrpos($content, $this->options->leftDelimiter);
                    $attrs = $getAttrs($content, $start);
                    $addAttrs($attrs, $token);
                    $token->markup = $content;
                    array_splice($tokens, $i + 1, 2);
                }
            ],
            (object)[
                /**
                 * end of {.block}
                 */
                'name' => 'end of block',
                'tests' => [
                    (object)[
                        'shift' => 0,
                        'type' => 'inline',
                        'children' => [
                            (object)[
                                'position' => -1,
                                'content' => $hasDelimiters('end'),
                                'type' => fn($t) => $t !== 'code_inline' && $t !== 'math_inline'
                            ]
                        ]
                    ]
                ],
                'transform' => function ($tokens, $i, $j) use ($getAttrs, $addAttrs, $getMatchingOpeningToken) {
                    $token = $tokens[$i]->children[$j];
                    $content = $token->content;
                    $attrs = $getAttrs($content, strrpos($content, $this->options->leftDelimiter));
                    $ii = $i + 1;
                    while (isset($tokens[$ii + 1]) && $tokens[$ii + 1]->nesting === -1) { $ii++; }
                    $openingToken = $getMatchingOpeningToken($tokens, $ii);
                    $addAttrs($attrs, $openingToken);
                    $trimmed = substr($content, 0, strrpos($content, $this->options->leftDelimiter));
                    $token->content = (substr($trimmed, -1) !== ' ') ? $trimmed : substr($trimmed, 0, -1);
                }
            ]
        ];
    }


    /**
     * Test if t matches token stream.
     *
     * @param {array} tokens
     * @param {number} i
     * @param {object} t Test to match.
     * @return {object} { match: true|false, j: null|number }
     */
    protected function test($tokens, $i, $t) {
        $res = (object)[
            'match' => false,
            'j' => null
        ];
    
        $ii = isset($t->shift) ? ($i + $t->shift) : $t->position;
    
        if (isset($t->shift) && $ii < 0) {
            // we should never shift to negative indexes (rolling around to back of array)
            return $res;
        }
    
        $token = $this->get($tokens, $ii);  // supports negative ii
    
    
        if (!isset($token)) { return $res; }
    
        foreach ($t as $key => $v) {
            if ($key === 'shift' || $key === 'position') { continue; }
        
            if (!isset($token->$key)) { return $res; }
        
            if ($key === 'children' && $this->isArrayOfObjects($t->children)) {
                if (count($token->children) === 0) {
                    return $res;
                }
                $childTests = $t->children;
                $children = $token->children;
                if (array_reduce($childTests, fn($carry, $tt) => $carry && isset($tt->position), true)) {
                    // positions instead of shifts, do not loop all children
                    $match = array_reduce($childTests, fn($carry, $tt) => $carry && $this->test($children, $tt->position, $tt)->match, true);
                    if ($match) {
                        // we may need position of child in transform
                        $j = last($childTests)->position;
                        $res->j = ($j >= 0) ? $j : count($children) + $j;
                    }
                } else {
                    for ($j = 0; $j < count($children); $j++) {
                        $match = array_reduce($childTests, fn($carry, $tt) => $carry && $this->test($children, $j, $tt)->match, true);
                        if ($match) {
                            $res->j = $j;
                            // all tests true, continue with next key of pattern t
                            break;
                        }
                    }
                }
        
                if ($match === false) { return $res; }
        
                continue;
            }
        
            switch (gettype($t->$key)) {
                case 'boolean':
                case 'integer':
                case 'string':
                    if ($token->$key !== $t->$key) { return $res; }
                    break;
                case 'object':
                case 'array':
                    if(is_object($t->$key) && get_class($t->$key) === 'Closure') {
                        if (!(($t->$key)($token->$key))) { return $res; }
                        break;
                    } else {
                        if ($this->isArrayOfClosures($t->$key)) {
                            $r = array_reduce($t->$key, fn($carry, $tt) => $carry && $tt($token->$key), true);
                        if ($r === false) { return $res; }
                            break;
                        }
                        // fall through for objects !== arrays of functions
                    }

                default:
                    throw new Exception("Unknown type of pattern test (key: $key). Test should be of type boolean, integer, string, closure or array of closures.");
            }
        }
    
        // no tests returned false -> all tests returns true
        $res->match = true;
        return $res;
    }

    function curlyAttrs (StateCore $state) {
        $tokens = $state->tokens;

        for ($i = 0; $i < count($tokens); $i++) {
            for ($p = 0; $p < count($this->patterns); $p++) {
                $pattern = $this->patterns[$p];
                $j = null; // position of child with offset 0
                $match = array_reduce($pattern->tests, function($carry, $t) use ($tokens, $i, &$j){
                    $res = $this->test($tokens, $i, $t);
                    if ($res->j !== null) { $j = $res->j; }
                    return $carry && $res->match;
                }, true);
                if ($match) {
                    ($pattern->transform)($tokens, $i, $j);
                    if ($pattern->name === 'inline attributes' || $pattern->name === 'inline nesting 0') {
                        // retry, may be several inline attributes
                        $p--;
                    }
                }
            }
        }
    }

    protected function get(array $arr, $n) {
        return ($n >= 0) ? ($arr[$n] ?? null) : ($arr[count($arr) + $n] ?? null);
    }
    
    protected function isArrayOfObjects($arr) {
        return is_array($arr) && count($arr) && array_reduce($arr, fn($carry, $el) => $carry && is_object($el), true);
    }

    protected function isArrayOfClosures($arr) {
        return is_array($arr) && count($arr) && array_reduce($arr, fn($carry, $el) => $carry && is_object($el) && get_class($el) === 'Closure', true);
    }
}