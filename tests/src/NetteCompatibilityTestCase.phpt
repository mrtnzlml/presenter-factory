<?php
namespace LibretteTests\Application\PresenterFactory;

use Librette\Application\PresenterFactory\PresenterFactory;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../bootstrap.php';


/**
 * @author David Grudl
 * @author David Matejka
 * @testCase
 */
class NetteCompatibilityTestCase extends TestCase
{

	/** @var PresenterFactory */
	protected $presenterFactory;


	public function setUp()
	{
		$this->presenterFactory = new PresenterFactory(new Mocks\PresenterObjectFactoryMock());
		$this->presenterFactory->addMapping('*', '*Module\\*Presenter');
	}


	public function testFormatPresenterClasses()
	{
		$this->presenterFactory->setMapping([
			'Foo2' => 'App2\*Presenter',
			'Foo3' => 'My\App\*Presenter',
		]);


		$this->assert('Foo2Presenter', 'Foo2');
		$this->assert('App2\BarPresenter', 'Foo2:Bar');
		$this->assert('App2\BarModule\BazPresenter', 'Foo2:Bar:Baz');
		$this->assert('My\App\BarPresenter', 'Foo3:Bar');
		$this->assert('My\App\BarModule\BazPresenter', 'Foo3:Bar:Baz');

	}


	public function testFormatPresenterClassModule()
	{
		$this->presenterFactory->setMapping([
			'Foo2' => 'App2\*\*Presenter',
			'Foo3' => 'My\App\*Mod\*Presenter',
		]);

		$this->assert('FooPresenter', 'Foo');
		$this->assert('FooModule\BarPresenter', 'Foo:Bar');
		$this->assert('FooModule\BarModule\BazPresenter', 'Foo:Bar:Baz');
		$this->assert('Foo2Presenter', 'Foo2');
		$this->assert('App2\BarPresenter', 'Foo2:Bar');
		$this->assert('App2\Bar\BazPresenter', 'Foo2:Bar:Baz');
		$this->assert('My\App\BarPresenter', 'Foo3:Bar');
		$this->assert('My\App\BarMod\BazPresenter', 'Foo3:Bar:Baz');
		$this->assert('NetteModule\FooPresenter', 'Nette:Foo');
	}


	private function assert($expectedClass, $presenterName)
	{
		$classes = $this->presenterFactory->formatPresenterClasses($presenterName);
		Assert::same($expectedClass, reset($classes));
		Assert::same($presenterName, $this->presenterFactory->unformatPresenterClass($expectedClass));
	}

}




run(new NetteCompatibilityTestCase());
