<?php

abstract class page {
protected $html;

public function __construct()
{
$this->html .= '<html>';
$this->html .= '<link rel="stylesheet" href="styles.css">';
$this->html .= '<body>';
}
public function __destruct()
{
$this->html .= '</body></html>';
stringFunctions::printThis($this->html);
}

public function get() {
echo 'default get message';
}

public function post() {
print_r($_POST);
}
}

?>