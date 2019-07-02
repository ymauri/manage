<?php

namespace Manage\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
/**
 * Command controller.
 *
 * @Route("/command")
 */
class CommandController extends Controller {

    /**
     *
     * @Route("/cc/", name="cc")
     * @Method("GET")
     *
     */
    public function ccAction()
    {
        $kernel = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(array(
            'command' => 'cache:clear',
        ));

        $output = new BufferedOutput();
        $application->run($input, $output);

        $content = $output->fetch();

        return new Response($content);
    }

    /**
     *
     * @Route("/showuschema/", name="showuschema")
     * @Method("GET")
     *
     */
    public function showuschemaAction()
    {
        $kernel = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(array(
            'command' => 'doctrine:schema:update',
            '--dump-sql'=>true,
        ));

        $output = new BufferedOutput();
        $application->run($input, $output);
        $content = $output->fetch();
        return new Response($content);
    }
    /**
     *
     * @Route("/uschema/", name="uschema")
     * @Method("GET")
     *
     */
    public function uschemaAction()
    {
        $kernel = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(array(
            'command' => 'doctrine:schema:update',
            '--force'=>true,
        ));

        $output = new BufferedOutput();
        $application->run($input, $output);
        $content = $output->fetch();
        return new Response($content);
    }
}
