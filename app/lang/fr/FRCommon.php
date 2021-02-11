<?php

/*
 * The MIT License
 *
 * Copyright 2020 cjacobsen.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace app\lang\fr;

/**
 * Description of Common
 *
 * @author cjacobsen
 */

use app\lang\Language;

abstract class FRCommon
{

    use Language;

    //put your code here

    public static $strings = [
        'Administrator Full Name' => 'Administrateur',
        'Login' => 'Connexion',
        'Remember Username' => 'Remember Username',
        'Remember Me' => 'Remember Me',
        'Username' => 'Nom d\'utilisateur',
         'Password' => 'Mot de passe',
         'Group' => 'Groupe',
         'Groups' => 'Groupes',
         'Users' => 'Utilisateurs',
         'Search' => 'Rechercher',
        'User Search' => 'Recherche d\'utilisateurs',
         'Group Search' => 'Recherche de groupe',
         'Application' => 'Application',
         'Authentication' => 'Authentification',
         'Email' => 'E-mail',
         'Notification' => 'Notification',
         'Update' => 'Mettre à jour',
         'Create' => 'Créer',
         'Group Name' => 'Nom du groupe',
         'Description' => 'Description',
         'Email Address' => 'Adresse e-mail',
         'OU' => 'OU',
         'New Version Available!' => 'Une Nouvelle Version Disponible!',
    ];
    public static $help = [
        'User_Search' => 'Peut également entrer le prénom ou le nom pour rechercher un nom d\'utilisateur.',
         'Group_Search' => "Recherche par nom, e-mail ou description",
         "Add user or group to group" => "Ajouter un utilisateur ou un groupe au groupe",
        'Can also search by first or last name.' =>'Peut également rechercher par prénom ou nom de famille.',
    ];

    public static $error = [
        "No user or group was supplied to the add group members modal"=>"Aucun utilisateur ou groupe n'a été fourni au modal d'ajout de membres de groupe",
        'Object not found' => 'Objet non trouvé',
    ];

}
