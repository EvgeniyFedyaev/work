<?
session_start();
class Farm
{
    private $numChicken = 20, $numCow = 10;
    public function __construct()
    {
        if (!isset($_SESSION['numChicken']))
        {
            $_SESSION['numChicken'] = $this->numChicken;
        }
        if (!isset($_SESSION['numCow']))
        {
            $_SESSION['numCow'] = $this->numCow;
        }
        if (!isset($_SESSION['numChickenEgg']))
        {
            $_SESSION['numChickenEgg'] = 0;
        }
        if (!isset($_SESSION['numMilkL']))
        {
            $_SESSION['numMilkL'] = 0;
        }
    }

    public function countAnimal()
    {
        echo '<pre>Количество кур: ' . $_SESSION['numChicken'] . '<br>Количество коров: ' . $_SESSION['numCow'] . '</pre>';
    }

    public function pickAnimalProduct()
    {
        $numChickenEgg = 0;
        $numMilkL = 0;
        for ($i = 1; $i <= $_SESSION['numChicken']; $i++) 
        { 
            $numChickenEgg += rand(0, 1);
        }
        for ($i = 1; $i <= $_SESSION['numCow']; $i++) 
        { 
            $numMilkL += rand(8, 12);
        }
        $_SESSION['numChickenEgg'] += $numChickenEgg;
        $_SESSION['numMilkL'] += $numMilkL;
    }

    public function countProduct()
    {
        echo '<pre>Яиц(шт.): ' . $_SESSION['numChickenEgg'] . '<br>Молока(л): ' . $_SESSION['numMilkL'] . '</pre>';
    }
}

class Animal extends Farm
{
    public function addAnimal($chicken = 0, $cow = 0)
    {
        $_SESSION['numChicken'] += $chicken;
        $_SESSION['numCow'] += $cow;
    }
}

$farm = new Animal;
$farm->countAnimal();
$day = 7;
for ($i = 1; $i <= $day; $i++) 
{ 
    $farm->pickAnimalProduct();
}
$farm->countProduct();
$farm->addAnimal(5, 1);
$farm->countAnimal();
for ($i = 1; $i <= $day; $i++) 
{ 
    $farm->pickAnimalProduct();
}
$farm->countProduct();
?>
