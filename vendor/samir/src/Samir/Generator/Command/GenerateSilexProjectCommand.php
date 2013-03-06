<?php

namespace Samir\Generator\Command;

use Silex\Application;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Command\Command as ConsoleCommand;
use Sensio\Bundle\GeneratorBundle\Command\Helper\DialogHelper;
use Sensio\Bundle\GeneratorBundle\Manipulator\RoutingManipulator;
use Doctrine\Bundle\DoctrineBundle\Mapping\MetadataFactory;
use Sensio\Bundle\GeneratorBundle\Command\Validators;
use Samir\Generator\Bundle\LazyBundleImpl;

/**
 * Generates a silex project from scratch.
 *
 * @author Samir El Aouar <samirbr@gmail.com>
 */

class GenerateSilexProjectCommand extends ConsoleCommand
{
	// generate project, web/index.php, add services
	// add config
	// add ajax remoting
	private $generator;
	
	public function __construct(Application $app, $skeletonPath = null) 
	{
		$this->app = $app;
		$this->skeletonPath = empty($skeletonPath) ? __DIR__.'/../Resources/skeleton/' : $skeletonPath;
	
		parent::__construct();
	}
	
	public function getApp($key = "")
	{
		if (empty($key)) {
			return $this->app;
		} else {
			return $this->app[$key];
		}
	}
		
	/**
	 * @see Command
	 */
	protected function configure()
	{
			$this
					->setDefinition(array(
							new InputOption('name', '', InputOption::VALUE_REQUIRED, 'The project name'),
							new InputOption('web-path', '', InputOption::VALUE_OPTIONAL, 'The path to web dir'),
							new InputOption('src-path', '', InputOption::VALUE_OPTIONAL, 'The path to src dir'),
							
					))
					->setDescription('Generates a new Silex project')
					->setHelp(<<<EOT
The <info>doctrine:generate:crud</info> command generates a CRUD based on a Doctrine entity.

The default command only generates the list and show actions.

<info>php app/console doctrine:generate:crud --entity=AcmeBlogBundle:Post --route-prefix=post_admin</info>

Using the --with-write option allows to generate the new, edit and delete actions.

<info>php app/console doctrine:generate:crud --entity=AcmeBlogBundle:Post --route-prefix=post_admin --with-write</info>
EOT
					)
					->setName('doctrine:generate:crud')
					->setAliases(array('generate:doctrine:crud'))
			;
	}

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
}