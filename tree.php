<?php

// Класс для представления нечеткого числа
class FuzzyNumber {
    public $a;
    public $b;
    public $c;
    public $d;

    public function __construct($a, $b, $c, $d) {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
        $this->d = $d;
    }

    // Метод для деффазификации нечеткого числа методом центра площади
    public function defuzzify() {
        $h = ($this->c - $this->b) / ($this->d - $this->b);
        $x = $this->b + ($h * ($this->c - $this->b));
        return round($x, 1);
    }
}

// Класс для представления узла нечеткого бинарного дерева
class FuzzyTreeNode {
    public $value;
    public $left;
    public $right;
    public $parent;

    public function __construct($value) {
        $this->value = $value;
        $this->left = null;
        $this->right = null;
        $this->parent = null;
    }

    // Метод для добавления узла в нечеткое бинарное дерево
    public function insert($node) {
        if ($node->value->defuzzify() < $this->value->defuzzify()) {
            if($this->left == null) {
                $this->left = $node;
                $node->parent = $this;
            } else {
                $this->left->insert($node);
            }   
        } else {
            if($this->right == null) {
                $this->right = $node;
                $node->parent = $this;
            } else {
                $this->right->insert($node);
            }  
        }
    }
}

// Функция для вывода нечеткого бинарного дерева
function printFuzzyTreeHtml($node, $level = 0) {
    if ($node == null) {
        return;
    }

    $defuzzifiedValue = round($node->value->defuzzify());
    $indent = str_repeat("  ", $level);

    echo "<ul>\n";
    echo "$indent<li>\n";
    echo "  <span class=\"value\">$defuzzifiedValue</span>\n";

    if ($node->left != null) {
        echo "  <span class=\"arrow left-arrow\"></span>\n";
    } else {
        echo "  <span class=\"empty left-empty\"></span>\n";
    }

    if ($node->right != null) {
        echo "  <span class=\"arrow right-arrow\"></span>\n";
    } else {
        echo "  <span class=\"empty right-empty\"></span>\n";
    }

    echo "</li>\n";

    printFuzzyTreeHtml($node->left, $level + 1);
    printFuzzyTreeHtml($node->right, $level + 1);

    echo "</ul>\n";
}

// Получаем массив строк из файла
$lines = file('input.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Перебираем строки, разбираем их на массив чисел и выводим на экран
$i = 0;
if(count($lines)<7) {
    echo "Нечетких чисел меньше 7!!!";
    exit;
}
foreach ($lines as $line) {
    $numbers = array_map('intval', explode(' ', $line));
    if($i == 0) {
        $fuzzyTree = new FuzzyTreeNode(new FuzzyNumber((int)$numbers[0],(int)$numbers[1],(int)$numbers[2],(int)$numbers[3]));
    } else {
        $fuzzyTree->insert(new FuzzyTreeNode(new FuzzyNumber((int)$numbers[0],(int)$numbers[1],(int)$numbers[2],(int)$numbers[3])));
    }
    $i++;
}

// Вывод нечеткого бинарного дерева до добавления узла
echo "Нечеткое бинарное дерево до добавления узла:<br>";
printFuzzyTreeHtml($fuzzyTree);

// Добавление узла в нечеткое бинарное дерево
$fuzzyTree->insert(new FuzzyTreeNode(new FuzzyNumber(3, 5, 6, 7)));

// Вывод нечеткого бинарного дерева после добавления узла
echo "Нечеткое бинарное дерево после добавления узла:<br>";
printFuzzyTreeHtml($fuzzyTree);

