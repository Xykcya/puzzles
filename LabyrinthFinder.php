<?php

class LabyrinthFinder
{
    const Y = 0;//для более понятного доступа к массиву y,x координат
    const X = 1;

    private $labyrinth = array();

    private $arrStartPoint;

    private $yMove = [-1, 0, +1, 0];//как изменяется y,x на каждой итерации при поиске доступных клеток
    private $xMove = [0, +1, 0, -1];

    private $queue;//очередь проверяемых клеток


    //принимает двухмерный массив следующего вида:
    //array(
    //    ['.', '.', '.'],
    //    ['.', '.', '.'],
    //    ['#', '.', '#'],
    //    ['.', '.', '.']
    //);
    /**
     * LabyrinthFinder constructor.
     * @param $labyrinth
     */
    public function __construct($labyrinth)
    {

        $this->arrStartPoint = array(0, 0);

        if (sizeof($labyrinth)) {
            $this->labyrinth = $labyrinth;
            $this->arrEndpoint = array(sizeof($labyrinth) - 1, sizeof($labyrinth[sizeof($labyrinth) - 1]) - 1);
        } else {
            throw  new Exception('wrong labyrinth');
        }
    }

    /**
     * разрешено ли писать в ячейку( не является ли стеной, уже заполненной или за пределами массива )
     * @param array $arrCell
     * @return bool
     */
    public function isAllowed(array $arrCell)
    {
        $result = false;

        if (isset($this->labyrinth[$arrCell[self::Y]] [$arrCell[self::X]])) {
            $cellVal = $this->labyrinth[$arrCell[self::Y]] [$arrCell[self::X]];
            if ('.' === $cellVal) {
                $result = true;
            }
        }

        return $result;
    }

    /**
     * возвращает минимальное количество шагов, необходимое для перемещение из arrStartPoint в arrEndpoint
     * @return int
     */
    public function getNumberOfSteps()
    {
        $steps = 0;

        $this->labyrinth[$this->arrStartPoint[self::Y]] [$this->arrStartPoint[self::X]] = 0;//значение стартовой клетки
        $this->queue[] = $this->arrStartPoint;//добавим в очередь

        do {
            $currentCell = $this->queue[0];
            $currentCellVal = $this->labyrinth[$currentCell[self::Y]][$currentCell[self::X]];

            for ($i = 0; $i < 4; $i++) {//смотрим в соседние клеки по часовой
                $targetCell = null;
                $targetCell = array($currentCell[self::Y] + $this->yMove[$i], $currentCell[self::X] + $this->xMove[$i]);//координаты проверяемой клетки

                if ($this->isAllowed($targetCell)) {//клетка свободна и не была ещё посещена
                    $this->labyrinth[$targetCell[self::Y]] [$targetCell[self::X]] = $currentCellVal + 1;//пишем +1 к стоимости пути
                    $this->queue[] = $targetCell;//добавляем в очередь
                }
            }

            array_shift($this->queue);//FIFO
        } while (sizeof($this->queue));

        $steps = $this->labyrinth[$this->arrEndpoint[self::Y]] [$this->arrEndpoint[self::X]];//берём значение финальной клетки
//        $this->dumpLabyrinth();
        return $steps;

    }


    private function dumpLabyrinth()
    {
        foreach ($this->labyrinth AS $i => $row) {
            foreach ($row AS $j => $cell) {
                echo '|' . $cell . '|';
            }
            echo "\r\n";
        }
        echo "\r\n";
    }


}