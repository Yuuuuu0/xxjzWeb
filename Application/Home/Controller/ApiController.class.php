<?php
namespace Home\Controller;
use Think\Controller;
class ApiController extends Controller {

    //登录api
    public function login(){
        if(IS_POST){
            $username = I('post.username','','htmlspecialchars');
            $password = I('post.password','','htmlspecialchars');
            $submit   = I('post.submit','','htmlspecialchars');
        }else{
            $username = I('get.username','','htmlspecialchars');
            $password = I('get.password','','htmlspecialchars');
            $submit   = I('get.submit','','htmlspecialchars');
        }

        session(null); //清空session

        if(UserLogin($username,$password)){
            session('submit',$submit);
            ClearAllCache(); //清除缓存
            $arrData = array('uid'=>session('uid'),'uname'=>session('username'));
        }elseif(intval(S('login_times_'.$username)) > C('USER_LOGIN_TIMES')){
            $arrData = array('uid'=>'0','uname'=>'你的账号已被锁定，请联系管理员解锁！');
        }else{
            $arrData = array('uid'=>'0','uname'=>'用户名或密码错误！');
        }
        die(json_encode($arrData));
    }

    //用户信息Api
    public function user() {
        if (IS_POST) {
            $uid = I('post.uid',0,'intval');
            $type = I('post.type','get');
            $data = json_decode(base64_decode(I('post.data',null)),true);
        } else {
            $uid = I('get.uid',0,'intval');
            $type = I('get.type','get');
            $data = json_decode(base64_decode(I('get.data',null)),true);
        }
        $arrData = array();
        if ($uid == session('uid')){
            if ($type == 'get') {
                $arrData['uid'] = $uid;
                $arrData['username'] = session('username');
                $arrData['email'] = GetUserEmail($uid,true);
            } else if ($type == 'updataUsername') {
                $ret = UpdataUserName($uid, $data['username'], $data['email'], $data['password']);
                if ($ret[0]) {
                    $arrData['uid'] = $uid;
                    $arrData['username'] = $ret[1];
                } else {
                    $arrData['uid'] = 0;
                    $arrData['username'] = $ret[1];
                }
            } else if ($type == 'updataPassword') {
                $ret = UpdataPassword($uid, $data['old'], $data['new']);
                if ($ret[0]) {
                    $arrData['uid'] = $uid;
                    $arrData['username'] = $ret[1];
                } else {
                    $arrData['uid'] = 0;
                    $arrData['username'] = $ret[1];
                }
            } else if ($type == 'updataEmail') {
                $arrData['uid'] = 0;
                $arrData['username'] = '邮箱不可修改，请联系管理员！';
            } else{
                $arrData['uid'] = 0;
            }
        } else {
            $arrData['uid'] = 0;
        }

        die(json_encode($arrData));
    }

    //获取统计Api
    public function statistic() {
        if (IS_POST) {
            $type = I('post.type','all');
        } else {
            $type = I('get.type','all');
        }
        $uid = session('uid');
        $arrData = array();
        if ($uid > 0) {
            if ($type == "retime") {
                ClearAllCache(); //清除缓存
            }
            $arrData['uid'] = $uid;
            $arrData['data'] = AccountStatisticProcess($uid);
        } else {
            $arrData['uid'] = 0;
            $arrData['data'] = "用户未登录，请重新登录！";
        }
        die(json_encode($arrData));
    }

    //资金账户API
    public function funds() {
        if (IS_POST) {
            $type = I('post.type','get');
            $data = json_decode(base64_decode(I('post.data',null)),true);
        } else {
            $type = I('get.type','get');
            $data = json_decode(base64_decode(I('get.data',null)),true);
        }

        $arrData = array();
        $uid = session('uid');
        if ($uid > 0) {
            $arrData['uid'] = $uid;
        } else {
            $arrData['uid'] = 0;
            $arrData['data'] = "用户未登录，请重新登录！";
            die(json_encode($arrData));
        }

        if (isset($data['formId'])) {
            D('UserPush')->addPush($data['formId'], 'Weixin', "funds." . $type);
        }

        switch ($type) {
            case 'get':
                $arrData['data'] = GetFundsData($uid);
                break;
            case 'get_id':
                $arrData['data'] = GetFundsIdData($data['fundsid'], $uid);
                break;
            case 'add':
                if (!isset($data['fundsmoney'])) {
                    $data['fundsmoney'] = 0;
                }
                $arrData['data'] = AddNewFunds($data['fundsname'], $data['fundsmoney'], $uid);
                break;
            case 'edit':
                if (isset($data['fundsname'])) {
                    $arrData['data'] = EditFundsName($data['fundsid'], $data['fundsname'], $uid);
                }
                if (isset($data['fundsmoney'])) {
                    $arrData['data'] = EditFundsDefaultMoney($data['fundsid'], $data['fundsmoney'], $uid);
                }
                break;
            case 'del':
                $arrData['data'] = DeleteFunds($data['fundsid_old'], $uid, $data['fundsid_new']);
                break;
            default:
                $arrData['data'] = '非法操作！';
                break;
        }
        die(json_encode($arrData));
    }

    //分类Api
    public function aclass() {
        if (IS_POST) {
            $type = I('post.type','get');
            $data = json_decode(base64_decode(I('post.data',null)),true);
        } else {
            $type = I('get.type','get');
            $data = json_decode(base64_decode(I('get.data',null)),true);
        }

        $arrData = array();
        $uid = session('uid');
        if ($uid > 0) {
            $arrData['uid'] = $uid;
        } else {
            $arrData['uid'] = 0;
            $arrData['data'] = "用户未登录，请重新登录！";
            die(json_encode($arrData));
        }

        if (isset($data['formId'])) {
            D('UserPush')->addPush($data['formId'], 'Weixin', "class." . $type);
        }

        // 是否只获取启用的分类
        $filter_status = isset($data['filter_status']) ? boolval($data['filter_status']) : false;

        switch ($type) {
            case 'get':
                $arrData['data']['in']  = GetClassData($uid, 1, $filter_status);
                $arrData['data']['out'] = GetClassData($uid, 2, $filter_status);
                $arrData['data']['all'] = GetClassData($uid, 0, $filter_status);
                break;
            
            case 'getin':
                $arrData['data'] = GetClassData($uid, 1, $filter_status);
                break;
            
            case 'getout':
                $arrData['data'] = GetClassData($uid, 2, $filter_status);
                break;

            case 'getall':
                $arrData['data'] = GetClassData($uid, 0, $filter_status);
                break;

            case 'getindata':
                $arrData['data'] = GetClassAllData($uid, 1, $filter_status);
                break;
            
            case 'getoutdata':
                $arrData['data'] = GetClassAllData($uid, 2, $filter_status);
                break;

            case 'getalldata':
                $arrData['data'] = GetClassAllData($uid, 0, $filter_status);
                break;

            case 'addall':
                $arrData['data'] = FastAddNewClass($data, $uid);
                break;
            
            case 'add':
                $data['ufid'] = $uid;
                $arrData['data'] = AddNewClass($data);
                break;

            case 'edit':
                $arrData['data'] = editClassName($data['classname'], $data['classid'], $data['classtype'], $uid);
                break;

            case 'change':
                $arrData['data'] = ChangeClassType($data['classid'], $uid);
                break;

            case 'del':
                if (intval(GetClassAccountNum($data['classid'],$uid)) > 0) {
                    $DbData = GetClassIdData($data['classid'],$uid);
                    $arrData['data'] = array(false, '【'.$DbData[1]['classname'].'】分类数据不为空，请先处理记账数据！');
                }else{
                    $arrData['data'] = DelClass($data['classid'],$uid);
                }
                break;

            case 'move':
                $arrData['data'] = MoveClassAccount($data['classid1'], $data['classid2'], $uid);
                break;

            default:
                $arrData['data'] = '非法操作！';
                break;
        }
        die(json_encode($arrData));
    }

    //记账数据Api
    public function account()
    {
        if (IS_POST) {
            $type = I('post.type','get');
            $data = json_decode(base64_decode(I('post.data',null)),true);
        } else {
            $type = I('get.type','get');
            $data = json_decode(base64_decode(I('get.data',null)),true);
        }

        $arrData = array();
        $uid = session('uid');
        if ($uid > 0) {
            $arrData['uid'] = $uid;
        } else {
            $arrData['uid'] = 0;
            $arrData['data'] = "用户未登录，请重新登录！";
            die(json_encode($arrData));
        }
        
        if (isset($data['formId'])) {
            D('UserPush')->addPush($data['formId'], 'Weixin', "account." . $type);
        }

        switch ($type) {
            case 'get':
                $arrData['data'] = GetDateAccountData($uid, $data); // 获取记账数据
                break;

            case 'get_year':
                $arrData['data'] = json_decode(getYearData($data['year'], $uid), true); // 获取年度统计
                break;

            case 'get_all_year':
                $arrData['data'] = json_decode(getAllYearData($uid), true); // 获取历年统计
                break;

            case 'get_id':
                $arrData['data'] = NumTimeToStrTime(GetIdData($data['acid']));
                if ($data['jiid'] != $uid) {
                    $arrData['uid'] = 0;
                    $arrData['data'] = '用户验证未通过，请重新登录！';
                }
                break;
            
            case 'add':
                $data['jiid'] = $uid;
                $ret = AddAccountData($data);
                $arrData['data']['ret'] = $ret[0];
                $arrData['data']['msg'] = $ret[1];
                ClearDataCache(); //清除缓存
                break;

            case 'edit':
                if (CheakIdShell($data['acid'], $uid)) {
                    $ret = UpdataAccountData($data);
                    $arrData['data']['ret'] = $ret[0];
                    $arrData['data']['msg'] = $ret[1];
                    ClearDataCache(); //清除缓存
                } else {
                    $arrData['data']['ret'] = false;
                    $arrData['data']['msg'] = '未通过合法性验证！';
                }
                break;

            case 'del':
                if (CheakIdShell($data['acid'], $uid)) {
                    $ret = DelIdData($uid, $data['acid']);
                    $arrData['data']['ret'] = $ret[0];
                    $arrData['data']['msg'] = $ret[1];
                    ClearDataCache(); //清除缓存
                } else {
                    $arrData['data']['ret'] = false;
                    $arrData['data']['msg'] = '未通过合法性验证！';
                }
                break;

            case 'find':
                if ($data['jiid'] == $uid) {
                    $ret = NumTimeToStrTime(FindAccountData($data, $data['page']));
                    $arrData['data']['ret'] = true;
                    $arrData['data']['msg'] = $ret;
                } else {
                    $arrData['data']['ret'] = false;
                    $arrData['data']['msg'] = '未通过用户验证！';
                }
                break;

            case 'get_image':
                $ret = GetImageData($uid, $data['acid']);
                $arrData['data']['ret'] = $ret[0];
                $arrData['data']['msg'] = $ret[1];
                break;

            case 'set_image':
                $ret = EditImageAcid($uid, $data['id'], $data['acid']);
                $arrData['data']['ret'] = $ret;
                $arrData['data']['msg'] = $ret == false ? '目标账单无法添加图片。' : 'OK';
                break;

            case 'del_image':
                $ret = DelImageData($uid, $data['acid'], $data['id']);
                $arrData['data']['ret'] = $ret[0];
                $arrData['data']['msg'] = $ret[1];
                break;

            default:
                break;
        }

        die(json_encode($arrData));
    }

    //转账数据Api
    public function transfer() {
        if (IS_POST) {
            $type = I('post.type','get');
            $data = json_decode(base64_decode(I('post.data',null)),true);
        } else {
            $type = I('get.type','get');
            $data = json_decode(base64_decode(I('get.data',null)),true);
        }

        $arrData = array();
        $uid = session('uid');
        if ($uid > 0) {
            $arrData['uid'] = $uid;
        } else {
            $arrData['uid'] = 0;
            $arrData['data'] = "用户未登录，请重新登录！";
            die(json_encode($arrData));
        }

        switch ($type) {
            case 'get':
                if (!isset($data['fid'])) {
                    $data['fid'] = 0;
                }
                $arrData['data'] = GetFundsIdTransferData($uid, $data['page'], $data['fid']); // 获取转账数据
                break;
                
            case 'get_id':
                $ret = GetTransferIdData($data['tid'], $uid); // 获取转账ID数据
                $arrData['data']['ret'] = $ret[0];
                $arrData['data']['msg'] = $ret[1];
                break;

            case 'add':
                $data['uid'] = $uid;
                $ret = AddTransferData($data);
                $arrData['data']['ret'] = $ret[0];
                $arrData['data']['msg'] = $ret[1];
                ClearDataCache(); //清除缓存
                break;

            case 'edit':
                $data['uid'] = $uid;
                $ret = EditTransferData($data['tid'], $data);
                $arrData['data']['ret'] = $ret[0];
                $arrData['data']['msg'] = $ret[1];
                ClearDataCache(); //清除缓存
                break;

            case 'del':
                $ret = DelTransferData($data['tid'], $uid);
                $arrData['data']['ret'] = $ret[0];
                $arrData['data']['msg'] = $ret[1];
                ClearDataCache(); //清除缓存
                break;

            case 'find':
                if ($data['jiid'] == $uid) {
                    $ret = FindTransferData($data, $data['page']);
                    $arrData['data']['ret'] = true;
                    $arrData['data']['msg'] = $ret;
                } else {
                    $arrData['data']['ret'] = false;
                    $arrData['data']['msg'] = '未通过用户验证！';
                }
                break;

            default:
                break;
        }
        die(json_encode($arrData));
    }

    //搜索数据Api
    public function find() {
        if (IS_POST) {
            $type = I('post.type','get');
            $data = json_decode(base64_decode(I('post.data',null)),true);
        } else {
            $type = I('get.type','get');
            $data = json_decode(base64_decode(I('get.data',null)),true);
        }

        $arrData = array();
        $uid = session('uid');
        if ($uid > 0) {
            $arrData['uid'] = $uid;
        } else {
            $arrData['uid'] = 0;
            $arrData['data'] = "用户未登录，请重新登录！";
            die(json_encode($arrData));
        }

        switch ($type) {
            case 'account':
                if ($data['jiid'] == $uid) {
                    $ret = NumTimeToStrTime(FindAccountData($data, $data['page']));
                    $arrData['data']['ret'] = true;
                    $arrData['data']['msg'] = $ret;
                } else {
                    $arrData['data']['ret'] = false;
                    $arrData['data']['msg'] = '未通过用户验证！';
                }
                break;

            case 'transfer':
                if ($data['jiid'] == $uid) {
                    $ret = FindTransferData($data, $data['page']);
                    $arrData['data']['ret'] = true;
                    $arrData['data']['msg'] = $ret;
                } else {
                    $arrData['data']['ret'] = false;
                    $arrData['data']['msg'] = '未通过用户验证！';
                }
                break;

            case 'all':
                if ($data['jiid'] == $uid) {
                    $ret = FindTransferAccountData($data, $data['page']);
                    $arrData['data']['ret'] = true;
                    $arrData['data']['msg'] = $ret;
                } else {
                    $arrData['data']['ret'] = false;
                    $arrData['data']['msg'] = '未通过用户验证！';
                }
                break;

            default:
                break;
        }
        die(json_encode($arrData));
    }

    //表格数据api
    public function chart() {
        if (IS_POST) {
            $type = I('post.type','year');
            $date = I('post.date',time(),'intval');
        } else {
            $type = I('get.type','year');
            $date = I('get.date',time(),'intval');
        }
        $uid = session('uid');
        $year = date('Y', $date);
        $month = date('m', $date);
        $day = date('d', $date);
        switch ($type) {
            case 'year':
                die(getYearData($year, $uid));
                break;

            case 'month':
                die(getMonthData($year, $month, $uid));
                break;
            
            default:
                die('非法操作！');
                break;
        }
    }

    //自动复制功能
    public function autocopy(){
        if (IS_POST) {
            $type = I('post.type','get');
            $data = I('post.data','');
            $enable = I('post.enable', false, 'boolean');
            $enablePullDown = I('post.enablePullDown', false, 'boolean');
        } else {
            $type = I('get.type','get');
            $data = I('get.data','');
            $enable = I('get.enable', false, 'boolean');
            $enablePullDown = I('get.enablePullDown', false, 'boolean');
        }
        $uid = session('uid');
        if (($uid == C('ADMIN_UID')) && ($type == 'updata')) {
            $autoCopyArray['strData'] = $data;
            if ($enable) {
                $autoCopyArray['enable'] = $enable;
            }
            if ($enablePullDown) {
                $autoCopyArray['enablePullDown'] = $enablePullDown;
            }
            $autoCopyFile = fopen('./autoCopy.json', 'w') or die("{}");
            $str = json_encode($autoCopyArray);
            fwrite($autoCopyFile, $str);
            fclose($autoCopyFile);
            die($str);
        } else if ($uid > 0) {
            $autoCopyFile = fopen('./autoCopy.json', 'r') or die("{}");
            $str = fread($autoCopyFile, filesize("./autoCopy.json"));
            fclose($autoCopyFile);
            die($str);
        } else {
            die('非法操作autoCopy.');
        }
    }

    //获取版本号
    public function version() {
        die(json_encode(array(
            'title' => C('XXJZ_TITLE'),
            'message' => C('XXJZ_WELCOME'),
            'version' => GetVersion(),
            'wechat' => C('WX_ENABLE'),
            'admin' => array('uid'=>C('ADMIN_UID')),
            'demo' => array(
                'username' => C('APP_DEMO_USERNAME'),
                'password' => C('APP_DEMO_PASSWORD'),
            ),
            'account' => array(
                'PAGE_SIZE' => C('PAGE_SIZE'),
                'MONEY_FORMAT_DECIMALS' => C('MONEY_FORMAT_DECIMALS'),
                'MONEY_FORMAT_POINT' => C('MONEY_FORMAT_POINT'),
                'MONEY_FORMAT_THOUSANDS' => C('MONEY_FORMAT_THOUSANDS'),
                'MAX_MONEY_VALUE' => C('MAX_MONEY_VALUE'),
                'MAX_CLASS_NAME' => C('MAX_CLASS_NAME'),
                'MAX_FUNDS_NAME' => C('MAX_FUNDS_NAME'),
                'MAX_MONEY_VALUE' => C('MAX_MONEY_VALUE'),
                'MAX_MARK_VALUE' => C('MAX_MARK_VALUE'),
                'IMAGE_SIZE' => C('IMAGE_SIZE'),
                'IMAGE_COUNT' => C('IMAGE_COUNT'),
                'IMAGE_CACHE_URL' => C('IMAGE_CACHE_URL'),
            ),
        )));
    }

    public function test(){
        // $uid = session('uid');
        // if (IS_POST) {
        //     $type = I('post.type','get');
        //     $data = I('post.data',null);
        // } else {
        //     $type = I('get.type','get');
        //     $data = I('get.data',null);
        // }
        // dump("版本号：".GetVersion());
        // dump($uid);
        // dump($type);
        // dump($data);
        // dump(base64_decode($data),true);
        // dump(json_decode(base64_decode($data),true));
        // dump(GetClassAllData($uid));
        dump(D('UserPush')->sendWeixinPush(D('UserPush')->getWeixinTemplateId(),3,"month",array("123","500","本月共记录32笔","2019年7月")));
    }
    
}
