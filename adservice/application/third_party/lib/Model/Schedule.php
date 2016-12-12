<?php
class Model_Schedule extends Model_Handler
{

    const TYPE_ORDER = 1;
    public static $type = array(
        self::TYPE_ORDER => '订单'
    );

    public function __construct()
    {
        parent::__construct('schedule_dates', 'ad_service');
    }

    public static function get($ids, $useCache = TRUE)
    {
        return self::_get(__CLASS__, $ids, $useCache);
    }

    public function getMap($option)
    {

        if(!$option) return array();

        $attr = array('select' => 'date, fee', 'order_by' => 'date ASC');

        $map = array();
        $rows = $this->select($option, $attr);
        foreach($rows as $row) {
            $map[$row['date']] = $row;
        }

        return $map;
    }

    public function renderScheduleHtml($dates){
        $html = '';
        $tp1 = <<<HTML
<div class="scheduleContent">
    <p>{{key}}</p>
    <div style="width:100%;">
        <table>
            <tbody>
                {{content}}
            </tbody>
        </table>
    </div>
</div>
HTML;

        foreach($dates as $key => $date){
            $_tpl = $tp1;
            $length = count($date);
            $content = '<tr>';
            for($i = 0; $i < $length; $i++){
                $content .= '<td>' . $date[$i]['day'] . '</td>';
            }
            $content .= '</tr><tr>';
            for($i = 0; $i < $length; $i++){
                $content .= '<td>' . $date[$i]['week'] . '</td>';
            }
            $content .= '</tr><tr>';
            for($i = 0; $i < $length; $i++){
                $content .= '<td><textarea name="' . $date[$i]['date'] . '">' . $date[$i]['fee'] . '</textarea></td>';
            }
            $content .= '</tr>';
            $_tpl = preg_replace('/{{key}}/', $key, $_tpl);
            $_tpl = preg_replace('/{{content}}/', $content, $_tpl);
            $html .= $_tpl;
        }
        return $html;
    }

    public function getScheduleHtml($params){

        if(strlen($params['start']) > 10){
            $params['start'] = date('Y-m-d', $params['start']/1000);
        }
        if(strlen($params['end']) > 10){
            $params['end'] = date('Y-m-d', $params['end']/1000);
        }

        $dates = self::getDates(NULL, $params['start'], $params['end'], $params['fee'], $params['cpm']);
        return self::renderScheduleHtml($dates);
    }

    public static function stampToDate(&$date){
        if(strlen($date) > 10){
            $date = date('Y-m-d', $date/1000);
        }
    }

    public function getDates($dates = NULL, $start = NULL, $end = NULL, $fee = NULL, $cpm = NULL){

        $realStart = NULL;
        $realEnd = NULL;
        if($dates){
            $start = array_shift($dates);
            $end = array_pop($dates);
            if($start && !$end){
                $realStart = $realEnd = $start['date'];
                $start = date('Y-m-01', strtotime($realStart));
                $end = date('Y-m-d', strtotime('-1 day',strtotime('+1 month', strtotime(date('Y-m-01', strtotime($realStart))))));
            }else{
                $realStart = $start['date'];
                $realEnd = $end['date'];
                $start = date('Y-m-01', strtotime($realStart));
                $end = date('Y-m-d', strtotime('-1 day',strtotime('+1 month', strtotime(date('Y-m-01', strtotime($realEnd))))));
            }
        }else{
            if(!$start || !$end){
                $realStart = date('Y-m-d');
                $realEnd = date('Y-m-d', strtotime('+6 day'));
                $start = date('Y-m-01', strtotime($realStart));
                $end = date('Y-m-d', strtotime('-1 day',strtotime('+1 month', strtotime(date('Y-m-01', strtotime($realEnd))))));
            }else{
                $realStart = $start;
                $realEnd = $end;
                $start = date('Y-m-01', strtotime($realStart));
                $end = date('Y-m-d', strtotime('-1 day',strtotime('+1 month', strtotime(date('Y-m-01', strtotime($realEnd))))));
            }

            $dayNum = date_diff(date_create($realStart), date_create($realEnd))->format('%a') + 1;

            if($fee && $cpm && $fee > 0 && $cpm > 0) {
                //获取配送比例
                $disRatio = self::getDisRatio($fee);
                $disRatio = $disRatio ? $disRatio : 1;
                $schedule = sprintf('%0.2f', $fee / $cpm / $dayNum * $disRatio);
            }else{
                $schedule = '';
            }
        }

        $map = array();
        $_year = date('Y', strtotime($start));
        $weeks = array("日", "一", "二", "三", "四", "五", "六");

        for(; $start <= $end; $start = date('Y-m-d', strtotime('+1 day', strtotime($start)))){

            $dayAry = explode('-', $start);
            $year = $dayAry[0];
            $month = intval($dayAry[1]);
            $key = $year . '-'. $month;
            $day = intval($dayAry[2]);
            $week = $weeks[date('w', strtotime($start))];

            if(!isset($map[$key])){
                $map[$key] = array();
            }

            $fee = '';
            if($dates){
                $fee = d(@$dates[$start]['fee'], '');
            }else{
                if($start >= $realStart && $start <= $realEnd){
                    $fee = $schedule;
                }
            }

            $month  .= '月';
            if($year != $_year){
                $_year = $year;
                $month .= "( $year 年)";
            }

            $map[$key][] = array(
                'month' => $month,
                'date' => $start,
                'day' => $day,
                'week' => $week,
                'fee' => $fee
            );
        }
        return $map;
    }

    public function getDisRatio($fee){
        $user = Model_User::get(Session::getInstance()->getUserID());
        $where = array(
            'status' => Model_User::STATUS_ACTIVE,
            'fee_limit_lower' => array(
                '<=' => $fee
            ),
            'fee_limit_upper' => array(
                '>=' => $fee
            ),
            'position_id' => $user['position']
        );
        $attr = array(
            'select' => 'dis_ratio'
        );
        $ratio = F::$f->Model_Discount->selectOne($where, $attr);
        if($ratio){
            return $ratio['dis_ratio'];
        }
    }

    public function _getDates($dates = NULL){
        if($dates){
            $start = array_shift($dates);
            $end = array_pop($dates);
            if($start && !$end){
                $_start = $start;
                $start = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-01', strtotime($start['date'])))));
                $end = date('Y-m-d', strtotime('-1 day',strtotime('+2 month', strtotime(date('Y-m-01',strtotime($_start['date']))))));
            }else{

                $start = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-01', strtotime($start['date'])))));
                $end = date('Y-m-d', strtotime('-1 day',strtotime('+2 month', strtotime(date('Y-m-01', strtotime($end['date']))))));
            }
        }else{
            //如果没有时间，默认获取最近的三个月
            $start = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-01'))));
            $end = date('Y-m-d', strtotime('-1 day',strtotime('+2 month', strtotime(date('Y-m-01')))));
        }

        $map = array();
        $_year = date('Y', strtotime($start));
        $weeks = array("日", "一", "二", "三", "四", "五", "六");
        $index = 0;

        for(; $start <= $end; $start = date('Y-m-d', strtotime('+1 day', strtotime($start)))){

            $dayAry = explode('-', $start);
            $year = $dayAry[0];
            $month = intval($dayAry[1]);
            $key = $year . '_'. $month;
            $day = intval($dayAry[2]);
            $week = $weeks[date('w', strtotime($start))];

            if(!isset($map[$key])){
                $map[$key] = array();
            }

            $fee = NULL;
            if($dates){
                $fee = d(@$dates[$start]['fee'], NULL);
            }

            $month  .= '月';
            if($year != $_year){
                $_year = $year;
                $month .= "( $year 年)";
            }

            $map[$key][] = array(
                'month' => $month,
                'date' => $start,
                'day' => $day,
                'week' => $week,
                'fee' => $fee
            );
        }
        return $map;
    }

}