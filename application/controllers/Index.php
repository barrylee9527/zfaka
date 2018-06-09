<?php
class IndexController extends PcBasicController {

	public function init(){
        parent::init();
	}

	public function indexAction(){
		if(!$this->login OR empty($this->uinfo)){
			$this->redirect("/product/");
		}else{
			$this->redirect("/member/");
		}
		return FALSE;
	}
	
	public function testAction(){
		$paymethod='zfbf2f';
		$this->m_payment = $this->load('payment');
		$payments = $this->m_payment->getConfig();
			$payconfig = $payments[$paymethod];
			//支付宝公钥，账户中心->密钥管理->开放平台密钥，找到添加了支付功能的应用，根据你的加密类型，查看支付宝公钥
			 $alipayPublicKey=$payconfig['appid'];
			$aliPay = new \Pay\AlipayService($alipayPublicKey);	
			
			$str='{"paymethod":"zfbf2f","gmt_create":"2018-06-09 14:55:38","charset":"UTF-8","seller_email":"18175617527","subject":"\u9080\u8bf7\u7801-\u5168\u7403\u4e3b\u673a\u4ea4\u6d41\u8bba\u575b","sign":"iO6v6H268HWVSMa8z9+\/xGNMJXIUqsBxsoYfz7GL6AGt5k+cr9zxWbFrK+Mvt+4N+BOB154Dd4zCK9+AcNc1rEFpQT6Q96FHM6xxC3KDFq0K4fvBnUmfVpbq5wSCbnpL+Jssd4\/1eUAwJ6voxA4ous\/nTGhk08UV7j0nnBU9mf3gJjcoHrHHpkceK5Qv+APDmt7k2tnmZO+eKnF2F4Yymdz2876Sk3zXLf0n9qwf6mNa5XwR1MCzG6wgm7Aiv3Z7DjokqY8DqQOxYtIEXaFwaOQpYlfKP\/3Z3iCnuMZ3guVPVNuZcS7\/KuoGn+QFaq9J2dDMsJuUKDJHgnzLciS9qQ==","body":"zfbf2f","buyer_id":"2088902956748723","invoice_amount":"2.00","notify_id":"ae1ef7760db261707e66d5cc65f21cblk5","fund_bill_list":"[{\"amount\":\"2.00\",\"fundChannel\":\"ALIPAYACCOUNT\"}]","notify_type":"trade_status_sync","trade_status":"TRADE_SUCCESS","receipt_amount":"2.00","buyer_pay_amount":"2.00","app_id":"2018060660307830","sign_type":"RSA2","seller_id":"2088032348049281","gmt_payment":"2018-06-09 14:55:49","notify_time":"2018-06-09 15:19:28","version":"1.0","out_trade_no":"zlkb2018060914524334805","total_amount":"2.00","trade_no":"2018060921001004720547186083","auth_app_id":"2018060660307830","buyer_logon_id":"137****8591","point_amount":"0.00"}';
			$post = json_decode($str,true);
			$result = $aliPay->rsaCheck($post,$post['sign_type']);
			if($result===true){
				if($post['trade_status'] == "TRADE_SUCCESS"){
					$params = array('paymethod'=>$paymethod,'tradeid'=>$post['trade_no'],'orderid'=>$post['out_trade_no'],'paymoney'=>$post['total_amount']);
					//$this->_doOrder($params);
					//处理你的逻辑，例如获取订单号$_POST['out_trade_no']，订单金额$_POST['total_amount']等
					//程序执行完后必须打印输出“success”（不包含引号）。如果商户反馈给支付宝的字符不是success这7个字符，支付宝服务器会不断重发通知，直到超过24小时22分钟。一般情况下，25小时以内完成8次通知（通知的间隔频率一般是：4m,10m,10m,1h,2h,6h,15h）；
					echo 'success';exit();
				}else{
					echo 'error';exit();
				}
			}else{
				echo 'error';exit();
			}
			exit();		
	}
}