<?php
namespace App\HttpController\Api;

use EasySwoole\Http\AbstractInterface\Controller;

class Index extends Controller{

//    protected $host = '184.173.170.252';
    protected $host = '127.0.0.1';

    protected function getData(){
        $request = $this->request();
        $data = $request->getRequestParam();
        $qid = isset($data['qid'])?$data['qid']:"";//$this->request->get('qid');
        $website = isset($data['website'])?$data['website']:"";//$this->request->get('website');
        $lang = isset($data['lang'])?$data['lang']:"";//$this->request->get('lang');
        $type = isset($data['type'])?$data['type']:"";// $this->request->get('type');
        $position = isset($data['position'])?$data['position']:"";
        $sharemode = isset($data['sharemode'])?$data['sharemode']:"";
        $testmode = isset($data['testmode'])?$data['testmode']:"";
        $domain = isset($data['domain'])?$data['domain']:'';
        $device = isset($data['device'])?$data['device']:'';
        if (empty($qid) or !is_numeric($qid)){
            return 'Parameter error';
        }

        if ($this->isAjax() != true){
            return 'Parameter error';
        }

        if (empty($website) or !in_array($website,['testname','quiztest','jogarquiz','gamev6','thehotgames','buzzfun','v6hot','gamez6','buzzsight','postfunny','diggfun','wowtime'])){
            return 'Parameter error';
        }
        if ($website == 'v6hot'){
            $data = [
                'qid' => $qid,
                'website' => $website
            ];
            return $data;
        }
        if (empty($lang)){
            return 'Parameter error';
        }
        if($website == 'gamev6' || $website == 'thehotgames' || $website == 'gamez6'){
            if (!empty($type) && is_numeric($type)){
                if(!in_array($type,[10,20,30])){
                    return 'Parameter error';
                }
            }else if (($website == 'thehotgames' || $website == 'gamev6')){
                if(empty($type)){
                    $type = 'mobile';
                }
            }else if (empty($type)){
                return 'Parameter error';
            }

            $data = [
                'qid' => $qid,
                'website' => $website,
                'lang' => $lang,
                'type' => $type
            ];
        }elseif (in_array($website,['buzzfun','diggfun','postfunny','buzzsight','wowtime'])){
            if ($type == '' || !in_array($type,[10,20,30,40,50,60,80,90])) {
                return 'Parameter error';
            }
            if(empty($device)){
                $device = 'mobile';
            }
            $data = [
                'qid' => $qid,
                'website' => $website,
                'lang' => $lang,
                'type' => $type,
                'domain' => $domain,
                'device' => $device

            ];
            if (!empty($position)){
                if (!in_array($position,[10,20,30,40,50,60,80,110,120])){
                    return 'Parameter error';
                }
                $data['position'] = $position;
            }
            if (!empty($sharemode)){
                if (!in_array($sharemode,['facebook','whatsapp','twitter','messenger','link'])){
                    return 'Parameter error';
                }
                $data['sharemode'] = $sharemode;
            }
            if (!empty($testmode)){
                if (!in_array($testmode,['facebook','google','name','playbuzz','select','start','photo','submit'])){
                    return 'Parameter error';
                }
                $data['testmode'] = $testmode;
            }
        }else{
            if (!empty($position)){
                if (!in_array($position,[10,30,40,60,70,90,91,92,93,100,110,120])){
                    return 'Parameter error';
                }
                $data['position'] = $position;
            }
            $data = [
                'qid' => $qid,
                'website' => $website,
                'lang' => $lang,
                'domain'=>$domain,
                'position'=>$position
            ];
        }
        return $data;
    }
    
    function index()
    {
        $data = $this->getData();
        var_dump($data);
        $this->writeJson(200, [], 'success');
    }

    protected function isAjax(){
        $request = $this->request();
        $data = $request->getHeaders();
        if (isset($data['origin'])){
            return true;
        }else{
            return false;
        }
//        $this->writeJson(200,$data,'success');
//        return $result;
    }

    /**
     * 设置题目展示量
     *  @throws \think\Exception
     */
    function setShowAmount(){

        $data = $this->getData();
        if (!is_array($data)){
            $this->writeJson(500, [], 'Parameter error');
        }else{
            $redis = new \Redis();
            $redis->connect($this->host, 6379);
            $hashKey = 'show_amount';
            if (isset($data['position'])){
                if ($data['position'] == 10){
                    $hashKey = 'i_show_amount';  //首页展示量 index
                }elseif ($data['position'] == 20){
                    $hashKey = 'mi_show_amount';  //版块首页展示量  block_index
                }elseif ($data['position'] == 30){
                    $hashKey = 'dr_show_amount';  //详情页展示量
                }elseif ($data['position'] == 40){
                    $hashKey = 'rr_show_amount';   //结果页展示量
                }elseif ($data['position'] == 50){
                    $hashKey = 'h_show_amount';   //圣诞专题展示量
                }elseif ($data['position'] == 60){
                    $hashKey = 'c_show_amount';    //分类展示量
                }elseif ($data['position'] == 70){
                    $hashKey = 'n_show_amount';    //分页展示量
                }elseif ($data['position'] == 80){
                    $hashKey = 'r_show_amount';    //旁行榜
                }elseif($data['position'] == 90){
                    $hashKey = 'most_show_amount';
                }elseif($data['position'] == 91){
                    $hashKey = 'most_loved_show_amount';
                }elseif($data['position'] == 92){
                    $hashKey = 'most_testd_show_amount';
                }elseif($data['position'] == 93){
                    $hashKey = 'most_shated_show_amount';
                }elseif($data['position'] == 100){
                    $hashKey = 'fresh_show_amount';
                }elseif($data['position'] == 110){
                    $hashKey = 'writer_show_amount';
                }elseif ($data['position'] == 120){
                    $hashKey = 'tag_show_amount';
                }
                unset($data['position']);
            }
            $str = implode('_',$data);
            $fieldName = 'statistics|' .$str;
            $redis->hIncrBy($fieldName,$hashKey,1);
            $this->writeJson(200, [], 'success');
        }
    }

    function setClickAmount(){
        $data = $this->getData();
        if (!is_array($data)){
            $this->writeJson(500, [], 'Parameter error');
        }else{
            $redis = new \Redis();
            $redis->connect($this->host, 6379);
            $hashKey = 'click_amount';
            if (isset($data['position'])){
                if ($data['position'] == 10){
                    $hashKey = 'i_click_amount';
                }elseif ($data['position'] == 20){
                    $hashKey = 'mi_click_amount';
                }elseif ($data['position'] == 30){
                    $hashKey = 'dr_click_amount';
                }elseif ($data['position'] == 40){
                    $hashKey = 'rr_click_amount';
                }elseif ($data['position'] == 50){ //圣诞专题展示量
                    $hashKey = 'h_click_amount';
                }elseif ($data['position'] == 60){
                    $hashKey = 'c_click_amount';    //分类展示量
                }elseif ($data['position'] == 70){
                    $hashKey = 'n_click_amount';    //分页展示量
                }elseif ($data['position'] == 80){
                    $hashKey = 'r_click_amount';
                }elseif($data['position'] == 90){
                    $hashKey = 'most_click_amount';
                }elseif($data['position'] == 91){
                    $hashKey = 'most_loved_click_amount';
                }elseif($data['position'] == 92){
                    $hashKey = 'most_testd_click_amount';
                }elseif($data['position'] == 93){
                    $hashKey = 'most_shated_click_amount';
                }elseif($data['position'] == 100){
                    $hashKey = 'fresh_click_amount';
                }elseif($data['position'] == 110){
                    $hashKey = 'writer_click_amount';
                }elseif ($data['position'] == 120){
                    $hashKey = 'tag_click_amount';
                }

                unset($data['position']);
            }
            $str = implode('_',$data);
            $fieldName = 'statistics|' .$str;
            $redis->hIncrBy($fieldName,$hashKey,1);
            $this->writeJson(200, [], 'success');
        }
    }

    /**
     * 题目详情展示量
     * @throws \think\Exception
     */
    public function setDetailsShowAmount()
    {
        $data = $this->getData();
        if (isset($data['position'])){
            unset($data['position']);
        }
        $this->setRedisData($data,'details_show_amount');
    }

    /**
     * 题目测试点击量
     * @throws \think\Exception
     */
    function setTestClickAmount(){
        $data = $this->getData();
        if (!is_array($data)){
            $this->writeJson(500, [], 'Parameter error');
        }else{
            $redis = new \Redis();
            $redis->connect($this->host, 6379);
            $hashKey = "test_click_amount";
            if (isset($data['testmode'])){
                if (in_array($data['testmode'],['facebook','google','name','playbuzz','select','start','photo','submit'])){
                    $hashKey = "test_click_". $data['testmode'] ."_amount";
                }else{
                    $this->writeJson(500, [], 'Parameter error');die();
                }
                unset($data['testmode']);
            }
            if (isset($data['position'])){
                unset($data['position']);
            }
            $str = implode('_',$data);
            $fieldName = 'statistics|' .$str;
            $redis->hIncrBy($fieldName,$hashKey,1);
            $this->writeJson(200, [], 'success');
        }
    }

    /**
     * 题目真实测试量
     * @throws \think\Exception
     */
    public function setTestAmount(){
        $data = $this->getData();
        $this->setRedisData($data,'test_amount');
    }


    /**
     * 渠道进入测试量
     * @return \think\response\JsonsetGameIndexShowAmount
     */
    public function setChannelTestAmount(){
        $data = $this->getData();
        $this->setRedisData($data,'channel_test_amount');
    }

    /**
     * 题目结果展示量
     * @throws \think\Exception
     */
    public function setTestResultShowAmount()
    {
        $data = $this->getData();
        if (!is_array($data)) {
            $this->writeJson(500, [], 'Parameter error');
        }else{
            if (isset($data['position'])) {
                unset($data['position']);
            }
            $this->setRedisData($data,'test_result_show_amount');
        }
    }

    /**
     * 题目分享量
     * @throws \think\Exception
     */
    public function setShareAmount(){
        $data = $this->getData();
        if (!is_array($data)){
            $this->writeJson(500, [], 'Parameter error');
        }else{
            $hashKey = 'share_amount';
            if (isset($data['sharemode'])){
                if ($data['sharemode'] == 'facebook'){
                    $hashKey = 'share_facebook_amount';
                }elseif ($data['sharemode'] == 'whatsapp'){
                    $hashKey = 'share_whatsapp_amount';
                }elseif ($data['sharemode'] == 'twitter'){
                    $hashKey = 'share_twitter_amount';
                }elseif ($data['sharemode'] == 'messenger'){
                    $hashKey = 'share_messenger_amount';
                }elseif ($data['sharemode'] == 'link'){
                    $hashKey = 'share_link_amount';
                }else{
                    $this->writeJson(500, [], 'Parameter error');die();
                }
                unset($data['sharemode']);
            }
            $this->setRedisData($data,$hashKey);
        }
    }

    /**
     * 推荐详情展示量
     * @return \think\response\Json
     */
    public function setRecommendDetailsShowAmout(){
        $data = $this->getData();
        if (!is_array($data)){
            $this->writeJson(500, [], 'Parameter error');
        }else{
            $this->setRedisData($data,'recommend_details_show_amout');
        }
    }

    /**
     * 推荐详情展示量
     * @return \think\response\Json
     */
    public function setRecommendDetailsTestClickAmout(){
        $data = $this->getData();
        if (!is_array($data)){
            $this->writeJson(500, [], 'Parameter error');
        }else{
            $this->setRedisData($data,'recommend_details_test_click_amout');
        }
    }

    public function setRecommendResultShowAmount(){
        $data = $this->getData();
        if (!is_array($data)){
            $this->writeJson(500, [], 'Parameter error');
        }else{
            $this->setRedisData($data,'recommend_result_show_amount');
        }
    }

    public function setRecommendResultTestClickAmount(){
        $data = $this->getData();
        if (!is_array($data)){
            $this->writeJson(500, [], 'Parameter error');
        }else{
            $this->setRedisData($data,'recommend_result_test_click_amount');
        }
    }

    public function setGameIndexShowAmount(){
        $data = $this->getData();
        if (!is_array($data)){
            $this->writeJson(500, [], 'Parameter error');
        }else{
            $this->setRedisData($data,'game_index_show_amount');
        }
    }

    public function setGameDetailsShowAmount(){
        $data = $this->getData();
        if (!is_array($data)){
            $this->writeJson(500, [], 'Parameter error');
        }else{
            $this->setRedisData($data,'game_details_show_amount');
        }
    }

    public function setGamePlayShowAmount(){
        $data = $this->getData();
        if (!is_array($data)){
            $this->writeJson(500, [], 'Parameter error');
        }else{
            $this->setRedisData($data,'game_play_show_amount');
        }
    }

    public function setGameIndexClickAmount(){
        $data = $this->getData();
        if (!is_array($data)){
            $this->writeJson(500, [], 'Parameter error');
        }else{
            $this->setRedisData($data,'game_index_click_amount');
        }
    }

    public function setGameDetailsClickAmount(){
        $data = $this->getData();
        if (!is_array($data)){
            $this->writeJson(500, [], 'Parameter error');
        }else{
            $this->setRedisData($data,'game_details_click_amount');
        }
    }

    public function setGamePlayClickAmount(){
        $data = $this->getData();
        if (!is_array($data)){
            $this->writeJson(500, [], 'Parameter error');
        }else{
            $this->setRedisData($data,'game_play_click_amount');
        }
    }

    public function setGamePlayAmount(){
        $data = $this->getData();
        if (!is_array($data)){
            $this->writeJson(500, [], 'Parameter error');
        }else{
            $this->setRedisData($data,'game_play_amount');
        }
    }

    public function setGamePlay2Amount(){
        $data = $this->getData();
        return $this->setRedisData($data,'game_play2_amount');
    }

    public function setGameNewShowAmount(){
        $data = $this->getData();
        return $this->setRedisData($data,'game_new_show_amount');
    }

    public function setGameNewClickAmount(){
        $data = $this->getData();
        return $this->setRedisData($data,'game_new_click_amount');
    }

    public function setGameNewPlayAmount(){
        $data = $this->getData();
        return $this->setRedisData($data,'game_new_play_amount');
    }

    public function setGameHotShowAmount(){
        $data = $this->getData();
        return $this->setRedisData($data,'game_hot_show_amount');
    }

    public function setGameHotClickAmount(){
        $data = $this->getData();
        return $this->setRedisData($data,'game_hot_click_amount');
    }

    public function setGameHotPlayAmount(){
        $data = $this->getData();
        return $this->setRedisData($data,'game_hot_play_amount');
    }

    public function setGameSearchShowAmount(){
        $data = $this->getData();
        return $this->setRedisData($data,'game_search_show_amount');
    }

    public function setGameSearchClickAmount(){
        $data = $this->getData();
        return $this->setRedisData($data,'game_search_click_amount');
    }

    public function setGameSearchPlayAmount(){
        $data = $this->getData();
        return $this->setRedisData($data,'game_search_play_amount');
    }

    public function setGameCategoryShowAmount(){
        $data = $this->getData();
        return $this->setRedisData($data,'game_category_show_amount');
    }

    public function setGameCategoryClickAmount(){
        $data = $this->getData();
        return $this->setRedisData($data,'game_category_click_amount');
    }

    public function setGameCategoryPlayAmount(){
        $data = $this->getData();
        return $this->setRedisData($data,'game_category_play_amount');
    }

    public function setDownImgAmount(){
        $data = $this->getData();
        return $this->setRedisData($data,'down_img_amount');
    }

    private function setRedisData($data,$field){
        if (!is_array($data)){
            $this->writeJson(500, [], 'Parameter error');
        }else{
            $str = implode('_',$data);
            $fieldName = 'statistics|' .$str;
            $redis = new \Redis();
            $redis->connect($this->host, 6379);
            $redis->hIncrBy($fieldName,$field,1);
            $this->writeJson(200, [], 'success');
        }
    }
}