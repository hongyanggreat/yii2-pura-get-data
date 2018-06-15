<?php
/**
 * Created by Navatech.
 * @project yii2-pura
 * @author  Phuong
 * @email   notteen[at]gmail.com
 * @date    06/06/2018
 * @time    5:37 CH
 */

namespace frontend\controllers;

use app\models\TransferHelp;
use frontend\models\GetHelp;
use frontend\models\ProvideHelp;
use frontend\models\Users;
use DOMDocument;
use navatech\simplehtmldom\SimpleHTMLDom;
use yii\web\Controller;

class FixaController extends Controller {

	/**
	 * return data customer
	 */
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
			for ($i = 1; $i <= 100; $i ++) {
				$customerUrl  = 'http://pura.global/admin/index.php?route=pd/customer&page=' . $i . '&token=' . $token;
				$customerCurl = self::getUrl($customerUrl, 'get');
				$customerDom  = SimpleHTMLDom::str_get_html($customerCurl);
				print_r($customerDom);
				if ($customerDom !== null) {
					$customerDiv = $customerDom->find('#homesss', 0);
					$customerTrs = $customerDiv->find('tr');
					foreach ($customerTrs as $customerTr) {
						if ($customerTr->find('td', 1) != null) {
							$pdTd        = $customerTr->find('td', 6);
							$button      = $pdTd->find('button', 0);
							$customer_id = $button->getAttribute('data-id');
							$customers[] = [
								'id'              => $j,
								'username'        => $customerTr->find('td', 1)->innertext(),
								'phone'           => $customerTr->find('td', 2)->innertext(),
								'upline'          => $customerTr->find('td', 3)->innertext(),
								'registration_at' => $customerTr->find('td', 4)->innertext(),
								'customer_id'     => $customer_id,
							];
							$j ++;
							$user                                  = new Users();
							$user->username                        = $customerTr->find('td', 1)->innertext();
							$user->phone                           = $customerTr->find('td', 1)->innertext();
							$user->fullname                        = $customerTr->find('td', 3)->innertext();
							$user->registration_ip                 = '127.0.0.1';
							$user->parent_id                       = 1;
							$user->role_id                         = 2;
							$user->customer_id                     = $customer_id;
							$user->password_hash                   = '$2y$10$YlOq6nj1qFqckV807SOcu.YIBkKIfbGoN461x3NvRwSvgISyFaxjy';
							$user->secret_code                     = '$2y$10$YlOq6nj1qFqckV807SOcu.YIBkKIfbGoN461x3NvRwSvgISyFaxjy';
							$user->email                           = 'a@gmail.com';
							$user->bank_account_vietcombank_holder = '123';
							$user->bank_account_vietcombank_number = '123';
							$user->bonus                           = '123';
							$user->save();
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

	/**
	 * return data F1
	 */
	public function actionF1() {
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
			$users = Users::find()->limit(- 1)->all();
			$a     = [];
			foreach ($users as $user) {
				$f1Url  = 'http://pura.global/admin/index.php?route=pd/customer/load_f1_customer&token=' . $token . '&customer_id=' . $user->customer_id;
				$f1Data = self::getUrl($f1Url, 'get');
				/*$pdDom       = SimpleHTMLDom::str_get_html($pdData);
				$tr = $pdDom->find('tr');*/
				$htmlDoc1 = new DOMDocument;
				@$htmlDoc1->loadHTML($f1Data);
				$rows = $htmlDoc1->getElementsByTagName('tr');
				foreach ($rows as $key => $row) {
					$cols = $row->getElementsByTagName('td');
					if (is_numeric($cols->item(0)->nodeValue)) {
						$a[$user->customer_id][$key]['stt']       = $cols->item(0)->nodeValue;
						$a[$user->customer_id][$key]['username']  = $cols->item(1)->nodeValue;
						$a[$user->customer_id][$key]['telephone'] = $cols->item(2)->nodeValue;
						$a[$user->customer_id][$key]['status']    = $cols->item(3)->nodeValue;
						$a[$user->customer_id][$key]['date_add']  = $cols->item(4)->nodeValue;
						$a[$user->customer_id][$key]['pdWaiting'] = $cols->item(5)->nodeValue;
						$a[$user->customer_id][$key]['pdMatched'] = $cols->item(6)->nodeValue;
						$a[$user->customer_id][$key]['pdFinish']  = $cols->item(7)->nodeValue;
						$a[$user->customer_id][$key]['gdWaiting'] = $cols->item(8)->nodeValue;
						$a[$user->customer_id][$key]['gdMatched'] = $cols->item(9)->nodeValue;
						$a[$user->customer_id][$key]['gdFinish']  = $cols->item(10)->nodeValue;
					}
				}
			}
			echo '<pre>';
			print_r($a);
		}
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
								'upline'          => $pdTr->find('td', 2)->innertext(),
								'amount'           => $pdTr->find('td', 3)->innertext(),
								'status'           => $pdTr->find('td', 4)->innertext(),
								'registration_at' => $pdTr->find('td', 5)->innertext(),
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
							$gds[] = [
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

	public function actionPin(){
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
			for ($i = 1; $i <= 100; $i ++) {
				$customerUrl  = 'http://pura.global/admin/index.php?route=pd/customer&page=' . $i . '&token=' . $token;
				$customerCurl = self::getUrl($customerUrl, 'get');
				$customerDom  = SimpleHTMLDom::str_get_html($customerCurl);

				if ($customerDom !== null) {
					$customerDiv = $customerDom->find('#homesss', 0);
					$customerTrs = $customerDiv->find('tr');

					foreach ($customerTrs as $customerTr) {
						if ($customerTr->find('td', 10) != null) {
							$customerTd        = $customerTr->find('td', 10);
							$button      = $customerTd->find('button', 0);
							$customer_id = $button->getAttribute('data-id');
							$pinUrl  = 'http://pura.global/admin/index.php?route=pd/customer/load_ping_customer&'.'token='.$token.'&customer_id='.$customer_id;
							$pinCurl = self::getUrl($pinUrl, 'get');
							$pinDom  = SimpleHTMLDom::str_get_html($pinCurl);
							if($pinDom !== null){
								$pinDiv = $pinDom->find('.text-center',0);
								$pinH2 = $pinDiv->find('h2');
								////todo lay pin cho nay
							}
						}
					}
				}
			}
		}
		echo '<pre>';
		print_r($customers);
		die;
	}
	public function actionMatched(){
		$matched         = [];
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
				$matchedUrl  = 'http://pura.global/admin/index.php?route=pd/matched=' . $i . '?route=pd/gh&token=' . $token;
				$matchedCurl = self::getUrl($matchedUrl, 'get');
				$matchedDom  = SimpleHTMLDom::str_get_html($matchedCurl);
				if ($matchedDom !== null) {
					$matchedDiv = $matchedDom->find('#home', 0);
					$matchedTrs = $matchedDom->find('tr');
					foreach ($matchedTrs as $matchedTr) {
						if ($matchedTr->find('td', 1) != null) {
							$matchedTd   = $matchedTr->find('td', 6);
							$button = $matchedTr->find('button', 0);
							$gds[] = [
								'id'              => $j,
								'usernamePD'        => $matchedTr->find('td', 1)->innertext(),
								'usernameGD'        => $matchedTr->find('td', 2)->innertext(),
								'amount'          => $matchedTr->find('td', 3)->innertext(),
								'statusPD'          => $matchedTr->find('td', 4)->innertext(),
								'statusGD'          => $matchedTr->find('td', 5)->innertext(),
								'registration_at' => $matchedTr->find('td', 6)->innertext(),
							];
							$j ++;
							foreach ($gds as $gd) {
								$matched               = new TransferHelp();
								$user                  = new Users();
								$getHelp->user_id      = $user->getUserIdByUserName($matchedTr->find('td', 1)->innertext());
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

//	public function actionupdate
}
