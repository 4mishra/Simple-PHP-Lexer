<?php

class Token {
    public $type;
    public $value;

    public function __construct($type, $value) {
        $this->type = $type;
        $this->value = $value;
    }
}

class Lexer {
    private $text;
    private $pos;
    private $currentChar;

    public function __construct($text) {
        $this->text = $text;
        $this->pos = 0;
        $this->currentChar = $this->text[$this->pos];
    }

    private function advance() {
        $this->pos++;
        if ($this->pos < strlen($this->text)) {
            $this->currentChar = $this->text[$this->pos];
        } else {
            $this->currentChar = null;
        }
    }

    private function isWhitespace($char) {
        return $char === ' ' || $char === "\t" || $char === "\n";
    }

    public function getNextToken() {
        while ($this->currentChar !== null) {
            if ($this->isWhitespace($this->currentChar)) {
                $this->advance();
                continue;
            }

            if (is_numeric($this->currentChar)) {
                $number = '';
                while ($this->currentChar !== null && is_numeric($this->currentChar)) {
                    $number .= $this->currentChar;
                    $this->advance();
                }
                return new Token('NUMBER', $number);
            }

            if ($this->currentChar === '+') {
                $this->advance();
                return new Token('PLUS', '+');
            }

            if ($this->currentChar === '-') {
                $this->advance();
                return new Token('MINUS', '-');
            }

            throw new Exception('Invalid character: ' . $this->currentChar);
        }

        return new Token('EOF', null);
    }
}

// Usage example:
$lexer = new Lexer('3 + 12 - 5');
$token = $lexer->getNextToken();
while ($token->type !== 'EOF') {
    echo 'Type: ' . $token->type . ', Value: ' . $token->value . PHP_EOL;
    $token = $lexer->getNextToken();
}
