<?php

use Faker\Factory;

class BotCest
{
	// логин и пароль
	private $login = 'burzev';
	private $password = 'burzev';

	// ID курсов
	private $course = [362, 2928, 350, 355];
	
	// время обучения, в часах
	private $hour = 4;

	/**
		Создание скринов
	*/
	private function fileName(){
		return "screen_".mktime();
	}

	private function screenshot($I){
		$fileName = $this->fileName();
		$I->makeScreenshot($fileName);
		$I->comment("<img src='debug/".$fileName.".png' style='width: 100%;' />");
	}

	public function _before(AcceptanceTester $I)
	{

	}

	public function _after(AcceptanceTester $I)
	{
	}

	public function walkSuccess(AcceptanceTester $I)
	{
		$I->wantTo('Авторизация на портале');

		$I->amOnPage("/login/index.php");
		$I->see('Вход');
		$I->wait(1);

		// авторизация
		$I->appendField('input[name="username"]', $this->login);
		$I->fillField('input[name="password"]', $this->password);
		//$this->screenshot($I);
		$I->click('#loginbtn');

		$I->wait(2);
		$I->dontSee("Вход");

		$arrCourseId = $this->course;

		// кол-во итераций, с расчета что в час будет порядка 150 запросов
		$max = $this->hour * 150;

		// бродим по сайту
		for($i=1; $i<$max; $i++){
			$arr = $arrCourseId;
			shuffle($arr);
			$courseId = current($arr);
			$I->amOnPage("/my/");
			$I->wait(rand(1, 10));
			$I->click('#course-info-container-'.$courseId.' > div > div.media-body > h4 > a');

			$I->wait(rand(15, 50));

			unset($arr);
			unset($courseId);
		}

		// сообщение
		$I->wantTo('Закрыли портал ДФН');
	}
}

