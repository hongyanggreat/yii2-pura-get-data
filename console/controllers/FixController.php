<?php
/**
 * Created by Navatech.
 * @project btcvip-com
 * @author  Phuong
 * @email   notteen[at]gmail.com
 * @date    6/6/2018
 * @time    5:11 PM
 */

namespace console\controllers;

use frontend\models\GetHelp;
use frontend\models\ProvideHelp;
use frontend\models\TransferHelp;
use frontend\models\Users;
use navatech\simplehtmldom\SimpleHTMLDom;
use yii\console\Controller;

class FixController extends Controller {

	public function actionIndex() {
		$customers   = [];
		$loginUrl    = 'http://pura.global/admin/index.php?route=common/login';
		$loginFields = array(
			'username' => 'admin',
			'password' => 'admin123456',
		);
		$loginCurl   = self::getUrl($loginUrl, 'post', $loginFields);
		$loginDom    = SimpleHTMLDom::str_get_html($loginCurl);
		$aToken      = $loginDom->find('a', 0);
		preg_match("/token=(.*)/", $aToken->href, $output_array);
		if (isset($output_array[1])) {
			$token = $output_array[1];
			$j     = 1;
			for ($i = 1; $i < 100; $i ++) {
				$customerUrl  = 'http://pura.global/admin/index.php?route=pd/customer&page=' . $i . '&token=' . $token;
				$customerCurl = self::getUrl($customerUrl, 'get');
				$customerDom  = SimpleHTMLDom::str_get_html($customerCurl);
				if ($customerDom !== null) {
					$customerDiv = $customerDom->find('#homesss', 0);
					$customerTrs = $customerDiv->find('tr');
					foreach ($customerTrs as $customerTr) {
						if ($customerTr->find('td', 1) != null) {
							$customers[] = [
								'id'              => $j,
								'username'        => $customerTr->find('td', 1)->innertext(),
								'phone'           => $customerTr->find('td', 2)->innertext(),
								'upline'          => $customerTr->find('td', 3)->innertext(),
								'registration_at' => $customerTr->find('td', 4)->innertext(),
							];
							$j ++;
						}
					}
				}
			}
		}
		echo '<pre>';
		print_r($customers);
		die;
	}

	public static function getUrl($url, $method = '', $vars = '') {
		$ch = curl_init();
		if ($method == 'post') {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
		}
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_COOKIEJAR, \Yii::getAlias('@runtime/cookies.txt'));
		curl_setopt($ch, CURLOPT_COOKIEFILE, \Yii::getAlias('@runtime/cookies.txt'));
		$buffer = curl_exec($ch);
		curl_close($ch);
		return $buffer;
	}

	public function actionPd() {
		$pds         = [];
		$loginUrl    = 'http://pura.global/admin/index.php?route=common/login';
		$loginFields = array(
			'username' => 'admin',
			'password' => 'admin123456',
		);
		$loginCurl   = self::getUrl($loginUrl, 'post', $loginFields);
		$loginDom    = SimpleHTMLDom::str_get_html($loginCurl);
		$aToken      = $loginDom->find('a', 0);
		preg_match("/token=(.*)/", $aToken->href, $output_array);
		if (isset($output_array[1])) {
			$token = $output_array[1];
			$j     = 1;
			for ($i = 1; $i <= 60; $i ++) {
				$pdUrl  = 'http://pura.global/admin/index.php?route=pd/gh&page=' . $i . '&token=' . $token;
				$pdCurl = self::getUrl($pdUrl, 'get');
				$pdDom  = SimpleHTMLDom::str_get_html($pdCurl);
				if ($pdDom !== null) {
					$pdDiv = $pdDom->find('#home', 0);
					$pdTrs = $pdDiv->find('tr');
					foreach ($pdTrs as $pdTr) {
						if ($pdTr->find('td', 1) != null) {
							$pdTd   = $pdTr->find('td', 6);
							$button = $pdTd->find('button', 0);
							$pds[]  = [
								'id'              => $j,
								'username'        => $pdTr->find('td', 1)->innertext(),
								'phone'           => $pdTr->find('td', 2)->innertext(),
								'upline'          => $pdTr->find('td', 3)->innertext(),
								'registration_at' => $pdTr->find('td', 4)->innertext(),
							];
							$j ++;
							foreach ($pds as $pd) {
								$provideHelp               = new ProvideHelp();
								$user                      = new Users();
								$provideHelp->user_id      = $user->getUserIdByUserName($pdTr->find('td', 1)->innertext());
								$provideHelp->status       = trim($pd['status'] == 'Watiing') ? 1 : 0;
								$provideHelp->count        = 1;
								$provideHelp->count_origin = 1;
								$provideHelp->is_completed = 1;
								$provideHelp->save();
							}
						}
					}
				}
			}
			echo '<pre>';
			print_r($pds);
		}
	}

	public function actionGd() {
		$gds         = [];
		$loginUrl    = 'http://pura.global/admin/index.php?route=common/login';
		$loginFields = array(
			'username' => 'admin',
			'password' => 'admin123456',
		);
		$loginCurl   = self::getUrl($loginUrl, 'post', $loginFields);
		$loginDom    = SimpleHTMLDom::str_get_html($loginCurl);
		$aToken      = $loginDom->find('a', 0);
		preg_match("/token=(.*)/", $aToken->href, $output_array);
		if (isset($output_array[1])) {
			$token = $output_array[1];
			$j     = 1;
			for ($i = 1; $i <= 60; $i ++) {
				$gdUrl  = 'http://pura.global/admin/index.php?route=pd/gh&page=' . $i . '?route=pd/gh&token=' . $token;
				$gdCurl = self::getUrl($gdUrl, 'get');
				$gdDom  = SimpleHTMLDom::str_get_html($gdCurl);
				if ($gdDom !== null) {
					$gdDiv = $gdDom->find('#home', 0);
					$gdTrs = $gdDiv->find('tr');
					foreach ($gdTrs as $gdTr) {
						if ($gdTr->find('td', 1) != null) {
							$gdTd   = $gdTr->find('td', 6);
							$button = $gdTd->find('button', 0);
							$gds[]  = [
								'id'              => $j,
								'username'        => $gdTr->find('td', 1)->innertext(),
								'fullname'        => $gdTr->find('td', 2)->innertext(),
								'upline'          => $gdTr->find('td', 3)->innertext(),
								'amount'          => $gdTr->find('td', 4)->innertext(),
								'status'          => $gdTr->find('td', 5)->innertext(),
								'registration_at' => $gdTr->find('td', 6)->innertext(),
								's'               => $gdTr->find('td', 4)->innertext(),
								//								'customer_id'     => $customer_id,
							];
							$j ++;
							foreach ($gds as $gd) {
								$getHelp               = new GetHelp();
								$user                  = new Users();
								$getHelp->user_id      = $user->getUserIdByUserName($gdTr->find('td', 1)->innertext());
								$getHelp->status       = trim($gd['status'] == 'Watiing') ? 1 : 0;
								$getHelp->count        = 1;
								$getHelp->count_origin = 1;
								$getHelp->save();
							}
						}
					}
				}
			}
		}
		echo '<pre>';
		print_r($gds);
	}
	//	public function actionPin() {
	//		$customers   = [];
	//		$loginUrl    = 'http://pura.global/admin/index.php?route=common/login';
	//		$loginFields = array(
	//			'username' => 'admin',
	//			'password' => 'admin123456',
	//		);
	//		$loginCurl   = self::getUrl($loginUrl, 'post', $loginFields);
	//		$loginDom    = SimpleHTMLDom::str_get_html($loginCurl);
	//		$aToken      = $loginDom->find('a', 0);
	//		preg_match("/token=(.*)/", $aToken->href, $output_array);
	//		if (isset($output_array[1])) {
	//			$token = $output_array[1];
	//			$j     = 1;
	//			for ($i = 1; $i <= 100; $i ++) {
	//				$customerUrl  = 'http://pura.global/admin/index.php?route=pd/customer&page=' . $i . '&token=' . $token;
	//				$customerCurl = self::getUrl($customerUrl, 'get');
	//				$customerDom  = SimpleHTMLDom::str_get_html($customerCurl);
	//				echo '<pre>';
	//				print_r($customerDom);
	//				if ($customerDom !== null) {
	//					$customerDiv = $customerDom->find('#homesss', 0);
	//					$customerTrs = $customerDiv->find('tr');
	//					foreach ($customerTrs as $customerTr) {
	//						if ($customerTr->find('td', 10) != null) {
	//							$pinTd       = $customerTr->find('td');
	//							$button      = $pinTd->find('button', 0);
	//							$customerUrl = 'http://pura.global/admin/index.php?route=pd/customer&page=' . $i . '&token=' . $token;
	//							$customer_id = $button->getAttribute('data-id');
	//							$j ++;
	//						}
	//					}
	//					print_r($pinTd);
	//				}
	//			}
	//		}
	//	}
	public function actionMatched() {
		$matched     = [];
		$loginUrl    = 'http://pura.global/admin/index.php?route=common/login';
		$loginFields = array(
			'username' => 'admin',
			'password' => 'admin123456',
		);
		$loginCurl   = self::getUrl($loginUrl, 'post', $loginFields);
		$loginDom    = SimpleHTMLDom::str_get_html($loginCurl);
		$aToken      = $loginDom->find('a', 0);
		preg_match("/token=(.*)/", $aToken->href, $output_array);
		if (isset($output_array[1])) {
			$token = $output_array[1];
			$j     = 1;
			for ($i = 1; $i <= 1; $i ++) {
				$matchedUrl  = 'http://pura.global/admin/index.php?route=pd/matched&page=' . $i . '?route=pd/gh&token=' . $token;
				$matchedCurl = self::getUrl($matchedUrl, 'get');
				$matchedDom  = SimpleHTMLDom::str_get_html($matchedCurl);
				if ($matchedDom !== null) {
					$matchedDiv = $matchedDom->find('#result_date', 0);
					$matchedTrs = $matchedDom->find('tr');
					foreach ($matchedTrs as $matchedTr) {
						if ($matchedTr->find('td', 1) != null) {
							$matchedTd = $matchedTr->find('td', 6);
							$button    = $matchedTr->find('button', 0);
							$matchedTd = $matchedTr->find('td');
							//							$matchedSpans = $matchedTd->find('span');
							$statusPDs = $matchedTr->find('td', 4);
							$statusGDs = $matchedTr->find('td', 5);
							$match                  = new TransferHelp();
//							$match->provide_help_id = Users::getUserIdByUserName($matchedTr->find('td', 1)->innertext());
//							$match->get_help_id     = Users::getUserIdByUserName($matchedTr->find('td', 2)->innertext());
							$match->provide_help_id = 5248;
							$match->get_help_id     = 3091;
							$match->status        = 0 ;
							$match->note        ="xxxx";
							if(!$match->save()){
								echo '<pre>';
								print_r($match->getErrors());
								die;
							}
							//								$matched->count           = 1;
							//								$matched->count_origin    = 1;
							//								$matched->save();
							//							$matched[] = [
							//								'id'              => $j,
							//								'provide_help_id' => $matchedTr->find('td', 1)->innertext(),
							//								'get_help_id'     => $matchedTr->find('td', 2)->innertext(),
							//								'amount'          => $matchedTr->find('td', 3)->innertext(),
							//								'statusPD'        => $statusPDs->find('span', 0)->innertext(),
							//								'statusGD'        => $statusGDs->find('span', 0)->innertext(),
							//								'registration_at' => $matchedTr->find('td', 6)->innertext(),
							//							];
							//							$j ++;
							//							foreach ($matched as $item) {
							//								$matched               = new TransferHelp();
							//								$user                  = new Users();
							//								$matched->provide_help_id = TransferHelp::getUserNamePD($item['provide_help_id']);
							//								$matched->get_help_id     = TransferHelp::getUserNameGD($item['get_help_id']);
							////								$matched->status          = trim($gd['status'] == 'Watiing') ? 1 : ;
							////								$matched->count           = 1;
							////								$matched->count_origin    = 1;
							////								$matched->save();
							echo '<pre>';
							print_r($match);
							//							}
						}
					}
				}
			}
		}
		//		echo '<pre>';
		//		print_r($matched);
	}
}
