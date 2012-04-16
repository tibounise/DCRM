<?php
	if (!defined("DCRM")) {
		exit;
	}

	// Sidebar
	$lang_sidebar['packages'] = array(0 => 'Packages',1 => 'Paquets');
		$lang_sidebar['add_package'] = array(0 => 'Add a package',1 => 'Ajouter un paquet');
		$lang_sidebar['manage_package'] = array(0 => 'Manage packages',1 => 'Gérer les paquets');
	$lang_sidebar['source'] = array(0 => 'Repository',1 => 'Source');
		$lang_sidebar['source_settings'] = array(0 => 'Settings of the repository',1 => 'Paramètres de la source');

	// Top buttons
	$lang_topbtn['build'] = array(0 => 'Build', 1 => 'Compiler');
	$lang_topbtn['settings'] = array(0 => 'Settings', 1 => 'Paramètres');
	$lang_topbtn['logout'] = array(0 => 'Log out', 1 => 'Se déconnecter');

	// Login page
	$lang_login['username'] = array(0 => "Username",1 => "Nom d'utilisateur");
	$lang_login['password'] = array(0 => "Password",1 => "Mot de passe");
	$lang_login['login'] = array(0 => "Log in",1 => "Se connecter");
	$lang_login['wrong_login'] = array(0 => "Your login is wrong", 1 => "Vos identifiants sont incorrects");
	$lang_login['not_enough'] = array(0 => "You havn't filled enough fields", 1 => "Vous n'avez pas rempli assez de champs");
	$lang_login['too_much'] = array(0 => "You've tried too much times to log in", 1 => "Vous avez essayé de vous connecter trop de fois");
	$lang_login['error'] = array(0 => "An error occured", 1 => "Une erreur s'est produite");
	$lang_login['login_message'] = array(0 => "DCRM - Connexion", 1 => "DCRM - Connexion");

	// Package managing page
	$lang_manage['manage'] = array(0 => 'Manage packages', 1 => 'Gérer les paquets');
	$lang_manage['nopackages'] = array(0 => "There's no packages here.", 1 => "Il n'y a pas de paquets ici.");
	$lang_manage['name'] = array(0 => "Name", 1 => "Nom");
	$lang_manage['size'] = array(0 => "Size", 1 => "Taille");
	$lang_manage['delete_confirmation'] = array(0 => "Do you want to delete", 1 => "Voulez-vous supprimer");
	$lang_manage['yes'] = array(0 => "Yes", 1 => "Oui");
	$lang_manage['no'] = array(0 => "No", 1 => "Non");
	$lang_manage['delete_message'] = array(0 => "has been deleted.", 1 => "a bien été supprimé.");
	$lang_manage['hasntpermissions'] = array(0 => "DCRM has not the permissions to delete this file.", 1 => "DCRM n'a pas les permissions pour supprimer ce fichier.");

	// Upload page
	$lang_upload['upload_title'] = array(0 => "Upload a package",1 => "Télécharger un paquet");
	$lang_upload['upload_btn'] = array(0 => "Upload",1 => "Télécharger");
	$lang_upload['legend'] = array(0 => "Select a .deb file",1 => "Choisissez un fichier .deb");
	$lang_upload['upload_successfull'] = array(0 => "Your file has been uploaded !",1 => "Votre fichier a bien été téléchargé !");
	$lang_upload['upload_unsuccessfull'] = array(0 => "Your file couldn't be uploaded :(",1 => "Votre fichier n'a pas pu être envoyé :(");
	$lang_upload['upload_unsuccessfull_reason'] = array(0 => "It may already exists, or you havn't the permissions, etc.",1 => "Il peut déjà exister, ou alors vous n'avez pas les permissions, etc.");

	// Build page
	$lang_build['title'] = array(0 => "Building your Package file",1 => "Construction de votre ficier Package");
	$lang_build['corrupted_informations'] = array(0 => "Note : Some informations of the control file from //PACKAGE// are corrupted. This package will be ignored",1 => "Note : Certaines informations du fichier de contrôle de //PACKAGE// sont corrompues. Ce fichier va être ignoré.");