<?php
class Model_Position extends Model_Handler
{
    CONST GRADE_TRIPLE_A = 1;
    CONST GRADE_DOUBLE_A = 2;
    CONST GRADE_A = 3;
    CONST GRADE_DOUBLE_B = 4;
    CONST GRADE_B = 5;

    public static $grades = array(
        self::GRADE_TRIPLE_A => 'AAA级',
        self::GRADE_DOUBLE_A => 'AA级',
        self::GRADE_A => 'A级',
        self::GRADE_DOUBLE_B => 'B-1级',
        self::GRADE_B => 'B-2级'
    );

    public function __construct()
    {
        parent::__construct('position', 'ad_service');
    }

    public static function get($ids, $useCache = TRUE)
    {
        return self::_get(__CLASS__, $ids, $useCache);
    }

    public function getMap($option = array(), $isSelect = FALSE)
    {
        if(empty($option)){
            $option = array(
                'status' => Model_User::STATUS_ACTIVE,
            );

        }else{
            $option = array_merge(array('status' => Model_User::STATUS_ACTIVE), $option);
        }

        $attr = array('select' => 'id, name');

        $positionMap = array();
        if($isSelect) {
            $positionMap = array('0' => '请选择');
        }

        $list = $this->select($option, $attr);
        foreach($list as $position) {
            $positionMap[$position['id']] = $position['name'];
        }
        return $positionMap;
    }

    public static function getAssistantId(){
        return 8;
    }

    public static function getSalesId(){
        return array(2, 3, 4, 5, 6, 7);
    }

}