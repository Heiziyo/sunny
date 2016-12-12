<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2016/10/20
 * Time: 10:02
 */
class Menu
{

    //投放优先级
    public function getCampaignPriority()
    {
        die($this->delWithMenu(Model_Campaign::$campaign_priority));
    }

    //播放规则
    public function getcreativeShowRule()
    {
        die($this->delWithMenu(Model_Campaign::$show_rule));
    }

    //投放方式
    public function getcampaignDisplayWay()
    {
        die($this->delWithMenu(Model_Campaign::$display_way));
    }

    //设备品质
    public function getQualityTarget()
    {
        die($this->delWithMenu(Model_Campaign::$quality_targeting));
    }

    //投放区域
    public function getCountryTarget()
    {
        die($this->delWithMenu(Model_Campaign::$target_area));
    }

    //投放时间段
    public function getTimeTarget()
    {
        die($this->delWithMenu(Model_Campaign::$time_target));
    }


    //格式化枚举类
    private function delWithMenu($array=[])
    {
        $meun = [];
        if(!empty($array))
        {
            foreach ($array as $key=>$val)
            {
                $menu[] = ['name'=>$val,'value'=>$key];
            }
            return json_encode(['success'=>true,'data'=>$menu]);
        }
        return null;
    }



    //省份列表
    public function getProvince()
    {
        $where = [
            'location1'=>Model_Location::china
        ];
        $attr = [
            'group_by'=>'location2',
            'select'=>'location2 value,cn name'
        ];
        $china = Model_Location::china;
        $data = F::$f->Model_Location->select($where,$attr);
        die(json_encode(['success'=>true,'data'=>$data]));
    }


    //省份下的城市列表
    public function getCity()
    {
        $where = [];
        $location2 = getRequestParam('cn');
        if(empty($location2)) die(json_encode(['success'=>false,'data'=>null]));
         $where['cn'] = [
                $location2,
                Db_sql::LOGIC=>'OR'
         ];
        $where['location1']=Model_Location::china;
        $attr = [
            'select'=>'location3  value,CONCAT(cn,"—",cn_city)  name',
            'group_by'=>'ID',
        ];
        $data = F::$f->Model_Location->select($where,$attr);
        die(json_encode(['success'=>true,'data'=>$data]));
    }

    //媒体列表
    public function getMedia()
    {
        $where = [];
        $where['inv_status'] = Model_Media::STATUS_PASS;
        $attr = [
            'select' => 'inv_id value,inv_name name'
        ];

        $data = F::$f->Model_Media->select($where,$attr);
        die(json_encode(['success'=>true,'data'=>$data]));
    }

    //广告位列表
    public function getZones()
    {
        $media_id = getRequestParam('media_id');
        if(empty($media_id)) die(json_encode(['success'=>false,'data'=>null]));
        $where['publication_id'] = [
            $media_id,
            Db_sql::LOGIC=>'OR'
        ];
        $attr = [
            'select' => 'zone_name name,CONCAT(entry_id,"—",zone_name)  value'
        ];

        $data = F::$f->Model_Zones->select($where,$attr);
        die(json_encode(['success'=>true,'data'=>$data]));
    }


    //设备列表
    public function getDevices()
    {
        $attr = [
            'select' => 'device_id value,device_name name'
        ];
        $data = F::$f->Model_Device->select('',$attr);
        die(json_encode(['success'=>true,'data'=>$data]));
    }
}