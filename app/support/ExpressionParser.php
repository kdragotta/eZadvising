<?php
/**
 * Created by PhpStorm.
 * User: phillip
 * Date: 9/24/15
 * Time: 4:11 PM
 */

class ExpressionNode {
    const PREREQ = 0;
    const _AND = 1;
    const _OR = 2;
    private $type;
    /**
     * @var null
     */
    private $value;

    public $left;
    public $right;

    /**
     * ExpressionNode constructor.
     * @param $type : An integer for defining the type of this node 0=Prereq, 1=AND, 2=OR
     * @param null $value : Should only be set if type = 0
     */
    public function __construct($type, $value=null)
    {
        $this->type = $type;
        $this->value = $value;
        if ($value!=null and $type!=0) {
            throw new LogicException("A value can only be set if type is 0: type=$type and value=$value");
        }
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return null
     */
    public function getValue()
    {
        return $this->value;
    }


}

class _Token {
    const VALUE = 0;
    const _AND = 1;
    const _OR  = 2;
    const OPENGRP = 3;
    const CLOSEGRP = 4;

    private $type;
    private $value;

    /**
     * _Token constructor.
     */
    public function __construct($type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function getTokenName() {
        $reflect = new ReflectionClass('_Token');
        $constants = $reflect->getConstants();
        foreach ($constants as $name => $val ) {
            if ($this->type == $val) {return $name;}
        }
        return "Unknown token type $this->type";
    }

    public static function getTokenNameFromType($type) {
        $reflect = new ReflectionClass('_Token');
        $constants = $reflect->getConstants();
        foreach ($constants as $name => $val ) {
            if ($type == $val) {return $name;}
        }
        return "Unknown token type $type";
    }

}

function ParseExpression ($expr) {
    $expr = strtoupper(trim($expr)); //Clean up expression
    $tokens = _tokenizer($expr);
    return _makeTree($tokens); //Generate the tree
}

function _makeTree(Iterator $tokens)
{
    //TODO: Handle case where there is only one token
    $lasttoken = -1;
    $validtoken = [];
    $nodes = [null, null, null];
    $root = null;
    $i=0;
    while ($tokens->valid()) {
        $token = $tokens->current();
        switch ($lasttoken) {
            case -1:
            case _Token::_AND:
            case _Token::_OR:
                $validtoken = [_Token::VALUE, _Token::OPENGRP];
                break;
            case _Token::VALUE:
            case _Token::CLOSEGRP:
                $validtoken = [_Token::_OR, _Token::_AND];
                break;
        }
        if (!in_array($token->getType(), $validtoken)) {
            $tnames = array_map(function($type) {return _Token::getTokenNameFromType($type);}, $validtoken);
            throw new LogicException("Unexpected token $token->getTokenName() expected any of $tnames");
        }
        switch ($token->getType()) {
            case _Token::VALUE:
                $nodes[$i] = new ExpressionNode(ExpressionNode::PREREQ, $token->getValue());
                break;
            case _Token::_AND:
                $nodes[$i] = new ExpressionNode(ExpressionNode::_AND);
                break;
            case _Token::_OR:
                $nodes[$i] = new ExpressionNode(ExpressionNode::_OR);
                break;
            case _Token::OPENGRP:
                $group = [];
                $tokens->next();
                while ($tokens->current()->getType() != _Token::CLOSEGRP and $tokens->current()->getValue() != $token->getValue()) {
                    if (!$tokens->valid()) {throw new LogicException("Unexpected EOF missing closing parentheses");}
                    $group[] = $tokens->current();
                    $tokens->next();
                }
                $nodes[$i] = _makeTree(new ArrayIterator($group));
                $token = $tokens->current();
        }
        if ($i == 2) {
            $root = $nodes[1];
            $root->left = $nodes[0];
            $root->right = $nodes[2];
            $nodes[0] = $root;
            $i=0;
        }
        $i++;
        $lasttoken = $token->getType();
        $tokens->next();
    }
    return $root;

}

function _tokenizer($expr){
    $state = 0; //0 = default 1=building number 2=building and 3=building or
    $cache = "";
    $i = 0;
    $nest = -1;
    //Iterate through the expression character by character
    foreach (str_split(strtoupper($expr)) as $c) {
        if (is_numeric($c)) {  //Found a number
            if ($state == 0){  //If we're in default state
                $state = 1; //Set to number state
                $cache = $c; //Set cache to the number we found
            } elseif ($state == 1) { //If we're already in number state
                $cache .= $c; //Append the next number to the cache
            } else {  //Finding a number in any other state is invalid
                throw new LogicException("Unexpected character $c while parsing expression at position $i \nExpression: $expr");
            }
        } elseif ($c == "A") {  //If we found an a
            if ($state == 0) {  //We should be in the default state
                $state = 2; //Go to and state
                $cache = $c; //Set cache to A
            } else { //We shouldn't find an a anywhere else
                throw new LogicException("Unexpected character $c while parsing expression at position $i \nExpression: $expr");
            }
        } elseif ($c == "O") {  //If we find an o
            if ($state == 0) {  //We should be in the default state
                $state = 3; //Set to or state
                $cache = $c; //Set cache to o
            } else { //We shouldn't find an O anywhere else
                throw new LogicException("Unexpected character $c while parsing expression at position $i \nExpression: $expr");
            }
        } elseif ($c == "(") { //If we found an open parentheses
            if ($state != 0) { //We shouldn't find one in any state except the default
                throw new LogicException("Unexpected character $c while parsing expression at position $i \nExpression: $expr");
            }
            $nest++; //Increase the nesting value
            yield new _Token(_Token::OPENGRP, $nest);  //Yield the open group token
        } elseif ($c == ")") {  //If we found a closing parentheses
            if ($state == 1) {  //We can be in the number state
                yield new _Token(_Token::VALUE, (int)$cache);  //Yield the number token
                $cache=""; //Reset the cache
                $state = 0; //Go back to the default state
            }
            if ($state != 0 or $nest < 0) { //If we're not in the default state or if the nesting level is less than zero we have a problem
                throw new LogicException("Unexpected character  $c while parsing expression at position $i");
            }
            yield new _Token(_Token::CLOSEGRP, $nest); //Yield the close group token
            $nest--; //Decrease the nesting level
        } elseif ($c == " ") { //If we find a space
            if ($state == 0) {continue;} //And we're in the default state consume the space
            //Any other state: finish off and yield the proper token
            elseif ($state == 1) {
                yield new _Token(_Token::VALUE, (int)$cache);
            } elseif ($state == 2 and $cache == "D") { //Check if the and has been properly finished
                yield new _Token(_Token::_AND, null);
            } elseif ($state == 3 and $cache == "R") { //Check if the or has been properly finished
                yield new _Token(_Token::_OR, null);
            } else { //If none of the above is true we have a problem
                throw new LogicException("Unexpected character $c while parsing expression at position $i \nExpression: $expr");
            }
            //Reset the cache and the state
            $cache="";
            $state = 0;
        } else { //Any other character
            //See if its an expected part of an and
            if ($state == 1 and (($cache == "A" and $c == "N") or ($cache=="N" and $c == "D"))) {$cache = $c;}
            //See if its an expected part of an or
            elseif ($state == 2 and $cache=="O" and $c == "R") {$cache = $c;}
            //If not we have a problem
            else {
                throw new LogicException("Unexpected character $c while parsing expression at position $i \nExpression: $expr");
            }
        }
    }
    //We've hit the end of the expression
    if ($state == 1) {
        //If we're at the end and we're building a number, finish the number and yield the token
        yield new _Token(_Token::VALUE, (int)$cache);
        $state = 0;
    }
    if ($state != 0) { //We shouldn't be in any other state at this point
        throw new LogicException("Unexpected EOL when parsing expression");
    }
}