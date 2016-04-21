<?php
namespace LibretteTests\Application\PresenterFactory;

use Librette;
use Nette;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';



/**
 * @testCase
 */
class FormatPresenterClassTestCase extends Tester\TestCase
{

	/** @var Librette\Application\PresenterFactory\PresenterFactory */
	protected $presenterFactory;


	public function setUp()
	{
		$this->presenterFactory = new Librette\Application\PresenterFactory\PresenterFactory(new Mocks\PresenterObjectFactoryMock());
	}


	public function testSubmodule()
	{
		$this->presenterFactory->setMapping([
			'App'     => 'App\\*Module\\*Presenter',
			'App:Foo' => 'AppFoo\\*Module\\*Presenter',
		]);

		Assert::same('App:Foo:Bar:Lorem', $this->presenterFactory->unformatPresenterClass('AppFoo\\BarModule\\LoremPresenter'));
		Assert::same('App:Foo:Bar:Lorem', $this->presenterFactory->unformatPresenterClass('App\\FooModule\\BarModule\\LoremPresenter'));

		Assert::same('App:Bar:Foo', $this->presenterFactory->unformatPresenterClass('App\\BarModule\\FooPresenter'));
	}


	public function testMultipleMappingForModule()
	{
		$this->presenterFactory->setMapping([
			'App' => ['NS1\\*Module\\*Presenter', 'NS2\\*Module\\*Presenter'],
		]);
		$this->presenterFactory->addMapping('App', 'NS3\\*Module\\*Presenter');

		Assert::same('App:Foo:Bar', $this->presenterFactory->unformatPresenterClass('NS3\\FooModule\\BarPresenter'));
		Assert::same('App:Foo:Bar', $this->presenterFactory->unformatPresenterClass('NS2\\FooModule\\BarPresenter'));
		Assert::same('App:Foo:Bar', $this->presenterFactory->unformatPresenterClass('NS1\\FooModule\\BarPresenter'));
	}


	public function testDirectPresenterMapping()
	{
		$this->presenterFactory->setMapping([
			':Foo:Bar' => 'FooModule\\BarPresenter',
		]);
		Assert::same(':Foo:Bar', $this->presenterFactory->unformatPresenterClass('FooModule\\BarPresenter'));
	}


	public function testPresenterNameOverwrite()
	{
		$this->presenterFactory->setMapping([
			'Foo' => 'Foo\\*Module\\BarPresenter',
		]);
		//cannot be resolved (see: \LibretteTests\Application\PresenterFactory\FormatPresenterClassTestCase::testPresenterNameOverwrite)
		Assert::same('Foo:Lorem:', $this->presenterFactory->unformatPresenterClass('Foo\\LoremModule\\BarPresenter'));
	}

}



\run(new FormatPresenterClassTestCase());