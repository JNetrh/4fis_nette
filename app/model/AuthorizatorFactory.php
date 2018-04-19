<?php

namespace App\Model;

use Nette\Security\Permission;

class AuthorizatorFactory {

    /**
    * @return \Nette\Security\IAuthorizator
    */
    public function create() {
        $permission = new Permission();

        // public boolean isAllowed( string $role, string $resource, string $privilege )

        /* seznam uživatelských rolí */
	    $permission->addRole('admin');
        $permission->addRole('presigned');
        $permission->addRole('basic');
        $permission->addRole('clenove');
        $permission->addRole('aktuality');
        $permission->addRole('galerie');
        $permission->addRole('staze');
        $permission->addRole('spravaClenu');
        $permission->addRole('authenticated');

        /* seznam zdrojů */
        $permission->addResource('Admin:Aktuality');
        $permission->addResource('Admin:Clenove');
        $permission->addResource('Admin:Galerie');
        $permission->addResource('Admin:Sign');
        $permission->addResource('Admin:SpravaClenu');
        $permission->addResource('Admin:Staze');
        $permission->addResource('Admin:Uvod');
        $permission->addResource('Admin:');


	    /* zákldní pole zdrojů */
        $basicArray = array('Admin:', 'Admin:Aktuality', 'Admin:Clenove', 'Admin:Galerie', 'Admin:Sign', 'Admin:SpravaClenu', 'Admin:Staze', 'Admin:Uvod');


	    /* zákldní pole práv */
        $defaultPrivileges = array('default', 'detail', 'logout');

	    /* přiřazení základních oprávnění */
	    $permission->allow('admin', $basicArray, $defaultPrivileges);
        $permission->allow('presigned', 'Admin:Sign', array('in', 'presigned'));
        $permission->allow('basic', $basicArray, $defaultPrivileges);
        $permission->allow('clenove', $basicArray, $defaultPrivileges);
        $permission->allow('aktuality', $basicArray, $defaultPrivileges);
        $permission->allow('galerie', $basicArray, $defaultPrivileges);
        $permission->allow('staze', $basicArray, $defaultPrivileges);
        $permission->allow('spravaClenu', $basicArray, $defaultPrivileges);


        $manageProfile = array('userDetail', 'userEdit', 'handle');

        //skoro vsichni mohou upravovat svůj profil
        $permission->allow('admin', 'Admin:Clenove', $manageProfile);
        $permission->allow('basic', 'Admin:Clenove', $manageProfile);
	    $permission->allow('clenove', 'Admin:Clenove', $manageProfile);
	    $permission->allow('aktuality', 'Admin:Clenove', $manageProfile);
	    $permission->allow('galerie', 'Admin:Clenove', $manageProfile);
        $permission->allow('staze', 'Admin:Clenove', $manageProfile);
        $permission->allow('spravaClenu', 'Admin:Clenove',$manageProfile);

        $managePrivileges = array('create','delete','edit', 'handle', 'new');

        //muze upravovat Cleny
        $permission->allow('clenove', 'Admin:Clenove', $managePrivileges);
        //muze upravovat aktuality
        $permission->allow('aktuality', 'Admin:Aktuality', $managePrivileges);
        //muze upravovat galerii
        $permission->allow('galerie', 'Admin:Galerie', $managePrivileges);
	    //muze upravovat staze
	    $permission->allow('staze', 'Admin:Staze', $managePrivileges);
        //muze upravovat cleny
        $permission->allow('spravaClenu', 'Admin:SpravaClenu', $managePrivileges);


	    /* ADMIN má práva na všechno */
	    $permission->allow('admin', $basicArray, $managePrivileges);
	    $permission->allow('admin', Permission::ALL, Permission::ALL);


        return $permission;
    }

}