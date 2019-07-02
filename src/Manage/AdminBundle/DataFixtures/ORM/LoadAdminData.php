<?php

namespace Manage\AdminBundle\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Manage\AdminBundle\Entity\User;
use Manage\AdminBundle\Entity\Role;

/**
 * Admin data fixtures
 *
 * @author viko
 */
class LoadAdminData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface  {
    
    private $container;
    
    public function getOrder() {
        return 1;
    }

    public function load($manager) {
                
        $roleCMS = new Role();
        $roleCMS->setName('ROLE_CMS');
        $roleCMS->setDescription('Gestión del contenido promocional.');
        $manager->persist($roleCMS);
        
        $roleSuperAdmin = new Role();
        $roleSuperAdmin->setName('ROLE_SUPERADMIN');
        $roleSuperAdmin->setDescription('Acceso a toda la información del sistema excepto las api-keys de los hoteles.');
        $manager->persist($roleSuperAdmin);
        
        $roleWebmaster = new Role();
        $roleWebmaster->setName('ROLE_WEBMASTER');
        $roleWebmaster->setDescription('Acceso a toda la información del sistema.');
        $manager->persist($roleWebmaster);
        
        // ---
        // Users
        // ---
        $util = $this->container->get('admin.util');
        
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setName('Administrador del Sistema');
        $admin->setEmail('admin@gmail.com');
        $admin->setPassword('adminpass');
        $util->encodePassword($admin);
        $admin->setUserRole($roleWebmaster);
        $manager->persist($admin);
        
        // save all
        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;       
    }
}

?>
